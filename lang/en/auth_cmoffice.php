<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_cmoffice', language 'en'.
 *
 * @package   auth_cmoffice
 * @copyright 2022 Stefan Swerk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_cmofficedescription'] = 'Users can sign in and create valid accounts immediately, if they provide a Typo3 session id, with no authentication against an external server and no confirmation via email.  Be careful using this option - think of the security and administration problems this could cause.';
$string['pluginname'] = 'Typo3 authentication';
$string['privacy:metadata'] = 'The Typo3 authentication plugin stores a mapping between a Typo3 user ID and a Moodle user ID.';
$string['checknoauthdetails'] = '<p>The <em>Typo3 authentication</em> plugin is not intended for production sites. Please disable it unless this is a development test site.</p>';
$string['checknoautherror'] = 'The Typo3 authentication plugin cannot be used on production sites.';
$string['checktypo3'] = 'Typo3 authentication';
$string['checknoauthok'] = 'The Typo3 authentication plugin is disabled.';
$string['auth_cmoffice:config_db_driver'] = 'Typo3 DB access driver';
$string['auth_cmoffice:config_db_driver_desc'] = 'Typo3 DB access driver to use, see <a href="https://adodb.org/dokuwiki/doku.php?id=v5:database:supported">here</a>';
$string['auth_cmoffice:config_db_host'] = 'Typo3 DB Host';
$string['auth_cmoffice:config_db_host_desc'] = 'Typo3 DB hostname or IP address to use';
$string['auth_cmoffice:config_db_port'] = 'Typo3 DB Port';
$string['auth_cmoffice:config_db_port_desc'] = 'Typo3 DB port number to use';
$string['auth_cmoffice:config_db_name'] = 'Typo3 DB Name';
$string['auth_cmoffice:config_db_name_desc'] = 'Typo3 DB Name to use';
$string['auth_cmoffice:config_db_user'] = 'Typo3 DB Username';
$string['auth_cmoffice:config_db_user_desc'] = 'Typo3 DB Username to use';
$string['auth_cmoffice:config_db_pass'] = 'Typo3 DB access password';
$string['auth_cmoffice:config_db_pass_desc'] = 'Typo3 DB access password to use';
$string['auth_cmoffice:config_db_table'] = 'Typo3 DB table';
$string['auth_cmoffice:config_db_table_desc'] = 'Typo3 DB table to use';
$string['auth_cmoffice:config_typo_folder_slug'] = 'Typo3 User Folder';
$string['auth_cmoffice:config_typo_folder_slug_desc'] = 'Typo3 folder (slug) containing the user records, that should be allowed to login';
$string['auth_cmoffice:generic_error'] = 'Login failed. Error: {$a->code} Error message: {$a->msg}. Please contact <a href="mailto:office@skillswork.info">office@skillswork.info</a> and provide this error message.';
$string['auth_cmoffice:error_msg_notauthenticated'] = 'You are not authenticated, please perform login to Typo3';
$string['auth_cmoffice:error_msg_unknownuser'] = 'Unknown Typo3/Moodle user';
$string['auth_cmoffice:error_msg_dbconnection'] = 'Typo3 DB Connection could not be established';
$string['auth_cmoffice:error_msg_dbslug'] = 'Error retrieving Typo3 Folder Slug, or Slug is invalid';
$string['auth_cmoffice:error_msg_dbslugmismatch'] = 'Typo3 folder slug does not match stored Moodle slug';
$string['auth_cmoffice:error_msg_dbusermismatch'] = 'Typo3/Moodle username mismatch';
$string['auth_cmoffice:error_msg_createuserinvalidparam'] = 'Invalid parameters for user creation';
$string['auth_cmoffice:error_msg_createmapping'] = 'Unable to create new Typo3/Moodle user mapping';
$string['auth_cmoffice:error_msg_createuser'] = 'Unable to create new Moodle user';
$string['auth_cmoffice:generic_info'] = 'Information: {$a->code} Message: {$a->msg}.';
$string['auth_cmoffice:info_msg_gotologin'] = 'Please login at https://skillsworld.at!';

