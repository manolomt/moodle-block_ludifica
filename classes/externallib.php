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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * External integration API
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
//require_once($CFG->dirroot . '/blocks/ludifica/locallib.php');


/**
 * External WS lib.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends \external_api {

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function get_ticket_parameters() {
        return new \external_function_parameters(
            array(
                'id' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED)
            )
        );
    }

    public static function get_ticket($id) {
        global $DB, $USER;

        $ticket = $DB->get_record('block_ludifica_ticket', array('id' => $id), '*', MUST_EXIST);

        if (isloggedin() && !isguestuser()) {
            $ticket->usertickets = $DB->get_records('block_ludifica_usertickets', array('userid' => $USER->id,
                                                                                        'ticketid' => $id));

            // Hack for external presentation.
            $dateformat = get_string('strftimedatetimeshort');
            foreach($ticket->usertickets as $uticket) {
                $uticket->timeusedformatted = $uticket->timeused ? userdate($uticket->timeused, $dateformat) : null;
            }
        } else {
            $ticket->usertickets = [];
        }

        return $ticket;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function get_ticket_returns() {
        return new \external_single_structure(
            array(
                'id' => new \external_value(PARAM_INT, 'Ticket id'),
                'name' => new \external_value(PARAM_TEXT, 'Ticket name'),
                'description' => new \external_value(PARAM_TEXT, 'Ticket description'),
                'moreinfo' => new \external_value(PARAM_RAW, 'Aditional info'),
                'type' => new \external_value(PARAM_TEXT, 'Ticket type'),
                'cost' => new \external_value(PARAM_INT, 'Ticket cost'),
                'availabledate' => new \external_value(PARAM_INT, 'End time to buy the ticket'),
                'available' => new \external_value(PARAM_INT, 'Total available tickets'),
                'byuser' => new \external_value(PARAM_INT, 'User available tickets'),
                'timecreated' => new \external_value(PARAM_INT, 'Ticket created time'),
                'usertickets' => new \external_multiple_structure(
                    new \external_single_structure(
                        array(
                            'id' => new \external_value(PARAM_INT, 'User ticket id'),
                            'userid' => new \external_value(PARAM_INT, 'User id'),
                            'usercode' => new \external_value(PARAM_TEXT, 'Unique user ticket code'),
                            'timecreated' => new \external_value(PARAM_INT, 'Time created'),
                            'timeused' => new \external_value(PARAM_INT, 'Time used, null if not used yet', VALUE_DEFAULT, null),
                            'timeusedformatted' => new \external_value(PARAM_TEXT, 'Time used formatted', VALUE_DEFAULT, null),
                        ),
                        'An user to access the resource'),
                    'User access list', VALUE_DEFAULT, array()
                )
            ),
            'A ticket info', VALUE_DEFAULT, null
        );
    }

    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function buy_ticket_parameters() {
        return new \external_function_parameters(
            array(
                'id' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED)
            )
        );
    }

    public static function buy_ticket($id) {
        global $DB, $USER;

        $ticket = $DB->get_record('block_ludifica_ticket', array('id' => $id), '*', MUST_EXIST);
        $usertickets = $DB->count_records('block_ludifica_usertickets', array('userid' => $USER->id,
                                                                                        'ticketid' => $id));

        $player = new \block_ludifica\player($USER->id);

        if ($ticket->enabled && (empty($ticket->availabledate) || $ticket->availabledate > time()) && $ticket->available > 0 &&
                $usertickets < $ticket->byuser && $player->general->coins >= $ticket->cost) {

            $usercode = $ticket->code;

            if (empty($usercode)) {
                $usercode = controller::generate_code();
            }

            $ticket->available--;
            $DB->update_record('block_ludifica_ticket', array('id' => $id, 'available' => $ticket->available));

            $data = new \stdClass();
            $data->userid = $USER->id;
            $data->ticketid = $id;
            $data->infodata = '{}';
            $data->usercode = $usercode;
            $data->timecreated = time();
            $data->timeused = null;
            $DB->insert_record('block_ludifica_usertickets', $data);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function buy_ticket_returns() {
        return new \external_value(PARAM_BOOL, 'True if ticket was bought');
    }


    /**
     * To validade input parameters
     * @return \external_function_parameters
     */
    public static function give_ticket_parameters() {
        return new \external_function_parameters(
            array(
                'ticketid' => new \external_value(PARAM_INT, 'Ticket id', VALUE_REQUIRED),
                'contactid' => new \external_value(PARAM_INT, 'Contact id', VALUE_REQUIRED)
            )
        );
    }

    public static function give_ticket($ticketid, $contactid) {
        global $DB, $USER;

        $usertickets = $DB->get_records('block_ludifica_usertickets',
                                            array('userid' => $USER->id, 'ticketid' => $ticketid, 'timeused' => null),
                                            '', '*', 0, 1);

        if (count($usertickets) > 0) {

            $ticket = reset($usertickets);
            $ticket->userid = $contactid;
            $DB->update_record('block_ludifica_usertickets', $ticket);

            return true;
        }

        return false;
    }

    /**
     * Validate the return value
     * @return \external_single_structure
     */
    public static function give_ticket_returns() {
        return new \external_value(PARAM_BOOL, 'True if ticket was given');
    }



}
