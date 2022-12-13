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
 * Class base to manage requirements.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\requirements;

/**
 * Requirement base.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class requirementbase {

    /**
     * @var \stdClass Complement options.
     */
    protected $options;

    /**
     * @var array Default properties list, key => value format.
     */
    protected $defaultoptions = [];

    /**
     * Class constructor.
     *
     * @param object|array $options Requeriment properties.
     */
    public function __construct($options = null) {

        if (!is_object($options)) {
            $options = (object)$options;
        }

        foreach ($this->defaultoptions as $option => $value) {
            if (!property_exists($options, $option)) {
                $options->$option = $value;
            }
        }

        $this->options = $options;
    }

    /**
     * It define if a player compliance the requirement.
     *
     * @param \block_ludifica\player $player
     * @return bool True in compliance, false in other case.
     */
    public function compliance(\block_ludifica\player $player) {
        return true;
    }

    /**
     * Compliance text to user.
     *
     * @return string Compliance caption.
     */
    public function caption() {
        return '';
    }
}
