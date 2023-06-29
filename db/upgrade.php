<?php
/*
 * Copyright (c) 2022 Stefan Swerk
 * All rights reserved.
 *
 * Unless required by applicable law or agreed to in writing, software is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade auth_none.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_cmoffice_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022111804) {

        // Define table auth_cmoffice to be created.
        $table = new xmldb_table('auth_cmoffice');

        // Adding fields to table auth_cmoffice.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('t3_uid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mdl_uid', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('t3_slug', XMLDB_TYPE_CHAR, '1333', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table auth_cmoffice.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_mdl_uid', XMLDB_KEY_FOREIGN, ['mdl_uid'], 'user', ['id']);

        // Conditionally launch create table for auth_cmoffice.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Typo3 savepoint reached.
        upgrade_plugin_savepoint(true, 2022111804, 'auth', 'cmoffice');
    }


    return true;
}
