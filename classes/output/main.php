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

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {

    /**
     * @var \block_ludifica\player Info about the player.
     */
    private $player;

    /**
     * @var array List of tabs to print.
     */
    private $tabs;

    /**
     * Constructor.
     *
     * @param array $tabs Tabs list to render.
     * @param \block_ludifica\player $player The player user information.
     */
    public function __construct($tabs, $player) {

        $this->player = $player;
        $this->tabs = $tabs;

    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $COURSE, $USER, $OUTPUT;

        $icons = array('profile' => 'address-card',
                        'topbycourse' => 'sort-amount-desc',
                        'topbysite' => 'trophy',
                        'lastmonth' => 'calendar-check-o',
                    );

        $showtabs = array();
        foreach ($this->tabs as $k => $tab) {
            $one = new \stdClass();
            $one->title = get_string('tabtitle_' . $tab, 'block_ludifica');
            $one->key = $tab;
            $one->icon = $icons[$tab];
            $one->state = $k == 0 ? 'active' : '';
            $showtabs[] = $one;
        }

        $activetab = false;

        $uniqueid = \block_ludifica\controller::get_uniqueid();

        $hasranking = false;

        if (in_array('topbycourse', $this->tabs) ||
            in_array('topbysite', $this->tabs) ||
            in_array('lastmonth', $this->tabs)) {
            
            $hasranking = true;
        }

        $defaultvariables = [
            'uniqueid' => $uniqueid,
            'hastabs' => count($this->tabs) > 1,
            'tabs' => $showtabs,
            'baseurl' => $CFG->wwwroot,
            'layoutgeneral' => true,
            'hasranking' => $hasranking
        ];

        if (in_array('profile', $this->tabs)) {

            $nickname = $this->player->get_nickname();
            $ownprofile = $this->player->general->userid == $USER->id;

            if ($ownprofile) {
                $tmpl = new \core\output\inplace_editable('block_ludifica', 'nickname', $this->player->general->id,
                    has_capability('moodle/user:editownprofile', \context_system::instance()),
                    format_string($nickname), $nickname, get_string('editnickname', 'block_ludifica'),
                    get_string('newnickname', 'block_ludifica', format_string($nickname)));
                    $nickcontent = $OUTPUT->render($tmpl);
                    $defaultvariables['tickets'] = array_values($this->player->get_tickets());
            } else {
                $nickcontent = $nickname;
            }

            $defaultvariables['nickcontent'] = $nickcontent;
            $defaultvariables['player'] = $this->player->get_profile();
            $defaultvariables['profilestate'] = 'active';
            $defaultvariables['ownprofile'] = $ownprofile;
            $activetab = true;
        }

        if (in_array('topbycourse', $this->tabs)) {
            $defaultvariables['hastopbycourse'] = true;
            $defaultvariables['topbycourse'] = array_values(\block_ludifica\controller::get_topbycourse($COURSE->id));
            $defaultvariables['hasrowstopbycourse'] = count($defaultvariables['topbycourse']) > 0;
            $defaultvariables['topbycoursestate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        if (in_array('topbysite', $this->tabs)) {
            $defaultvariables['hastopbysite'] = true;
            $defaultvariables['topbysite'] = array_values(\block_ludifica\controller::get_topbysite());
            $defaultvariables['hasrowstopbysite'] = count($defaultvariables['topbysite']) > 0;
            $defaultvariables['topbysitestate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        if (in_array('lastmonth', $this->tabs)) {
            $defaultvariables['haslastmonth'] = true;
            $defaultvariables['lastmonth'] = array_values(\block_ludifica\controller::get_lastmonth($COURSE->id));
            $defaultvariables['hasrowslastmonth'] = count($defaultvariables['lastmonth']) > 0;
            $defaultvariables['lastmonthstate'] = !$activetab ? 'active' : '';
            $activetab = true;
        }

        return $defaultvariables;
    }
}
