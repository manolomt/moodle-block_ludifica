<?php
//
// This is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for the block
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$settings = new admin_category('block_ludifica_settings', get_string('pluginname', 'block_ludifica'));

$generalsettings = new admin_settingpage('block_ludifica', get_string('generalsettings', 'block_ludifica'));

if ($ADMIN->fulltree) {

    // // Course fields.
    // $name = 'block_ludifica/settingsheaderfields';
    // $heading = get_string('settingsheaderfields', 'block_ludifica');
    // $setting = new admin_setting_heading($name, $heading, '');
    // $generalsettings->add($setting);
	
    // Use duration field?
    $name = 'block_ludifica/durationcheck';
    $title = get_string('durationcheck', 'block_ludifica');
    $help = get_string('durationcheck_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // Duration field.
    $fields = $DB->get_records_menu('customfield_field', null, 'name', 'id, name');
    $name = 'block_ludifica/duration';
    $title = get_string('durationfield', 'block_ludifica');
    $help = get_string('durationfield_help', 'block_ludifica');
    $setting = new admin_setting_configselect($name, $title, $help, '', $fields);
    $generalsettings->add($setting);

    // Create user points.
    $name = 'block_ludifica/pointsbynewuser';
    $title = get_string('pointsbynewuser', 'block_ludifica');
    $help = get_string('pointsbynewuser_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Change email points.
    $name = 'block_ludifica/pointsbychangemail';
    $title = get_string('pointsbychangemail', 'block_ludifica');
    $help = get_string('pointsbychangemail_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Initial email pattern.
    $name = 'block_ludifica/initialemailpattern';
    $title = get_string('initialemailpattern', 'block_ludifica');
    $help = get_string('initialemailpattern_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 'noreply', PARAM_TEXT);
    $generalsettings->add($setting);

    // Embed question points.
    $name = 'block_ludifica/pointsbyembedquestion';
    $title = get_string('pointsbyembedquestion', 'block_ludifica');
    $help = get_string('pointsbyembedquestion_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Points when answer correctly any embed question?
    $name = 'block_ludifica/pointsbyembedquestion_all';
    $title = get_string('pointsbyembedquestion_all', 'block_ludifica');
    $help = get_string('pointsbyembedquestion_all_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // List of question idnumber.
    $name = 'block_ludifica/pointsbyembedquestion_ids';
    $title = get_string('pointsbyembedquestion_ids', 'block_ludifica');
    $help = get_string('pointsbyembedquestion_ids_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, '', PARAM_TEXT);
    $generalsettings->add($setting);

    // Points when answer partialy an embed question?
    $name = 'block_ludifica/pointsbyembedquestion_partial';
    $title = get_string('pointsbyembedquestion_partial', 'block_ludifica');
    $help = get_string('pointsbyembedquestion_partial_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);
     
    // Complete course points.
    $name = 'block_ludifica/pointsbyendcourse';
    $title = get_string('pointsbyendcourse', 'block_ludifica');
    $help = get_string('pointsbyendcourse_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Points when completed any course?
    $name = 'block_ludifica/pointsbyendcourse_all';
    $title = get_string('pointsbyendcourse_all', 'block_ludifica');
    $help = get_string('pointsbyendcourse_all_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // Category for courses.
    $name = 'block_ludifica/pointsbyendcourse_category';
    $title = get_string('pointsbyendcourse_category', 'block_ludifica');
    $help = get_string('pointsbyendcourse_category_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);
    
    // List of course id's.
    $name = 'block_ludifica/pointsbyendcourse_ids';
    $title = get_string('pointsbyendcourse_ids', 'block_ludifica');
    $help = get_string('pointsbyendcourse_ids_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, '', PARAM_TEXT);
    $generalsettings->add($setting);

    // Complete course module points.
    $name = 'block_ludifica/pointsbyendcoursemodule';
    $title = get_string('pointsbyendcoursemodule', 'block_ludifica');
    $help = get_string('pointsbyendcoursemodule_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Points when completed any course module
    $name = 'block_ludifica/pointsbyendcoursemodule_all';
    $title = get_string('pointsbyendcoursemodule_all', 'block_ludifica');
    $help = get_string('pointsbyendcoursemodule_all_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // Points when completed any course module
    $name = 'block_ludifica/pointsbyviewfolder_all';
    $title = get_string('pointsbyviewfolder_all', 'block_ludifica');
    $help = get_string('pointsbyviewfolder_all_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // Points when view a folder content
    $name = 'block_ludifica/pointsbyviewfolder';
    $title = get_string('pointsbyviewfolder', 'block_ludifica');
    $help = get_string('pointsbyviewfolder_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login days.
    $name = 'block_ludifica/recurrentlogindays';
    $title = get_string('recurrentlogindays', 'block_ludifica');
    $help = get_string('recurrentlogindays_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login points.
    $name = 'block_ludifica/pointsbyrecurrentlogin1';
    $title = get_string('pointsbyrecurrentlogin1', 'block_ludifica');
    $help = get_string('pointsbyrecurrentlogin1_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Recurrent login points by next days.
    $name = 'block_ludifica/pointsbyrecurrentlogin2';
    $title = get_string('pointsbyrecurrentlogin2', 'block_ludifica');
    $help = get_string('pointsbyrecurrentlogin2_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Coins by points.
    $name = 'block_ludifica/coinsbypoints';
    $title = get_string('coinsbypoints', 'block_ludifica');
    $help = get_string('coinsbypoints_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Coins each x points.
    $name = 'block_ludifica/pointstocoins';
    $title = get_string('pointstocoins', 'block_ludifica');
    $help = get_string('pointstocoins_help', 'block_ludifica');
    $setting = new admin_setting_configtext($name, $title, $help, 0, PARAM_INT);
    $generalsettings->add($setting);

    // Coins equal to points?
    $name = 'block_ludifica/coinsequalpoints_check';
    $title = get_string('coinsequalpoints', 'block_ludifica');
    $help = get_string('coinsequalpoints_help', 'block_ludifica');
    $setting = new admin_setting_configcheckbox($name, $title, $help, 0);
    $generalsettings->add($setting);

    // Levels.
    $name = 'block_ludifica/levels';
    $title = get_string('levels', 'block_ludifica');
    $help = get_string('levels_help', 'block_ludifica');
    $setting = new admin_setting_configtextarea($name, $title, $help, '');
    $generalsettings->add($setting);

}

$settings->add('block_ludifica_settings', $generalsettings);

$externalpage = new admin_externalpage('block_ludifica_avatars', get_string('avatars', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/avatars.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

/*$externalpage = new admin_externalpage('block_ludifica_cards', get_string('cards', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/cards.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);*/

$externalpage = new admin_externalpage('block_ludifica_tickets', get_string('tickets', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/tickets.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);

$externalpage = new admin_externalpage('block_ludifica_state', get_string('generalstate', 'block_ludifica'),
                    new moodle_url("/blocks/ludifica/state.php"), 'block/ludifica:manage');
$settings->add('block_ludifica_settings', $externalpage);
