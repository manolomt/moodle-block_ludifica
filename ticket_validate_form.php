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
 * Class containing form definition for ticket validation.
 *
 * @package   block_ludifica
 * @copyright 2022 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * The form for validate a ticket.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica_ticket_validate extends moodleform {

    /**
     * @var object List of local data.
     */
    protected $_data;

    /**
     * Form definition.
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // This contains the data of this form.
        $this->_data = $this->_customdata['data'];

        $mform->addElement('header', 'general', get_string('searchticket', 'block_ludifica'));

        $mform->addElement('text', 'username', get_string('username'));
        $mform->addRule('username', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');
        $mform->setType('username', PARAM_TEXT);

        $mform->addElement('text', 'usercode', get_string('usercode', 'block_ludifica'));
        $mform->addRule('usercode', get_string('missingfield', 'block_ludifica'), 'required', null, 'client');
        $mform->setType('usercode', PARAM_TEXT);

        $mform->addElement('submit', 'search', get_string('searchticket', 'block_ludifica'));

        // Finally set the current form data.
        $this->set_data($this->_data);
    }

}
