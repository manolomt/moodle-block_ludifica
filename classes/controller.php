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
 * Class containing the general controls.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;
defined('MOODLE_INTERNAL') || die();

/**
 * Component controller.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controller {

    /**
     * var string Points when a course is completed by a user.
     */
    const POINTS_TYPE_COURSECOMPLETED = 'coursecompleted';

    /**
     * var string Points by recurrent login.
     */
    const POINTS_TYPE_RECURRENTLOGIN = 'recurrentlogin';

    /**
     * var string Points by new user.
     */
    const POINTS_TYPE_USERCREATED = 'usercreated';

    /**
     * var string Points by change email.
     */
    const POINTS_TYPE_EMAILCHANGED = 'emailchanged';

    /**
     * var string Points by embed_question.
     */
    const POINTS_TYPE_EMBEDQUESTION = 'embedquestion';
    
    /**
     * var string Points when a course is completed by a user.
     */
    const POINTS_TYPE_MODULECOMPLETED = 'modulecompleted';

    /**
     * var string Points by recurrent login.
     */
    const COINS_TYPE_BYPOINTS = 'bypoints';

    /**
     * var int Ranking users.
     */
    const LIMIT_RANKING = 10;

    /**
     * var int Instances includes in page request.
     */
    private static $instancescounter = 0;


    private static $LEVELS = null;

    /**
     * Add points to player profile when complete a course.
     */
    public static function points_completecourse($userid, $courseid, $completionid = null) {
        global $DB;

        $conditions = [
            'userid' => $userid,
            'courseid' => $courseid,
            'type' => controller::POINTS_TYPE_COURSECOMPLETED
        ];

        $record = $DB->get_record('block_ludifica_userpoints', $conditions);

        // If exists not add more points.
        if ($record) {
            return false;
        }

	$durationcheck = get_config('block_ludifica', 'durationcheck');
        $coursedurationfield = get_config('block_ludifica', 'duration');
        $pointsbycourse = get_config('block_ludifica', 'pointsbyendcourse');

        $check_this_course = FALSE;

	$allcourses = get_config('block_ludifica', 'pointsbyendcourse_all');

	if($allcourses)
	   $check_this_course = TRUE;
	else {
	     $course_list = get_config('block_ludifica', 'pointsbyendcourse_ids');
             $course_category = get_config('block_ludifica', 'pointsbyendcourse_category');	
             if(empty($course_list) AND empty($course_category))
	        return false;
	     
	     if(!empty($course_list)) {
		$courses_array = array();
		$course_list_without_spaces = str_replace(' ', '', $courselist);
		$course_list_as_array = explode(',', $course_list_without_spaces);
		foreach($course_list_as_array as $course_item)
			$courses_array[] = (int)$course_item;

                if(in_array($courseid, $courses_array))
                  $check_this_course = TRUE;
	     }

	     if(!empty($course_category)) {
		$course_category_from_record = $DB->get_field('course', 'category', array('id' => $courseid));
		if($course_category_from_record == $course_category)
		   $check_this_course = TRUE;
		else {
		      $categoryids_array = array();
		      $subcategories_query = "SELECT id FROM {course_categories} WHERE visible = 1 AND path LIKE '/$course_category/%'";
                      $subcategories_records = $DB->get_records_sql($subcategories_query);
                      foreach($tenant_subcategories_records as $key => $subcategory_id) {
                              $categoryids_array[$key] = $subcategory_id->id;
                      }
                      if(in_array($courseid, $categoryids_array))
			 $check_this_course = true;
		}
	     }
	}

        if ( (empty($coursedurationfield) AND $durationcheck) || empty($pointsbycourse) || !$check_this_course) {
            return false;
	}

	if(!empty($coursedurationfield) AND $durationcheck)
           $duration = $DB->get_field('customfield_data', 'value', ['fieldid' => $coursedurationfield, 'instanceid' => $courseid]);
	else
	    $duration = 1;

        $pointsbycomplete = $pointsbycourse * $duration;

        $player = new player($userid);
        $totalpoints = $pointsbycomplete + $player->general->points;

        // We need put coins before points because current points are used to calc the coins earned.
        self::coinsbypoints($userid, $courseid, $pointsbycomplete);

        // Save the global/total points.
        $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                      'points' => $totalpoints,
                                                      'timeupdated' => time()]);

        // Save specific course points.
        $infodata = new \stdClass();
        $infodata->completionid = $completionid;

        $data = new \stdClass();
        $data->courseid = $courseid;
	$data->userid = $userid;
	$data->objectid = $completionid;
        $data->type = self::POINTS_TYPE_COURSECOMPLETED;
        $data->points = $pointsbycomplete;
        $data->infodata = json_encode($infodata);
        $data->timecreated = time();

        $DB->insert_record('block_ludifica_userpoints', $data);

        return true;
    }

    /**
     * Add points to user according her recurrent login.
     */
    public static function points_recurrentlogin($userid) {
        global $DB;

        if (isguestuser()) {
            // No points to guest user.
            return;
        }

        $conflogindays = get_config('block_ludifica', 'recurrentlogindays');

        if (empty($conflogindays)) {
            return false;
        }

        $conditions = [
            'userid' => $userid,
            'type' => controller::POINTS_TYPE_RECURRENTLOGIN
        ];

        // Only get the newest.
        $userpoints = $DB->get_records('block_ludifica_userpoints', $conditions, 'timecreated DESC', '*', 1, 1);

        $recurrentdays = 0;
        if (count($userpoints) > 0) {
            $userpoints = reset($userpoints);
            $userpoints->infodata = json_decode($userpoints->infodata);

            $todaytime = strtotime("today");

            if (property_exists($userpoints->infodata, 'lastday')) {

                // Current day processed previously.
                if ($userpoints->infodata->lastday == date('Y-m-d')) {
                    return false;
                }

                // Restart counter if not logged in in the last day.
                $recurrentdays = strtotime($userpoints->infodata->lastday) + (24 * 60 * 60) >= strtotime("today") ?
                                    $userpoints->infodata->days : 0;
            }
        } else {
            $infodata = new \stdClass();
            $infodata->lastday = date('Y-m-d');
            $infodata->days = 0;
            $infodata->steps = 0;

            $userpoints = new \stdClass();
            $userpoints->courseid = SITEID;
            $userpoints->userid = $userid;
            $userpoints->type = controller::POINTS_TYPE_RECURRENTLOGIN;
            $userpoints->points = 0;
            $userpoints->timecreated = time();
            $userpoints->infodata = json_encode($infodata);

            $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
            $userpoints->infodata = $infodata;
        }

        $points = 0;
        // Not the minimum required days yet.
        if ($conflogindays <= $recurrentdays + 1) {
            if ($conflogindays == $recurrentdays + 1) {
                $points = get_config('block_ludifica', 'pointsbyrecurrentlogin1');
                $userpoints->infodata->steps++;
            } else {
                $points = get_config('block_ludifica', 'pointsbyrecurrentlogin2');
            }

            $player = new player($userid);
            $totalpoints = $points + $player->general->points;

            // We need put coins before points because current points are used to calc the coins earned.
            self::coinsbypoints($userid, SITEID, $points);

            // Save the global/total points.
            $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                          'points' => $totalpoints,
                                                          'timeupdated' => time()]);
        }

        $userpoints->infodata->lastday = date('Y-m-d');
        $userpoints->infodata->days = $recurrentdays + 1;

        $DB->update_record('block_ludifica_userpoints', ['id' => $userpoints->id,
                                                         'points' => $userpoints->points + $points,
                                                         'infodata' => json_encode($userpoints->infodata)]);
        return true;
    }

    /**
     * Add points to a new user.
     */
    public static function points_usercreated($userid) {
	   global $DB;

            $points = get_config('block_ludifica', 'pointsbynewuser');
     
            // Save specific course points.
            $infodata = new \stdClass();
            $infodata->userid = $userid;

            $userpoints = new \stdClass();
            $userpoints->courseid = SITEID;
	    $userpoints->userid = $userid;
	    $userpoints->objectid = NULL;
            $userpoints->type = controller::POINTS_TYPE_USERCREATED;
            $userpoints->points = $points;
            $userpoints->timecreated = time();
            $userpoints->infodata = json_encode($infodata);

            $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
	    $userpoints->infodata = $infodata;

            $player = new player($userid);
            $totalpoints = $points + $player->general->points;

            // We need put coins before points because current points are used to calc the coins earned.
            self::coinsbypoints($userid, SITEID, $points);

            // Save the global/total points.
            $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                          'points' => $totalpoints,
                                                          'timeupdated' => time()]);
	    return true;
    }

    public static function points_userupdated($userid, $relateduserid) {
	    global $DB, $USER;

            if($USER->id != $relateduserid)
	       return false;

	    $points = get_config('block_ludifica', 'pointsbychangemail');
            $pattern = get_config('block_ludifica', 'initialemailpattern');

            if(empty($points) || empty($pattern))
	       return false;

            $conditions = [
            	'userid' => $userid,
                'courseid' => SITEID,
            	'type' => controller::POINTS_TYPE_EMAILCHANGED
            ];

            $record = $DB->get_record('block_ludifica_userpoints', $conditions);

            // If exists not add more points.
            if ($record) {
                return false;
            }

	    $user_email = $DB->get_field('user', 'email', array('id' => $userid));
	    
	    if(strpos($user_email, $pattern) !== false)
	       return false;	    

            // Save specific course points.
            $infodata = new \stdClass();
            $infodata->email = $user_email;

            $userpoints = new \stdClass();
            $userpoints->courseid = SITEID;
	    $userpoints->userid = $relateduserid;
	    $userpoints->objectid = NULL;
            $userpoints->type = controller::POINTS_TYPE_EMAILCHANGED;
            $userpoints->points = $points;
            $userpoints->timecreated = time();
            $userpoints->infodata = json_encode($infodata);

            $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
	    $userpoints->infodata = $infodata;

            $player = new player($relateduserid);
            $totalpoints = $points + $player->general->points;

            // We need put coins before points because current points are used to calc the coins earned.
            self::coinsbypoints($userid, SITEID, $points);

            // Save the global/total points.
            $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                          'points' => $totalpoints,
                                                          'timeupdated' => time()]);
	    return true;
    }

    /**
     * Add points when a user answers an embed question.
     */
    public static function points_embedquestion($userid, $courseid, $contextid, $objectid = null) {
           global $DB;

           $conditions = [
               'userid' => $userid,
	       'courseid' => $courseid,
	       'objectid' => $objectid, 
               'type' => controller::POINTS_TYPE_EMBEDQUESTION
           ];

           $record = $DB->get_record('block_ludifica_userpoints', $conditions);

           // If exists not add more points.
           if ($record) {
               return false;
	   }
	   
	       ///////////////////////////////////
	       //To Do: is there a better way to do this? 
	       $is_correct = false;
           $is_partial_correct = false;
               $question_idnumber = $DB->get_field('question','idnumber', array('id' => $objectid));
	       $question_usage_query = "SELECT questionusageid FROM {report_embedquestion_attempt} WHERE userid = $userid AND contextid = $contextid AND embedid LIKE '%/$question_idnumber'";
               $question_usage_result = $DB->get_record_sql($question_usage_query);
               $question_usage_id = $question_usage_result->questionusageid;

	       //first response only
	       $question_attempts_query = "SELECT MIN(id) as minid FROM {question_attempts} WHERE questionusageid = $question_usage_id AND responsesummary IS NOT NULL";
	       $question_attempts_result = $DB->get_record_sql($question_attempts_query);
	       $question_attempt_id = $question_attempts_result->minid;

	       if($DB->record_exists('question_attempt_steps', array('questionattemptid' => $question_attempt_id, 'state' => 'gradedright')))
	          $is_correct = true;

	       if($DB->record_exists('question_attempt_steps', array('questionattemptid' => $question_attempt_id, 'state' => 'gradedpartial')))
	          $is_partial_correct = true;

	       ///////////////////////////////////

           $points = -1;

           $all_embed_questions = get_config('block_ludifica', 'pointsbyembedquestion_all');
	       $partial_questions = get_config('block_ludifica', 'pointsbyembedquestion_partial');
           if($all_embed_questions) {
              if($is_correct || $is_partial_correct) 
	             $points = get_config('block_ludifica', 'pointsbyembedquestion');
              else 
                  $points = 0;
           }
	       else  {  
		          $question_list = get_config('block_ludifica', 'pointsbyembedquestion_ids');
		    
		          if(!empty($question_list)) {
                     $questions_array = array();
                     $question_list_without_spaces = str_replace(' ', '', $question_list);
                     $question_list_as_array = explode(',', $question_list_without_spaces);
                     foreach($question_list_as_array as $question_item)
                             $questions_array[] = $question_item;

		             if(in_array($question_idnumber, $questions_array)) {
			            if($is_correct || $is_partial_correct)  
                           $points = get_config('block_ludifica', 'pointsbyembedquestion');
                        else
                            $points = 0;
                     }
                  }
	        }

            if($points != -1) {
               // Save specific course points.
               $infodata = new \stdClass();
               $infodata->embedquestionid = $objectid;

               $userpoints = new \stdClass();
               $userpoints->courseid = $courseid;
	           $userpoints->userid = $userid;
	           $userpoints->objectid = $objectid;
               $userpoints->type = controller::POINTS_TYPE_EMBEDQUESTION;
               $userpoints->points = $points;
               $userpoints->timecreated = time();
               $userpoints->infodata = json_encode($infodata);

               $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
               $userpoints->infodata = $infodata;

               $player = new player($userid);
               $totalpoints = $points + $player->general->points;

               // We need put coins before points because current points are used to calc the coins earned.
               self::coinsbypoints($userid, SITEID, $points);

               // Save the global/total points.
               $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                             'points' => $totalpoints,
                                                             'timeupdated' => time()]);
            }
            return true;
    }

    /**
     * Add points when a user answers an embed question.
     */
    public static function points_completemodule($userid, $relateduserid, $courseid, $objectid = null) {
           global $DB;

           $conditions = [
                       'userid' => $userid,
		               'courseid' => $courseid,
		               'objectid' => $objectid, 
                       'type' => controller::POINTS_TYPE_MODULECOMPLETED
           ];

           $record = $DB->get_record('block_ludifica_userpoints', $conditions);

           // If exists not add more points.
           if ($record) 
               return false;

	       if($userid != $relateduserid)
               return false;

           $pointsbycoursemodule = get_config('block_ludifica', 'pointsbyendcoursemodule');
	       $allmodules = get_config('block_ludifica', 'pointsbyendcoursemodule_all');
	      
           $configdata = $DB->get_field('block_instances', 'configdata', array('blockname' => 'ludifica', 'parentcontextid' => 474));

           $points = -1;

	       if($allmodules == 0) {

	          $context = \context_course::instance($courseid);
              $contextid = (int)$context->id;
              $coursemoduleid = $DB->get_field('course_modules_completion', 'coursemoduleid', array('id' => $objectid));
                               
              $configdata = $DB->get_field('block_instances', 'configdata', array('blockname' => 'ludifica', 'parentcontextid' => $contextid));
              
	          if ($configdata = $DB->get_field('block_instances', 'configdata', array('blockname' => 'ludifica', 'parentcontextid' => $context->id))) {

                  $config = unserialize(base64_decode($configdata));  
                  
		          if (isset($config->{"points_module_".$coursemoduleid}) AND $config->{"points_module_".$coursemoduleid} > 0) {
		              $points = $config->{"points_module_".$coursemoduleid};
		          } 
	          }
	       }
           else {
                $points = $pointsbycoursemodule;
           }

           $record_debug = new \stdClass();
           $record_debug->name = 'pointsbymodule';
           $record_debug->value = $points;
           $DB->insert_record('debug', $record_debug);

           if($points != -1) {
               // Save specific course points.
               $infodata = new \stdClass();
               $infodata->moduleid = $objectid;

               $userpoints = new \stdClass();
               $userpoints->courseid = $courseid;
	           $userpoints->userid = $userid;
	           $userpoints->objectid = $objectid;
               $userpoints->type = controller::POINTS_TYPE_MODULECOMPLETED;
               $userpoints->points = $points;
               $userpoints->timecreated = time();
               $userpoints->infodata = json_encode($infodata);

               $userpoints->id = $DB->insert_record('block_ludifica_userpoints', $userpoints, true);
               $userpoints->infodata = $infodata;

               $player = new player($userid);
               $totalpoints = $points + $player->general->points;

               // We need put coins before points because current points are used to calc the coins earned.
               self::coinsbypoints($userid, SITEID, $points);

               // Save the global/total points.
               $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                             'points' => $totalpoints,
                                                             'timeupdated' => time()]);
           }

           return true;
    }	   

    /**
     * Add coins by new points.
     */
    public static function coinsbypoints($userid, $courseid, $newpoints) {
        global $DB;

        $coinsbypoints = get_config('block_ludifica', 'coinsbypoints');
        $pointstocoins = get_config('block_ludifica', 'pointstocoins');
        $coinsequalpoints = get_config('block_ludifica', 'coinsequalpoints_check');

        if ((empty($coinsbypoints) || empty($pointstocoins)) AND !$coinsbypoints) {
            return false;
        }

        if ($coinsequalpoints) 
            $equals = TRUE;
        else
            $equals = FALSE;

        $player = new player($userid);

        if (!$equals) {
            $newpoints += $player->general->points % $pointstocoins;
            $newcoins = floor($newpoints / $pointstocoins);
        }
        else {
             $newcoins = $newpoints;
        }
        $totalcoins = $newcoins + $player->general->coins;

        // Save the global/total points.
        $DB->update_record('block_ludifica_general', ['id' => $player->general->id,
                                                      'coins' => $totalcoins,
                                                      'timeupdated' => time()]);

        $infodata = new \stdClass();
        $infodata->points = $newpoints;

        $data = new \stdClass();
        $data->courseid = $courseid;
        $data->userid = $userid;
        $data->type = self::COINS_TYPE_BYPOINTS;
        $data->coins = $newcoins;
        $data->infodata = json_encode($infodata);
        $data->timecreated = time();
        $DB->insert_record('block_ludifica_usercoins', $data);

        return true;
    }

    public static function calc_level($points) {

        $levels = self::get_levels();

        foreach ($levels as $level) {
            if ($level->maxpoints === null || $points <= $level->maxpoints) {
                return $level;
            }
        }

        // Theoretically, this option should not be valid but it is left by control,
        // in case there is a problem in the configuration of the levels.
        $level = new \stdClass();
        $level->name = '';
        $level->maxpoints = 0;
        $level->index = 0;
        return $level;
    }

    public static function get_levels() {
        if (!self::$LEVELS) {
            self::$LEVELS = array();

            $levels = get_config('block_ludifica', 'levels');

            if (empty($levels)) {
                $level = new \stdClass();
                $level->maxpoints = null;
                $level->name = get_string('defaultlevel', 'block_ludifica');
                $level->index = 0;
                return [$level];
            }

            $lines = explode("\n", $levels);

            foreach ($lines as $key => $line) {
                $fields = explode('|', $line);

                $level = new \stdClass();
                $level->name = trim($fields[0]);
                $level->index = $key;

                if (count($fields) != 2) {
                    // If it is the last line is the maximum level. If not, it is not a valid line.
                    if ($key == (count($lines) - 1)) {
                        $level->maxpoints = null;
                    } else {
                        continue;
                    }
                } else {
                    $level->maxpoints = (int)$fields[1];
                }

                self::$LEVELS[] = $level;
            }

        }

        return self::$LEVELS;
    }

    /**
     * If a ticket is avalilable according the requirement compliance.
     */
    public static function requirements_compliance($userid, $ticket) {
        global $CFG;

        $infodata = is_string($ticket->infodata) ? json_decode($ticket->infodata) : $ticket->infodata;

        if (is_object($infodata) && property_exists($infodata, 'requirements') && is_array($infodata->requirements)) {
            $player = new player($userid);
            foreach ($infodata->requirements as $requirement) {
                if (property_exists($requirement, 'type')) {
                    $fullpath = $CFG->dirroot . '/blocks/ludifica/requirements/' . $requirement->type . '/requirement.php';
                    if (file_exists($fullpath)) {
                        include_once($fullpath);
                        $class = 'block_ludifica\\requirements\\' . $requirement->type;
                        $options = property_exists($requirement, 'options') ? $requirement->options : null;
                        $logic = new $class($options);

                        if (!$logic->compliance($player)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Text to user about the requirement compliance.
     */
    public static function requirements_text($userid, $ticket) {
        global $CFG;

        $captions = array();

        $infodata = is_string($ticket->infodata) ? json_decode($ticket->infodata) : $ticket->infodata;

        if (is_object($infodata) && property_exists($infodata, 'requirements') && is_array($infodata->requirements)) {
            $player = new player($userid);
            foreach ($infodata->requirements as $requirement) {
                if (property_exists($requirement, 'type')) {
                    $fullpath = $CFG->dirroot . '/blocks/ludifica/requirements/' . $requirement->type . '/requirement.php';
                    if (file_exists($fullpath)) {
                        include_once($fullpath);
                        $class = 'block_ludifica\\requirements\\' . $requirement->type;
                        $options = property_exists($requirement, 'options') ? $requirement->options : null;
                        $logic = new $class($options);

                        $captions[] = $logic->caption($player);
                    }
                }
            }
        }

        return $captions;
    }

    /**
     * To generate a random string with a specific length.
     *
     * @param int $len String length.
     * @return string Random string.
     */
    public static function generate_code($len = 10) {
        $word = array_merge(range('a', 'z'), range(0, 9));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    public static function get_topbycourse($courseid, $includecurrent = true) {
        global $DB;

        $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                " FROM {block_ludifica_userpoints} AS lu " .
                " INNER JOIN {block_ludifica_general} AS g ON g.userid = lu.userid" .
                " WHERE lu.courseid = :courseid" .
                " GROUP BY lu.userid, g.nickname" .
                " ORDER BY points DESC";
        $records = $DB->get_records_sql($sql, array('courseid' => $courseid));

        return self::get_toplist($records, $includecurrent);

    }

    public static function get_topbysite($includecurrent = true) {
        global $DB;

        $list = array();

        $sql = "SELECT lu.userid AS id, g.nickname, " . $DB->sql_ceil('SUM(lu.points)') . " AS points " .
                " FROM {block_ludifica_userpoints} AS lu " .
                " INNER JOIN {block_ludifica_general} AS g ON g.userid = lu.userid" .
                " GROUP BY lu.userid, g.nickname" .
                " ORDER BY points DESC";

	$records = $DB->get_records_sql($sql);

        return self::get_toplist($records, $includecurrent);
    }

    public static function get_lastmonth($courseid, $includecurrent = true) {
        // ToDo: The current DB structure not support points by date because all points are sum by type.
        return array();
    }

    private static function get_toplist($records, $includecurrent = true) {
        global $USER;

        $list = array();

        $k = 0;
        $curentincluded = false;
        foreach ($records as $record) {
            $k++;

            $record->position = $k;
            $list[] = $record;

            if ($record->id == $USER->id) {
                $record->current = true;
                $curentincluded = true;
            }

            if (empty($record->nickname)) {
                global $USER;
                if ($record->id == $USER->id) {
                    $record->nickname = fullname($USER);
                } else {
                    $record->nickname = get_string('nicknameunasined', 'block_ludifica', $record->id);
                }
            }

            if ($k >= self::LIMIT_RANKING) {
                break;
            }
        }

        if ($includecurrent && !$curentincluded) {
            $k = 0;
            foreach ($records as $record) {

                $k++;
                if ($record->id !== $USER->id) {
                    continue;
                }

                if (empty($record->nickname)) {
                    global $USER;
                    $record->nickname = fullname($USER);
                }

                $record->position = $k;
                $record->current = true;
                $list[] = $record;
                break;
            }
        }

        return $list;
    }

    public static function get_storetabs($active) {
        $tabs = array();

        $avatars = new \stdClass();
        $avatars->text = get_string('avatars', 'block_ludifica');
        $avatars->title = $avatars->text;
        $avatars->url = new \moodle_url('/blocks/ludifica/avatars.php');
        $avatars->active = $active == 'avatars';
        $tabs[] = $avatars;

        $tickets = new \stdClass();
        $tickets->text = get_string('tickets', 'block_ludifica');
        $tickets->title = $tickets->text;
        $tickets->url = new \moodle_url('/blocks/ludifica/tickets.php');
        $tickets->active = $active == 'tickets';
        $tabs[] = $tickets;

        return $tabs;
    }

    public static function get_uniqueid() {
        $uniqueid = 'block_ludifica_' . self::$instancescounter;
        self::$instancescounter++;

        return $uniqueid;
    }
}
