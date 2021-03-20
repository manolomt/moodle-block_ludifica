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

//        $amount = get_config('block_ludifica', 'singleamount');

        $html = '';

        // Check we are not trying to load guest's player profile.
        if (isguestuser($USER)) {
            // Can not view info of guest - thre is nothing to see there.
            $html = '';
        } else {

            $info = new \block_ludifica\player();

            // Load templates to display the block content.
            $renderable = new \block_ludifica\output\main($info);
            $renderer = $this->page->get_renderer('block_ludifica');
            $html .= $renderer->render($renderable);

        }

        $this->content->text = $html;

        return $this->content;
    }

    public function instance_can_be_docked() {
        return false;
    }

}
