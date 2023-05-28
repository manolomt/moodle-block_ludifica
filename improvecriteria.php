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
 * criteria edition page.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$badgeid = required_param('badgeid', PARAM_INT);

require_login();

$badge = new \core_badges\badge($badgeid);

$syscontext = context_system::instance();
require_capability('block/ludifica:manage', $syscontext);

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/improvecriteria.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('improvecriteria', 'block_ludifica'));
$PAGE->set_title(get_string('improvecriteria', 'block_ludifica'));

// Validation. Only can improve the criteria of cohort type.
$validimprove = false;
foreach ($badge->criteria as $criteria) {
    if ($criteria->criteriatype == BADGE_CRITERIA_TYPE_COHORT) {
        $validimprove = true;
    }
}

if (!$validimprove) {
    throw new moodle_exception('invalidbadgecriteriatype', 'block_ludifica');
}

$list = $DB->get_records('block_ludifica_criteria', ['badgeid' => $badgeid]);

// Currently only one criteria is allowed.
if (count($list) >= 1) {
    $criteria = array_shift($list);
} else {
    $criteria = new stdClass();
    $criteria->badgeid = $badgeid;
    $criteria->type = null;
}

// First create the form.
$editform = new \block_ludifica\forms\improvecriteria(null, ['data' => $criteria]);

if ($editform->is_cancelled()) {
    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/badges.php');
    redirect($url);
} else if ($data = $editform->get_data()) {

    $criteria->type = $data->type;
    $instance = \block_ludifica\controller::get_badges_improvecriteria($data->type);

    $criteria->settings = $instance->encode_settings($data);

    if (!empty($criteria->id)) {
        $DB->update_record('block_ludifica_criteria', $criteria);

        $event = \block_ludifica\event\criteria_updated::create([
            'objectid' => $criteria->id,
            'context' => $syscontext,
            'other' => [
                'type' => $data->type,
                'badgeid' => $badgeid,
            ],
        ]);
        $event->trigger();
    } else {
        $id = $DB->insert_record('block_ludifica_criteria', $criteria, true);

        $event = \block_ludifica\event\criteria_created::create([
            'objectid' => $id,
            'context' => $syscontext,
            'other' => [
                'type' => $data->type,
                'badgeid' => $badgeid,
            ],
        ]);
        $event->trigger();
    }

    $url = new moodle_url($CFG->wwwroot . '/blocks/ludifica/badges.php', ['msg' => 'improvecriteriasaved']);
    redirect($url);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('improvecriteria', 'block_ludifica'));

$editform->display();

echo $OUTPUT->footer();
