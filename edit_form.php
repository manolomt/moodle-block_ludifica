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
 * Form for editing block instances.
 *
 * @package   block_ludifica
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing block instances.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_ludifica_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG, $COURSE;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('customtitle', 'block_ludifica'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('checkbox', 'config_tabprofile', get_string('tabprofile', 'block_ludifica'));

        $mform->addElement('checkbox', 'config_tabtopbycourse', get_string('tabtopbycourse', 'block_ludifica'));

        $mform->addElement('checkbox', 'config_tabtopbysite', get_string('tabtopbysite', 'block_ludifica'));
	
	//$mform->addElement('checkbox', 'config_tablastmonth', get_string('tablastmonth', 'block_ludifica'));

	$mform->addElement('header', 'configheader_sectionmodules', get_string('modulepoints', 'block_ludifica'));

	if($COURSE->id > 1) {

           $format = course_get_format($COURSE->id);
	   $sections = $format->get_sections();
	   $modinfo = get_fast_modinfo($COURSE);
	   $context = context_course::instance($COURSE->id);
	
           $course_modules = [];
           foreach ($sections as $section) {
                    $i = $section->section;

                    if (isset($course->numsections) && ($i > $course->numsections)) {
                       // Support for legacy formats that still provide numsections (see MDL-57769).
                       break;
	            }

                   //$thissection_modules = [];
                   if (!empty($modinfo->sections[$i])) {
                      foreach ($modinfo->sections[$i] as $modnumber) {
			       $module = $modinfo->cms[$modnumber];
                               $icon_url = $module->get_icon_url();
                               $s_icon_url = s($icon_url);

                               if ((get_config(
                                'block_course_modulenavigation',
                                'toggleshowlabels'
                                 ) == 1) && ($module->modname == 'label')) {
                                 continue;
                               }
                    if ( $module->deletioninprogress == '1') {
                        continue;
                    }
		    $thismod = new stdClass();
		    $thismod->instance = $module->instance;
		    $thismod->id = $module->id;
                    $thismod->name = format_string(
                        $module->name,
                        true,
                        ['context' => $context]
		    );
		    $thismod->type = $module->modname;
		    $thismod->iconurl = $s_icon_url;

                    if ($module->modname == 'label') {
                        $thismod->label = 1;
                    }
                    else
			 $thismod->label = 0;

		    if($thismod->label == 0)
                       $course_modules[] = $thismod;

	        } 
		$course_hasmodules = (count($course_modules) > 0);
	    }
	}
	if($course_hasmodules) {
           foreach($course_modules as $course_module) {
                   $html_for_icon_module = "<img src='$course_module->iconurl' alt='$course_module->type' title='$course_module->type' class='icon'> ";

                   $mform->addElement('text', "config_points_module_$course_module->id", "$html_for_icon_module $course_module->name <i>($course_module->type)</i>");
                   $mform->setType("config_points_module_$course_module->id", PARAM_INT);
           }			  
	}
	}

    }
}
