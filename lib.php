<?php
//
// It is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package    block_ludifica
 * @copyright  2021 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function block_ludifica_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $USER;

    require_login();

    $entryid = (int) array_shift($args);

    // Fetch file info.
    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/block_ludifica/$filearea/$entryid/$relativepath";

    if (!($file = $fs->get_file_by_hash(sha1($fullpath))) || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, false, $options);
}

function block_ludifica_inplace_editable($itemtype, $itemid, $newvalue) {
    if ($itemtype === 'nickname') {
        global $DB, $USER, $CFG;

        \external_api::validate_context(context_system::instance());

        $record = $DB->get_record('block_ludifica_general', array('id' => $itemid), '*', MUST_EXIST);

        if (isguestuser($USER) || $USER->id != $record->userid) {
            print_error('cannotchangeprofiletoother');
        }

        // Check permission of the user to update your profile.
        require_capability('moodle/user:editownprofile', context_system::instance());

        // Clean input and update the record.
        $newvalue = substr(clean_param($newvalue, PARAM_NOTAGS), 0, 31);
        $record->nickname = $newvalue;

        $DB->update_record('block_ludifica_general', $record);

        $newvalue = empty($newvalue) ? fullname($USER) : $newvalue;

        // Prepare the element for the output:
        return new \core\output\inplace_editable('block_ludifica', 'nickname', $itemid, true,
            format_string($newvalue), $newvalue, get_string('edit'),
            get_string('newnickname', 'block_ludifica', format_string($newvalue)));
    }
}