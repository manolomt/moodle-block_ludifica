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
 * Form for editing ludifica block instances.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_ludifica extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_ludifica');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newblocktitle', 'block_ludifica');
        }
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $DB, $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';

        $tabs = array();

        // Profile tab is printed by default if not exists the configuration parameter.
        if (!property_exists($this->config, 'tabprofile') || $this->config->tabprofile) {
            $tabs[] = 'profile';
        }

        if ($this->page->course->id != SITEID && property_exists($this->config, 'tabtopbycourse') && $this->config->tabtopbycourse) {
            $tabs[] = 'topbycourse';
        }

        if (property_exists($this->config, 'tabtopbysite') && $this->config->tabtopbysite) {
            $tabs[] = 'topbysite';
        }

        if ($this->page->course->id != SITEID && property_exists($this->config, 'tablastmonth') && $this->config->tablastmonth) {
            $tabs[] = 'lastmonth';
        }

        $html = '';

        // Check we are not trying to load guest's player profile.
        if (isguestuser($USER)) {
            // Can not view info of guest - thre is nothing to see there.
            $html = '';
        } else if (count($tabs) == 0) {
            // Not tabs selected to print.
            $html = '';
        } else {

            $info = new \block_ludifica\player();

            // Load templates to display the block content.
            $renderable = new \block_ludifica\output\main($tabs, $info);
            $renderer = $this->page->get_renderer('block_ludifica');
            $html .= $renderer->render($renderable);
            $this->page->requires->js_call_amd('block_ludifica/main', 'init');

        }

        $this->content->text = $html;

        return $this->content;
    }

    public function instance_can_be_docked() {
        return false;
    }

}
