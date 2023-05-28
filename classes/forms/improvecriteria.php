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
 * Class containing form definition to manage an improve criteria.
 *
 * @package   block_ludifica
 * @copyright 2023 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica\forms;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

use moodleform;

/**
 * The form for handling editing an improve criteria.
 *
 * @copyright 2023 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class improvecriteria extends moodleform {

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
        $improvecriteria = $this->_data->type;

        // Get available criteria to improve.
        $availablecriteria = \block_ludifica\controller::badges_improvecriteria();

        $criterialist = [];
        foreach ($availablecriteria as $criteria) {
            $criterialist[$criteria->key()] = $criteria->title();
        }

        // We wait at least one criteria to improve :s.
        if (!$improvecriteria) {
            $improvecriteria = array_keys($criterialist)[0];
        }

        $mform->addElement('select', 'type', get_string('improvecriteria', 'block_ludifica'), $criterialist);
        // First criteria is the default.
        $mform->setDefault('type', $improvecriteria);

        if (count($criterialist) > 1) {
            // Button to update improvecriteria options on change.
            // ToDo: will be hidden and trigger by JavaScript.
            $mform->registerNoSubmitButton('updateimprovecriteria');
            $mform->addElement('submit', 'updateimprovecriteria', get_string('improvecriteriaudpate', 'block_ludifica'));
        }

        // Get the current improvecriteria options.
        $availablecriteria[$improvecriteria]->settings($mform, $this->_data);

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'badgeid', null);
        $mform->setType('badgeid', PARAM_INT);

        $this->add_action_buttons();

        // Finally set the current form data.
        $this->set_data($this->_data);
    }

}
