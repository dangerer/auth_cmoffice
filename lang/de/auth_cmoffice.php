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
 * Strings for component 'auth_cmoffice', language 'de'.
 *
 * @package   auth_cmoffice
 * @copyright 2022 Stefan Swerk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_cmofficedescription'] = 'Ermöglicht Userlogin und Accounterstellung durch eine übergebene Typo3 Session ID ohne Authentifizierung durch einen externen Server, und ohne Email-Bestätigung - Vorsicht bei der Verwendung dieses Plugins, die Sicherheits- und Administrationsseiteneffekte müssen hier beachtet werden.';
$string['pluginname'] = 'Typo3 Token Login';
$string['privacy:metadata'] = 'Dieses Plugin hinterlegt eine immutable Zuweisung zwischen einer Typo3 User ID und einer Moodle User ID.';
$string['checknoauthdetails'] = '<p>Das <em>Typo3 authentication</em> Plugin sollte nicht in Produktionsumgebungen eingesetzt werden. Bitte dieses Plugin deaktivieren, es sei denn es handelt sich um eine Entwicklungsumgebung.</p>';
$string['checknoautherror'] = 'Das Typo3 authentication Plugin kann nicht für Produktionsumgebungen verwendet werden.';
$string['checktypo3'] = 'Typo3 Token Login';
$string['checknoauthok'] = 'Das Typo3 authentication plugin ist deaktiviert.';
$string['auth_cmoffice:config_db_driver'] = 'Typo3 DB Treiber';
$string['auth_cmoffice:config_db_driver_desc'] = 'Typo3 DB Treiber der verwendet werden soll, siehe <a href="https://adodb.org/dokuwiki/doku.php?id=v5:database:supported">hier</a>';
$string['auth_cmoffice:config_db_host'] = 'Typo3 DB Host';
$string['auth_cmoffice:config_db_host_desc'] = 'Typo3 DB Hostname oder IP Adresse die verwendet werden soll';
$string['auth_cmoffice:config_db_port'] = 'Typo3 DB Port';
$string['auth_cmoffice:config_db_port_desc'] = 'Typo3 DB Portnummer die verwendet werden soll';
$string['auth_cmoffice:config_db_name'] = 'Typo3 DB Name';
$string['auth_cmoffice:config_db_name_desc'] = 'Typo3 DB Name der verwendet werden soll';
$string['auth_cmoffice:config_db_user'] = 'Typo3 DB Benutzername';
$string['auth_cmoffice:config_db_user_desc'] = 'Typo3 DB Benutzername der verwendet werden soll';
$string['auth_cmoffice:config_db_pass'] = 'Typo3 DB Passwort';
$string['auth_cmoffice:config_db_pass_desc'] = 'Typo3 DB Password das verwendet werden soll';
$string['auth_cmoffice:config_db_table'] = 'Typo3 DB Tabelle';
$string['auth_cmoffice:config_db_table_desc'] = 'Typo3 DB Tabelle die verwendet werden soll';
$string['auth_cmoffice:config_typo_folder_slug'] = 'Typo3 Benutzerordner';
$string['auth_cmoffice:config_typo_folder_slug_desc'] = 'Typo3 Benutzerordner (slug) welcher die Benutzersätze enthält, welche Zugang zu dieser, that should be allowed to login';
$string['auth_cmoffice:generic_error'] = 'Login fehlgeschlagen. Fehler: {$a->code} Fehlermeldung: {$a->msg}. Bitte kontaktieren Sie <a href="mailto:office@skillswork.info">office@skillswork.info</a> unter Angabe dieser Fehlermeldung.';
$string['auth_cmoffice:error_msg_notauthenticated'] = 'Sie sind nicht angemeldet, bitte authentifizieren sie sich auf skillsworld.info!';
$string['auth_cmoffice:error_msg_unknownuser'] = 'Der Benutzername ist nicht im System vorhanden!';
$string['auth_cmoffice:error_msg_dbconnection'] = 'Es konnte keine Datenbankverbindung zu Skillsworld etabliert werden!';
$string['auth_cmoffice:error_msg_dbslug'] = 'Ihr Benutzer ist nicht berechtigt zur Anmeldung in diesem System (Folderslugs stimmen nicht überein), oder es wurde kein Folder Slug übermittelt!';
$string['auth_cmoffice:error_msg_dbslugmismatch'] = 'Skillsworld Folderslug stimmt nicht überein mit dem gespeicherten Moodleslug';
$string['auth_cmoffice:error_msg_dbusermismatch'] = 'Skillworld Benutzer und Moodle Benutzer stimmen nicht überein!';
$string['auth_cmoffice:error_msg_createuserinvalidparam'] = 'Ungültiger Parameter, Benutzer kann nicht erstellt werden!';
$string['auth_cmoffice:error_msg_createmapping'] = 'Es konnte kein Typo3/Moodle user mapping erfolgen!';
$string['auth_cmoffice:error_msg_createuser'] = 'Es konnte kein Benutzer erstellt werden!';
$string['auth_cmoffice:generic_info'] = 'Information: {$a->code} Meldung: {$a->msg}.';
$string['auth_cmoffice:info_msg_gotologin'] = 'Bitte melden sie sich auf https://skillsworld.at an';
