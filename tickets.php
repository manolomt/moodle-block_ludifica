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

require_once('../../config.php');

$query = optional_param('q', '', PARAM_TEXT);
$spage = optional_param('spage', 0, PARAM_INT);
$sort = optional_param('sort', 'name', PARAM_TEXT);
$bypage = optional_param('bypage', 20, PARAM_INT);

require_login();

$syscontext = context_system::instance();

$PAGE->set_context($syscontext);
$PAGE->set_url('/blocks/ludifica/tickets.php');
$PAGE->set_pagelayout('incourse');
$PAGE->set_heading(get_string('tickets', 'block_ludifica'));
$PAGE->set_title(get_string('tickets', 'block_ludifica'));

$hasmanage = has_capability('block/ludifica:manage', $syscontext);

$sortavailable = array('name', 'available', 'availabledate', 'cost');
if (!in_array($sort, $sortavailable)) {
    $sort = 'name';
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('tickets', 'block_ludifica'));

$tickets = $DB->get_records('block_ludifica_ticket', null, $sort . ' ASC', '*', $spage * $bypage, $bypage);
$ticketscount = $DB->count_records('block_ludifica_ticket');


$pagingbar = new paging_bar($ticketscount, $spage, $bypage, "/blocks/ludifica/index.php?q={$query}&amp;sort={$sort}&amp;");
$pagingbar->pagevar = 'spage';

$renderable = new \block_ludifica\output\tickets($tickets);
$renderer = $PAGE->get_renderer('block_ludifica');

echo $renderer->render($renderable);

echo $OUTPUT->render($pagingbar);

echo $OUTPUT->footer();