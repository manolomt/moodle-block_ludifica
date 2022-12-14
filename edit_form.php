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

    /**
     * Block edition form.
     *
     * @param object $mform Parent form.
     */
    protected function specific_definition($mform) {
        global $CFG, $COURSE;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('customtitle', 'block_ludifica'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('checkbox', 'config_tabprofile', get_string('tabprofile', 'block_ludifica'));

        $mform->addElement('checkbox', 'config_tabtopbycourse', get_string('tabtopbycourse', 'block_ludifica'));

        $mform->addElement('checkbox', 'config_tabtopbysite', get_string('tabtopbysite', 'block_ludifica'));

        $mform->addElement('checkbox', 'config_tablastmonth', get_string('tablastmonth', 'block_ludifica'));

        // Points by complete modules not apply in the site level.
        if ($COURSE->id > SITEID && $COURSE->enablecompletion) {

            $pointsbycoursemodule = intval(get_config('block_ludifica', 'pointsbyendcoursemodule'));
            $allmodules = get_config('block_ludifica', 'pointsbyendallmodules');

            if (!empty($pointsbycoursemodule) && !$allmodules) {

                $format = course_get_format($COURSE->id);
                $sections = $format->get_sections();
                $modinfo = get_fast_modinfo($COURSE);
                $context = context_course::instance($COURSE->id);
                $completioninfo = new completion_info($COURSE);

                $coursemodules = [];
                foreach ($sections as $section) {
                    $sectionindex = $section->section;

                    if (isset($course->numsections) && $sectionindex > $course->numsections) {
                        // Support for legacy formats that still provide numsections (see MDL-57769).
                        break;
                    }

                    if (empty($modinfo->sections[$sectionindex])) {
                        continue;
                    }

                    foreach ($modinfo->sections[$sectionindex] as $modnumber) {
                        $module = $modinfo->cms[$modnumber];
                        $iconurl = $module->get_icon_url();
                        $siconurl = s($iconurl);

                        // Exclude labels.
                        if ($module->modname == 'label') {
                            continue;
                        }

                        if ($module->deletioninprogress) {
                            continue;
                        }

                        if ($completioninfo->is_enabled($module) == COMPLETION_TRACKING_NONE) {
                            continue;
                        }

                        $thismod = new \stdClass();
                        $thismod->id = $module->id;
                        $thismod->name = format_string($module->name, true, ['context' => $context]);
                        $thismod->type = $module->modname;
                        $thismod->typetitle = get_string('pluginname', $module->modname);
                        $thismod->iconurl = $siconurl;

                        $coursemodules[] = $thismod;

                    }
                }

                if (count($coursemodules) > 0) {

                    $mform->addElement('header', 'configheader_modules', get_string('configheader_modules', 'block_ludifica'));
                    $mform->addElement('static', 'configmodules_help', '', get_string('configmodules_help', 'block_ludifica'));

                    foreach ($coursemodules as $cm) {
                        $content = '<img src="' . $cm->iconurl . '" alt="' . $cm->typetitle . '" title="' .
                                        $cm->typetitle . '" class="icon">';

                        $content .= ' ' . $cm->name . ' ';
                        $content .= '<label>(' . $cm->typetitle . ')</label>';

                        $mform->addElement('text', 'config_points_module_' . $cm->id, $content, ['size' => 4]);
                        $mform->setType('config_points_module_' . $cm->id, PARAM_INT);
                        $mform->setDefault('config_points_module_' . $cm->id, 0);
                    }
                }
            }
        }
    }
}
