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
 * Class to manage the player information.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;
defined('MOODLE_INTERNAL') || die();


/**
 * Player info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class player {

    const DEFAULT_AVATAR = null;

    /**
     * var \stdClass Info about the player.
     */
    private $data;

    /**
     * Class constructor.
     */
    public function __construct($userid = null) {
        global $USER, $DB;

        if (!$userid) {
            $this->data = $USER;
        } else {
            $this->data = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
        }

        $general = $DB->get_record('block_ludifica_general', array('userid' => $this->data->id));

        if (!$general) {
            $general = new \stdClass();
            $general->userid = $this->data->id;
            $general->points = 0;
            $general->coins = 0;
            $general->avatarid = self::DEFAULT_AVATAR;
            $general->nickname = '';
            $general->timeupdated = time();
            $general->id = $DB->insert_record('block_ludifica_general', $general, true);
        }

        $this->data->general = $general;

        if ($this->data->general->avatarid) {
            $this->data->avatar = $DB->get_record('block_ludifica_avatars',
                                                    array('id' => $this->data->general->avatarid), '*', MUST_EXIST);
        } else {
            $this->data->avatar = null;
        }
    }

    public function get_profile() {
        global $OUTPUT;

        $info = new \stdClass();
        $info->fullname = fullname($this->data);
        $info->points = $this->data->general->points;
        $info->coins = $this->data->general->coins;
        $info->level = controller::calc_level($this->data->general->points);
        $info->avatar = $this->data->avatar;
        $info->nickname = $this->get_nickname();

        if ($this->data->avatar) {
            $avatar = new avatar($this->data->avatar);
            $info->avatarurl = $avatar->get_uri($info->level->index);
        } else {
            $info->avatarurl = avatar::default_avatar();
        }

        return $info;
    }

    public function get_tickets() {
        global $OUTPUT, $DB, $USER;

        $usertickets = $DB->get_records('block_ludifica_usertickets', array('userid' => $USER->id));

        $response = array();
        foreach ($usertickets as $one) {

            if (!isset($response[$one->ticketid])) {
                $ticket = new ticket($one->ticketid);
                $info = new \stdClass();
                $info->thumbnail = $ticket->get_thumbnail();
                $info->name = $ticket->name;
                $info->count = 1;
                $response[$one->ticketid] = $info;
            } else {
                $info = $response[$one->ticketid];
                $info->count++;
            }
        }

        return $response;
    }

    public function get_nickname() {
        global $USER;

        if(!empty($this->data->general->nickname)) {
            $nickname = $this->data->general->nickname;
        } else {
            $nickname = fullname($USER);
        }

        return $nickname;
    }

    public function __get($name) {
        if (property_exists($this, $name)){
            return $this->$name;
        } else if (property_exists($this->data, $name)){
            return $this->data->$name;
        } else if(method_exists($this, 'get_' . $name)) {
            return call_user_func(array($this, 'get_' . $name));
        } else {
            throw new \Exception('propertie_or_method_not_found: ' . get_class($this) . '->'. $name);
        }
    }

    public function __set($name, $value) {
        if (property_exists($this, $name)){
            $this->$name = $value;
        } else if (property_exists($this->data, $name)){
            $this->data->$name = $value;
        } else if(method_exists($this, 'set_' . $name)) {
            return call_user_func(array($this, 'set_' . $name), $value);
        } else {
            throw new \Exception('propertie_or_method_not_found: ' . get_class($this) . '->'. $name);
        }
    }
}