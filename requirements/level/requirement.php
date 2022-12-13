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
 * Class to manage the level requirements.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\requirements;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/ludifica/requirements/requirementbase.php');

/**
 * Level requirement.
 *
 * Configuration example:
 * {
 *  "requirements" : [
 *      {
 *          "type" : "level",
 *          "options" : { "min" : 1 }
 *      }
 *  ]
 * }
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class level extends requirementbase {

    /**
     * @var array Default properties list, key => value format.
     */
    protected $defaultoptions = ['min' => 0];

    /**
     * Check if player meets the requirement.
     *
     * @param object $player
     * @return bool True in compliance, false in other case.
     */
    public function compliance($player) {
        $level = \block_ludifica\controller::calc_level($player->general->points);

        return $level->index >= $this->options->min;
    }

    /**
     * Compliance text to user.
     *
     * @return string Compliance caption.
     */
    public function caption() {
        $levels = \block_ludifica\controller::get_levels();
        return get_string('levelrequired', 'block_ludifica', $levels[$this->options->min]->name);
    }

}
