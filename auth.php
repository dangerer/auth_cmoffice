<?php
/**
 * Anybody can login with a Typo3 session ID.
 *
 * If this plugin is used as Moodle authentication method, a Typo3 Session ID replaces the entire password based authentication process.
 * If somebody manages to intercept the Typo3 Session Token of a user, he will immediately gain access to the running Moodle platform.
 * This plugin is not capable of validating a Typo3 Session Token, and Typo3 is not able to authenticate Moodle users.
 * Therefore the software is provided "as is", without warranty of any kind, express or implied, including but
 * not limited to the warranties of merchantability, fitness for a particular purpose and noninfringement.
 * In no event shall the Author or copyright holder be liable for any claim, damages or other liability,
 * whether in an action of contract, tort or otherwise, arising from, out of or in connection
 * with the software or the use or other dealings in the software.
 *
 * @package auth_cmoffice
 * @author Stefan Swerk
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/login/lib.php');

class auth_plugin_cmoffice extends auth_plugin_base {
    const TYPO_3_SESSID_PARAM = 'token';
    const TYPO_3_AUTH_TYPE = 'cmoffice';

    private $typo3SessionId;

    public function __construct() {
        $this->authtype = self::TYPO_3_AUTH_TYPE;
        $this->config = get_config('auth_cmoffice');
        $this->typo3SessionId = null;
    }

    /**
     * Hook for overriding behaviour of login page.
     * This method is called from login/index.php page for all enabled auth plugins.
     */
    function loginpage_hook() {
        global $frm;  // can be used to override submitted login form
        global $user; // can be used to replace authenticate_user_login()
        global $CFG;

        // TODO allow local admin login, maybe: if there is no Typo3 session id in the request, do local login, else typo3
        $this->retrieve_typo3_session_param();
        if(!empty($this->typo3SessionId)) {
            $this->pre_loginpage_hook();
            // Dakora redirect added by Dietmar Angerer
            $dakoraUrl = trim(get_config('exacomp', 'dakora_url'));
			//dk=2000
			$redirecttodakora = optional_param('dk', '0', PARAM_INT);
            if ($redirecttodakora==2000) {
                //$dakoraUrl = $CFG->wwwroot . '/blocks/exacomp/applogin.php?action=dakora_sso&sesskey=' . sesskey(); // FIXME: unused?

				redirect ("https://skillswork.info/dakora-plus/login?do_login=true&moodle_url=".$CFG->wwwroot);
            } else {
                //redirect ("https://skillswork.info/dakora-plus");
                redirect($CFG->wwwroot); //by stefan: I uncommented this again, since otherwise it will not work as intended locally
            }
        } else {
            // allow local login
        }
    }

    /**
     * Will check if we have to redirect before going to login page
     */
    public function pre_loginpage_hook() {
        global $DB,$CFG;

        // TODO allow local admin login, maybe: if there is no Typo3 session id in the request, do local login, else typo3
        if(isloggedin()) {
            // TODO maybe redirect to session->wantsurl?
        } else {
            /*
             * 1. retrieve TYPO3 Token
             * 2. look up Typo3 DB, find Typo3 User ID mapped to Token
             * 3. look up Typo3 DB find Folder that contains User ID
             * 4. if Folder != Folder information stored within this plugin configuration, throw error
             * 5. look up this plugin DB Typo3 User ID mapping to Moodle User ID mapping,
             * 5.1 if not found, create new user, fill required fields and create a mapping typo3uid <-> moodleuid
             * 6. retrieve moodle user record/find username
             * 7. call authenticate_user_login
             * 8. call complete_user_login
             */
            $this->retrieve_typo3_session_param();
            if(empty($this->typo3SessionId)) {
				
				if ($_SERVER["HTTP_REFERER"] == $CFG->wwwroot."/my/"){  //logout, keine Fehlermeldung, sondern nur Info anzeigen
					$this->showInfo(get_string('auth_cmoffice:info_msg_gotologin', 'auth_cmoffice'), 3435);
				}else{
					$this->showError(get_string('auth_cmoffice:error_msg_notauthenticated', 'auth_cmoffice'), 3431);
				}
                return;
            }

            $moodleUserID = $this->get_moodle_uid_by_typo_3_token($DB, $this->typo3SessionId, true);
            $this->typo3SessionId = null; // we no longer need this value, dispose it
			//echo  $moodleUserID;die;
            if(!$moodleUserID || !$this->exists_user_by_id($moodleUserID)) {
                $this->showError(get_string("auth_cmoffice:error_msg_unknownuser", 'auth_cmoffice'), 3432);
                return;
            }

            $moodleUserName = $this->get_user_by_id($moodleUserID)->username;
            $moodleUser = authenticate_user_login($moodleUserName, null); // this will call user_login function below
            if($moodleUser) {
                complete_user_login($moodleUser);
            } else {
                // TODO in case authentication failed, e.g. user is not allowed to login
                $this->showError(get_string('auth_cmoffice:error_msg_notauthenticated', 'auth_cmoffice'), 3433);
                //redirect('https://typo-url', 'Message on redirect', 10000, \core\output\notification::NOTIFY_ERROR);
            }
        }
    }

    private function get_moodle_uid_by_typo_3_token(moodle_database $DB, string $t3sid, bool $createUser=false) : bool|int {
        $authdb = $this->connect_to_typo3_db();
		//echo $t3sid."---";die;
        if(!$authdb || !$authdb->IsConnected()) {
            $this->showError(get_string('auth_cmoffice:error_msg_dbconnection', 'auth_cmoffice'), 3434);
            return false;
        }

        $localMoodleFolderSlug = $this->get_moodle_config_typo3_folder_slug();
        $remoteTypo3FolderSlug = $this->retrieve_typo3_folder_slug($authdb, $this->typo3SessionId);
//echo $localMoodleFolderSlug."--".$remoteTypo3FolderSlug;die;
        if($remoteTypo3FolderSlug!=="superadmin" && //some admin users who are in typo3 folder superadmin should have access to all moodles (added by Dietmar Angerer)
            (empty($remoteTypo3FolderSlug) || $remoteTypo3FolderSlug !== $localMoodleFolderSlug)) {
            // more/less than one t3sid is not allowed, unless a user belongs to the superadmin Folder
            $this->showError(get_string('auth_cmoffice:error_msg_dbslug', 'auth_cmoffice'), 3421);
            $authdb->Close();
            return false;
        }

        // retrieve user info from Typo3 DB
        $typo3UserID = clean_param($this->retrieve_typo3_userid($authdb, $this->typo3SessionId), PARAM_INT);
        $typo3UserName = clean_param($this->retrieve_typo3_username($authdb, $this->typo3SessionId), PARAM_ALPHANUMEXT);
        $uidMappingRecord = $this->get_uid_mapping($DB, $typo3UserID); // lookup internal Typo3/Moodle UID Mapping

		if (!$uidMappingRecord)  {
			if ($this->check_if_user_is_created_from_webservice($DB, $typo3UserID,$typo3UserName,$remoteTypo3FolderSlug)){
				$uidMappingRecord = $this->get_uid_mapping($DB, $typo3UserID); 
			}
		}
		
        $moodleUserID = false;
        if($uidMappingRecord) {
            // there is an entry with a matching Typo3 UID, therefore we verify the slug
            if($uidMappingRecord->t3_slug === $localMoodleFolderSlug) { // TODO if required validate remote slug as well
                $moodleUserID = $uidMappingRecord->mdl_uid;
            } else { // slug does not match
                $this->showError(get_string('auth_cmoffice:error_msg_dbslugmismatch', 'auth_cmoffice'), 3422);
                $authdb->Close();
                return false;
            }
        } else if($createUser) {
            // there is no matching entry, therefore we create a new Mapping and a new Moodle user, if no such username already exists
            if(!$this->exists_user_by_username($typo3UserName)) {
                $typo3FirstName = $this->retrieve_typo3_firstname($authdb, $this->typo3SessionId);
                $typo3LastName = $this->retrieve_typo3_lastname($authdb, $this->typo3SessionId);
                $typo3Email = $this->retrieve_typo3_email($authdb, $this->typo3SessionId);
                if(empty($typo3FirstName) || empty($typo3LastName) || empty($typo3Email)) {
                    // TODO add further parameter validation
                    $this->showError(get_string('auth_cmoffice:error_msg_createuserinvalidparam', 'auth_cmoffice'), 3423);
                    $authdb->Close();
                    return false;
                }
                $newUser = $this->create_user($typo3UserName, $typo3FirstName, $typo3LastName, $typo3Email);
                $newMapping = $this->create_uid_mapping($DB, $typo3UserID, $remoteTypo3FolderSlug, $newUser);
                if(!$newMapping) {
                    $this->showError(get_string('auth_cmoffice:error_msg_createmapping', 'auth_cmoffice'), 3424);
                    $authdb->Close();
                    return false;
                }
                $moodleUserID = $newUser->id;
            } else { // there is an already existing Moodle username, but no mapping, bail out
                $this->showError(get_string('auth_cmoffice:error_msg_createuser', 'auth_cmoffice'), 3425);
                $authdb->Close();
                return false;
            }
        } else {
            $this->showError(get_string('auth_cmoffice:error_msg_createuser', 'auth_cmoffice'), 2426);
            $authdb->Close();
            return false;
        }

        $authdb->Close();
        return $moodleUserID;
    }

    /**
     * Post authentication hook.
     *
     * This method is called from authenticate_user_login() for all enabled auth plugins.
     *
     * @param object $user user object, later used for $USER
     * @param string $username (with system magic quotes)
     * @param string $password plain text password (with system magic quotes)
     */
    public function user_authenticated_hook(&$user, $username, $password) {
        global $DB;
    }

    /**
     * Returns true if a typo3 session token has been found and a corresponding mapping to a Moolde UID exists and false
     * if there is no such token or user id, or the given username does not match the username found in the DB.
     * The password parameter is ignored
     *
     * @param string $username The username
     * @param string $password The password, ignored
     * @return bool true, if there is a Typo3 session id mapped to a Moodle user
     * @throws coding_exception
     */
    function user_login ($username, $password) {
        global $CFG, $DB;

        // TODO: add login logic here, verify token & db records again
        $this->retrieve_typo3_session_param();
        if(empty($this->typo3SessionId)) {
            $this->showError(get_string('auth_cmoffice:error_msg_notauthenticated', 'auth_cmoffice'), 3427);
            return false;
        }

        $moodleUserID = $this->get_moodle_uid_by_typo_3_token($DB, $this->typo3SessionId, false);
        $this->typo3SessionId = null; // we no longer need this value, dispose it
        if(!$moodleUserID || !$this->exists_user_by_id($moodleUserID)) {
            $this->showError(get_string('auth_cmoffice:error_msg_unknownuser', 'auth_cmoffice'), 3428);
            return false;
        }

        $moodleUserName = $this->get_user_by_id($moodleUserID)->username;
        if(!$moodleUserName || $moodleUserName !== $username) {
            $this->showError(get_string('auth_cmoffice:error_msg_dbusermismatch', 'auth_cmoffice'), 3429);
            return false;
        }

        // TODO implement the login conditions here, compare token/etc

        return true;
    }

    /**
     * Not used / returns false
     *
     * @return boolean false
     *
     */
    function user_update_password($user, $newpassword) {
        return false;
    }

    function prevent_local_passwords() {
        return true;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }

    function exists_user_by_id(int $uid): bool
    {
        global $DB;
        return $DB->record_exists('user', ['id' => $uid]);
    }

    function exists_user_by_username(string $username): bool
    {
        global $DB;
        return $DB->record_exists('user', ['username' => $username]);
    }

    function get_user_by_id(int $uid): bool|stdClass
    {
        global $DB;

        if($this->exists_user_by_id($uid)) {
            return $DB->get_record('user', ['id' => $uid]);
        }

        return false;
    }

    function store_wantsurl() {
        global $SESSION, $CFG;
        // First, let's remember where we were trying to get to before we got here
        if (empty($SESSION->wantsurl)) {
            $SESSION->wantsurl = null;
            $referer = get_local_referer(false);
            if ($referer &&
                $referer != $CFG->wwwroot &&
                $referer != $CFG->wwwroot . '/' &&
                $referer != $CFG->wwwroot . '/login/' &&
                $referer != $CFG->wwwroot . '/login/index.php') {
                $SESSION->wantsurl = $referer;
            }
        }
    }

    private function create_user(string $username, string $firstname, string $lastname, string $email): bool|stdClass
    {
        $username = clean_param($username, PARAM_ALPHANUMEXT);
        $firstname = clean_param($firstname, PARAM_ALPHANUMEXT);
        $lastname = clean_param($lastname, PARAM_ALPHANUMEXT);
        $email = clean_param($email, PARAM_EMAIL);

        if($this->exists_user_by_username($username) || empty($username)) {
            return false;
        }

        $user = create_user_record($username, null, self::TYPO_3_AUTH_TYPE);
        if($user) {
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            user_update_user($user, false, true);
        }

        return $user;
    }

    /**
     * Tries to connect to a external DB containing Typo3 related data, using the AdoDB library, forcing a new connection.
     * Make sure that the required plugin settings (DB driver, host, port, user, pass, dbname, dbtable) are setup correctly.
     *
     * @return bool|ADOConnection the new ADODB connection to the external DB, false otherwise
     * @throws coding_exception
     */
    private function connect_to_typo3_db(): bool|ADOConnection
    {
        global $CFG;
        require_once($CFG->libdir.'/adodb/adodb.inc.php');

        // Connect to the external database (forcing new connection).
//echo $this->config->config_db_driver;die;
        $authdb = ADONewConnection($this->config->config_db_driver);
        if(!$authdb) {
            $this->showError(get_string('auth_cmoffice:error_msg_dbconnection', 'auth_cmoffice'), 3430);
            return false;
        }
        if (!empty($this->config->debugtypo3db)) {
            $authdb->debug = true;
            ob_start(); //Start output buffer to allow later use of the page headers.
        }
        if(!empty($this->config->config_db_port)) {
            $authdb->port = $this->config->config_db_port;
        }
		//echo $this->config->config_db_host;die;
        $dbuser = $this->config->config_db_user ?: $CFG->dbuser;
        $dbpass = $this->config->config_db_pass ?: $CFG->dbpass;

if(!$authdb->Connect($this->config->config_db_host, $dbuser, $dbpass, $this->config->config_db_name, true)) {
            $this->showError(get_string('auth_cmoffice:error_msg_dbconnection', 'auth_cmoffice'), 3430);
            return false;
        }
		
        
        $authdb->SetFetchMode(ADODB_FETCH_ASSOC);

        return $authdb;
    }

    private function retrieve_typo3_session_param(): string
    {
        $this->typo3SessionId = optional_param(self::TYPO_3_SESSID_PARAM, '', PARAM_ALPHANUMEXT);
        return $this->typo3SessionId;
    }

    private function get_moodle_config_typo3_folder_slug() : string {
        return $this->config->config_typo_folder_slug;
    }

    private function get_moodle_config_typo3_table_name() : string {
        return $this->config->config_db_table ?? "moodlesso_users";
    }

    private function retrieve_typo3_field_value(ADOConnection $db, string $t3sid, string $fieldName) : ?string
    {
        $tokenCol = $db->param('token');
        $bindVars = array('token' => $db->addQ($t3sid));

        if($db && $db->IsConnected() && !empty($t3sid)) {
            $res = $db->GetArray("select ". $fieldName ." from " . $this->get_moodle_config_typo3_table_name() . " where token=$tokenCol", $bindVars);
            if(count($res) === 1) {
                return $res[0][$fieldName];
            }
        }

        return null;
    }

    private function retrieve_typo3_folder_slug(ADOConnection $db, string $t3sid) : ?string
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "folderslug");
    }

    private function retrieve_typo3_userid(ADOConnection $db, string $t3sid) : ?int
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "userid");
    }

    private function retrieve_typo3_username(ADOConnection $db, string $t3sid) : ?string
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "username");
    }

    private function retrieve_typo3_firstname(ADOConnection $db, string $t3sid) : ?string
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "first_name");
    }

    private function retrieve_typo3_lastname(ADOConnection $db, string $t3sid) : ?string
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "last_name");
    }

    private function retrieve_typo3_email(ADOConnection $db, string $t3sid) : ?string
    {
        return $this->retrieve_typo3_field_value($db, $t3sid, "email");
    }

    private function get_uid_mapping(moodle_database $DB, int $typo3UID) : stdClass|bool {
        $uidMappingRecord = $DB->get_record("auth_cmoffice", ['t3_uid'  => $typo3UID], '*', IGNORE_MISSING);

        return $uidMappingRecord;
    }

    private function create_uid_mapping(moodle_database $DB, int $typo3UID, string $t3FolderSlug, stdClass $moodleUser) : bool|int {
        if(!isset($moodleUser->id)) {
            return false; // invalid user, must contain an ID
        }
        $uidMappingRecord = new stdClass();
        $uidMappingRecord->t3_uid = $typo3UID;
        $uidMappingRecord->mdl_uid = $moodleUser->id;
        $uidMappingRecord->t3_slug = $t3FolderSlug;

        return $DB->insert_record("auth_cmoffice", $uidMappingRecord);
    }
	
	private function check_if_user_is_created_from_webservice(moodle_database $DB, int $typo3UID, string $typo3UserName, string $typo3FolderSlug) : stdClass|bool {
		$mdlUser = $DB->get_record("user", ['idnumber'  => $typo3UID, 'username' => $typo3UserName], '*', IGNORE_MISSING);
		if ($mdlUser) {
			$newMapping = $this->create_uid_mapping($DB, $typo3UID, $typo3FolderSlug, $mdlUser);
			$userupdate = new stdClass();
			$userupdate->id = $mdlUser->id; 
			$userupdate->auth = 'cmoffice';  // The field and the new value that you want to update.
			$DB->update_record('user', $userupdate);
			return true;
		}
        return false;
    }

    private function showError(string $msg, int $errorCode): void {
        \core\notification::error(get_string('auth_cmoffice:generic_error', 'auth_cmoffice',
            ['code' => $errorCode, 'msg' => $msg]));
    }
	private function showInfo(string $msg, int $errorCode): void {
        \core\notification::info(get_string('auth_cmoffice:generic_info', 'auth_cmoffice',
            ['code' => $errorCode, 'msg' => $msg]));
    }

}


