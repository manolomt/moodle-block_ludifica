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
 * This file keeps track of upgrades to the block
 *
 * @package block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade code for the ludifica block.
 *
 * @param int $oldversion
 */
function xmldb_block_ludifica_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021031204.02) {

        // Define field objectid to be added to block_ludifica_userpoints.
        $table = new xmldb_table('block_ludifica_userpoints');
        $field = new xmldb_field('objectid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'courseid');

        // Conditionally launch add field objectid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ludifica savepoint reached.
        upgrade_block_savepoint(true, 2021031204.02, 'ludifica');
    }

    if ($oldversion < 2021031208) {

        // Create the new table block_ludifica_criteria.
        $table = new xmldb_table('block_ludifica_criteria');

        // Adding fields to table block_ludifica_criteria.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('badgeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '31', null, XMLDB_NOTNULL, null, null);
        $table->add_field('settings', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_ludifica_criteria.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('badgeid', XMLDB_KEY_FOREIGN, ['badgeid'], 'badge', ['id']);

        // Adding indexes to table block_ludifica_criteria.
        $table->add_index('type', XMLDB_INDEX_NOTUNIQUE, ['type']);

        // Conditionally launch create table for block_ludifica_criteria.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ludifica savepoint reached.
        upgrade_block_savepoint(true, 2021031208, 'ludifica');
    }

    return true;
}
