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
 * Block renderer
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica\output;

use plugin_renderer_base;
use renderable;

/**
 * ludifica block renderer
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the template content for the block.
     *
     * @param main $main The main renderable
     * @return string HTML string
     */
    public function render_main(main $main) {
        global $CFG;

        $template = get_config('block_ludifica', 'templatetype');
        $path = $CFG->dirroot . '/blocks/ludifica/templates/' . $template . '/main.mustache';

        if ($template != 'default' && file_exists($path)) {
            $templatefile = 'block_ludifica/' . $template . '/main';
        } else {
            $templatefile = 'block_ludifica/main';
        }

        return $this->render_from_template($templatefile, $main->export_for_template($this));
    }

    /**
     * Return the template content for the block.
     *
     * @param avatars $avatars The avatars renderable
     * @return string HTML string
     */
    public function render_avatars(avatars $avatars) {
        global $CFG;

        $template = get_config('block_ludifica', 'templatetype');
        $path = $CFG->dirroot . '/blocks/ludifica/templates/' . $template . '/avatars.mustache';

        if ($template != 'default' && file_exists($path)) {
            $templatefile = 'block_ludifica/' . $template . '/avatars';
        } else {
            $templatefile = 'block_ludifica/avatars';
        }

        return $this->render_from_template($templatefile, $avatars->export_for_template($this));
    }

    /**
     * Return the template content for the block.
     *
     * @param badges $badges The badges renderable
     * @return string HTML string
     */
    public function render_badges(badges $badges) {
        global $CFG;

        $template = get_config('block_ludifica', 'templatetype');
        $path = $CFG->dirroot . '/blocks/ludifica/templates/' . $template . '/badges.mustache';

        if ($template != 'default' && file_exists($path)) {
            $templatefile = 'block_ludifica/' . $template . '/badges';
        } else {
            $templatefile = 'block_ludifica/badges';
        }

        return $this->render_from_template($templatefile, $badges->export_for_template($this));
    }

    /**
     * Return the template content for the block.
     *
     * @param tickets $tickets The tickets renderable
     * @return string HTML string
     */
    public function render_tickets(tickets $tickets) {
        global $CFG;

        $template = get_config('block_ludifica', 'templatetype');
        $path = $CFG->dirroot . '/blocks/ludifica/templates/' . $template . '/tickets.mustache';

        if ($template != 'default' && file_exists($path)) {
            $templatefile = 'block_ludifica/' . $template . '/tickets';
        } else {
            $templatefile = 'block_ludifica/tickets';
        }

        return $this->render_from_template($templatefile, $tickets->export_for_template($this));
    }
}
