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
 * Admin settings and defaults.
 *
 * @package auth_cmoffice
 * @copyright  2022 Stefan Swerk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_cmoffice/pluginname', '',
        new lang_string('auth_cmofficedescription', 'auth_cmoffice')));

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('cmoffice');
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
        get_string('auth_fieldlocks_help', 'auth'), false, false);

    // db driver to use for accessing the typo3 DB
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_driver',
        get_string('auth_cmoffice:config_db_driver', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_driver_desc', 'auth_cmoffice'),
        'mysqli', PARAM_ALPHANUMEXT);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // Typo3 db host
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_host',
        get_string('auth_cmoffice:config_db_host', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_host_desc', 'auth_cmoffice'),
        'localhost', PARAM_HOST);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // Typo3 db port
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_port',
        get_string('auth_cmoffice:config_db_port', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_port_desc', 'auth_cmoffice'),
        '', PARAM_INT);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // db name
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_name',
        get_string('auth_cmoffice:config_db_name', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_name_desc', 'auth_cmoffice'),
        '', PARAM_ALPHANUMEXT);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // db user
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_user',
        get_string('auth_cmoffice:config_db_user', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_user_desc', 'auth_cmoffice'),
        '', PARAM_ALPHANUMEXT);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // db password
    $setting = new admin_setting_configpasswordunmask('auth_cmoffice/config_db_pass',
        get_string('auth_cmoffice:config_db_pass', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_pass_desc', 'auth_cmoffice'), '');
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // db table
    $setting = new admin_setting_configtext('auth_cmoffice/config_db_table',
        get_string('auth_cmoffice:config_db_table', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_db_table_desc', 'auth_cmoffice'),
        '', PARAM_ALPHANUMEXT);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);

    // TODO add remaining settings

    // Typo3 Folder containing user records
    $setting = new admin_setting_configtext('auth_cmoffice/config_typo_folder_slug',
        get_string('auth_cmoffice:config_typo_folder_slug', 'auth_cmoffice'),
        get_string('auth_cmoffice:config_typo_folder_slug_desc', 'auth_cmoffice'),
        '', PARAM_RAW);
    $setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
    $settings->add($setting);
}
