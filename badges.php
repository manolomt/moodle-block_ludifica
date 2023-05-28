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
 * badges management page.
 *
 * @package   block_ludifica
 * @copyright 2023 David Arias @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$msg = optional_param('msg', '', PARAM_TEXT);

require_login();

// Redirect if the user is a guest.
if (isguestuser()) {
    $url = new moodle_url($CFG->wwwroot);
    redirect($url);
    die();
}

$syscontext = context_system::instance();
$hasmanage = has_capability('block/ludifica:manage', $syscontext);

$networks = get_config('block_ludifica', 'networks');
$networkslist = explode("\n", $networks);
$socialnetworks = [];

foreach ($networkslist as $one) {

    $row = explode('|', $one);
    if (count($row) >= 2) {
        $network = new \stdClass();
        $network->icon = trim($row[0]);
        $network->url = trim($row[1]);
        $socialnetworks[] = $network;
    }
}

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/badges.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('badges', 'block_ludifica'));
$PAGE->set_title(get_string('badges', 'block_ludifica'));
$PAGE->requires->js_call_amd('block_ludifica/badges', 'init', [$socialnetworks]);

\block_ludifica\controller::include_templatecss();

echo $OUTPUT->header();

$renderable = new \block_ludifica\output\badges();

$renderer = $PAGE->get_renderer('block_ludifica');

if (!empty($msg)) {
    $msg = get_string($msg, 'block_ludifica');
    echo $OUTPUT->notification($msg, 'notifysuccess');
}

echo $renderer->render($renderable);

echo $OUTPUT->footer();
