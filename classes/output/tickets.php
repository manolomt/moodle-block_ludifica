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
 * Class containing renderers for the block.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\output;
defined('MOODLE_INTERNAL') || die();

//include_once('ludifica.class.php');

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tickets implements renderable, templatable {

    /**
     * var array Tickets list.
     */
    private $tickets;

    /**
     * Constructor.
     *
     * @param array $tickets The tickets list.
     */
    public function __construct($tickets) {

        $this->tickets = $tickets;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $USER, $DB;

        $syscontext = \context_system::instance();
        $hasmanage = has_capability('block/ludifica:manage', $syscontext);

        $player = new \block_ludifica\player($USER->id);

        $fs = get_file_storage();
        $dateformat = get_string('strftimedatetimeshort');
        foreach ($this->tickets as $key => $ticket) {

            if (!$hasmanage && !$ticket->enabled) {
                unset($this->tickets[$key]);
                continue;
            }

            $ticket->usertickets = $DB->count_records('block_ludifica_usertickets', array('userid' => $USER->id,
                                                                               'ticketid' => $ticket->id));

            $ticket->availabledateformated = !empty($ticket->availabledate) ? userdate($ticket->availabledate,$dateformat) :
                                                                                get_string('unlimited', 'block_ludifica');
            $ticket->enabled = (empty($ticket->availabledate) || $ticket->availabledate > time()) && $ticket->available > 0;

            $compliance = \block_ludifica\controller::requirements_compliance($USER->id, $ticket);
            $compliancecaptions = \block_ludifica\controller::requirements_text($USER->id, $ticket);

            $ticket->hascompliancecaptions = count($compliancecaptions) > 0;
            $ticket->compliancecaptions = $compliancecaptions;
            $ticket->compliancecaption = implode(',', $compliancecaptions);

            $ticket->enabled = $ticket->enabled && $compliance;

            if (!$ticket->enabled) {
                $ticket->notenabledtext = !$compliance ?
                                                get_string('notcompliance', 'block_ludifica') :
                                                $ticket->available <= 0 ?
                                                    get_string('notavailable', 'block_ludifica') :
                                                    get_string('notavailabledate', 'block_ludifica');
            } else {
                if ($ticket->usertickets >= $ticket->byuser) {
                    $ticket->notenabledtext = get_string('maxtickets', 'block_ludifica');
                    $ticket->enabled = false;
                } else if ($player->general->coins < $ticket->cost) {
                    $ticket->notenabledtext = get_string('notcostcompliance', 'block_ludifica');
                    $ticket->enabled = false;
                }
            }

            $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'ticket', $ticket->id);
            foreach ($files as $file) {
                $filename = $file->get_filename();

                if (!empty($filename) && $filename != '.') {
                    $path = '/' . implode('/', array($file->get_contextid(),
                                                        'block_ludifica',
                                                        'ticket',
                                                        $file->get_itemid() . $file->get_filepath() . $filename));

                    $ticket->thumbnail = \moodle_url::make_file_url('/pluginfile.php', $path);

                    // Only one image by ticket.
                    break;
                }
            }
        }

        $defaultvariables = [
            'tickets' => array_values($this->tickets),
            'baseurl' => $CFG->wwwroot,
            'canedit' => $hasmanage,
            'sesskey' => sesskey()
        ];

        return $defaultvariables;
    }
}
