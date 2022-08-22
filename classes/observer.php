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
 * Event observer.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ludifica;
use stdclass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/lib/accesslib.php');
/**
 * Events observer.
 *
 * Manage all events related to points and others block elements.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Calculate points when a user complete a course.
     *
     * @param \core\event\base $event
     */
    public static function course_completed(\core\event\base $event) {

           controller::points_completecourse($event->relateduserid, $event->courseid, $event->objectid);

    }

    /**
     * Calculate points when a user logged in.
     *
     * @param \core\event\base $event
     */
    public static function user_loggedin(\core\event\base $event) {

           controller::points_recurrentlogin($event->userid);

    }

    /**
     * Calculate points when a new user is created.
     *
     * @param \core\event\base $event
     */
    public static function user_created(\core\event\base $event) {

           controller::points_usercreated($event->relateduserid);

    }

    /**
     * Calculate points when a user answers an embed question.
     *
     * @param \core\event\base $event
     */
    public static function question_attempted(\filter_embedquestion\event\question_attempted $event) {

           controller::points_embedquestion($event->userid, $event->courseid, $event->contextid, $event->objectid);

    }
    
    /**
     * Calculate points when changes his profile with a valid email.
     *
     * @param \core\event\base $event
     */
    public static function user_updated(\core\event\user_updated $event) {
    
	   controller::points_userupdated($event->userid, $event->relateduserid);

    }

    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {

	    global $DB;

	    $eventdata = $event->get_record_snapshot('course_modules_completion', $event->objectid);

	    $context = \context_course::instance($event->courseid);

            if ($eventdata->completionstate == COMPLETION_COMPLETE || $eventdata->completionstate == COMPLETION_COMPLETE_PASS) {
                controller::points_completemodule($event->userid, $event->relateduserid, $event->courseid, $event->objectid);
	    }
    }
}
