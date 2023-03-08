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

require_once($CFG->dirroot . '/lib/badgeslib.php');

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badges implements renderable, templatable {

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

        $uniqueid = \block_ludifica\controller::get_uniqueid();

        // Get user badges only in profile tab.

        $allbadges = badges_get_badges(BADGE_TYPE_SITE);
        $userbadges = badges_get_user_badges($player->general->userid, null);
        $badges = [];

        foreach ($userbadges as $badge) {

            if ($badge->status == '3') {

                // Equal symbol encode so it can work in LinkedIn URL.
                $badge->url = (string)(new \moodle_url('/badges/badge.php', ['hash' => $badge->uniquehash]));
                $badge->expire = date('F Y', $badge->dateexpire);
                $badge->year = date('Y', $badge->timecreated);
                $badge->month = date('m', $badge->timecreated);
                $badge->thumbnail = \moodle_url::make_pluginfile_url(SITEID, 'badges', 'badgeimage', $badge->id, '/', 'f3', false);
                $badges[] = $badge;
            }
        }
        // End Get user badges.

        // Get unavialable badges.
        foreach ($allbadges as $badge) {
            if ($badge->status == '1') {
                
                $badge->thumbnail = \moodle_url::make_pluginfile_url(SITEID, 'badges', 'badgeimage', $badge->id, '/', 'f3', false);
                $badge->unavailable = 'unavailable';
                $badge->unavailablewarning = get_string('unavailablewarning', 'block_ludifica');
                $badges[] = $badge;
            }
        }

        $defaultvariables = [
            'uniqueid' => $uniqueid,
            'baseurl' => $CFG->wwwroot,
            'canedit' => $hasmanage,
            'storetabs' => \block_ludifica\controller::get_storetabs('badges'),
            'sesskey' => sesskey(),
            'player' => $player->get_profile(),
            'layoutbadges' => true,
            'badges' => $badges
        ];

        return $defaultvariables;
    }
}
