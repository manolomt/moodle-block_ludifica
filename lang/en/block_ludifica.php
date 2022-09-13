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
 * Strings for component 'block_ludifica', language 'en'
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Ludifica';

//Capabilities
$string['ludifica:addinstance'] = 'Add a new Ludifica block';
$string['ludifica:myaddinstance'] = 'Add a new Ludifica block to Dashboard';
$string['ludifica:manage'] = 'Manage Ludifica block settings';

$string['privacy:metadata'] = 'The Ludifica block does not store privacy user information.';

$string['available'] = 'Quantity available';
$string['availabledate'] = 'Available until';
$string['avatar'] = 'Avatar';
$string['avatars'] = 'Avatars';
$string['avatarbust'] = 'Bust';
$string['avatarbuy'] = 'Buy the avatar';
$string['avatarbuymessage'] = 'Do you really want to buy this avatar?';
$string['avatardelete'] = 'Delete Avatar';
$string['avatarnew'] = 'New avatar';
$string['avatarnotuse'] = 'Not can use this avatar';
$string['avatarsources'] = 'Sources';
$string['avatarsources_help'] = 'List of image URIs. One by line. The 0 line is for level 0 and equal for the other levels.
Can use the next dynamic tags for the URI: {name}, {level} and {levelname}.';
$string['avatartype'] = 'Type';
$string['avatartype_normal'] = 'Normal';
$string['avatartype_user'] = 'For specific user';
$string['avataruse'] = 'Use the avatar';
$string['avatarused'] = 'Avatar assigned';
$string['avatarusemessage'] = 'Do you really want change the avatar?';
$string['bought'] = 'Ticket bought';
$string['buy'] = 'Buy';
$string['buyticket'] = 'Buy ticket';
$string['buyticketmessage'] = 'Do you really want to buy this ticket?';
$string['changessaved'] = 'Changes saved';
$string['coinsbypoints'] = 'Coins by points';
$string['coinsbypoints_help'] = '';
$string['coinsequalpoints'] = 'Coins equal to points?';
$string['coinsequalpoints_help'] = 'One coin for each point.';
$string['cost'] = 'Cost';
$string['customtitle'] = 'Custom title';
$string['defaultlevel'] = 'Default level';
$string['deleted'] = 'Record deleted successfully';
$string['durationcheck'] = 'Use duration field?';
$string['durationcheck_help'] = '';
$string['durationfield'] = 'Duration field';
$string['durationfield_help'] = 'A course field to specify the course duration time.';
$string['edit'] = 'Edit';
$string['editnickname'] = 'Edit the nickname';
$string['enabled'] = 'Enabled';
$string['errornotavatardata'] = 'Not avatar data';
$string['errornotticketdata'] = 'Not ticket data';
$string['eventavatar_created'] = 'Avatar created';
$string['eventavatar_deleted'] = 'Avatar deleted';
$string['eventavatar_updated'] = 'Avatar updated';
$string['eventticket_created'] = 'Ticket created';
$string['eventticket_updated'] = 'Ticket updated';
$string['eventticket_deleted'] = 'Ticket deleted';
$string['generalstate'] = 'General state';
$string['generalsettings'] = 'General';
$string['gift'] = 'Gift';
$string['give'] = 'Give';
$string['given'] = 'Ticket given';
$string['giveticket'] = 'Give a ticket';
$string['giveticketmessage'] = 'Choose the target contact';
$string['infodata'] = 'Related data (JSON format)';
$string['initialemailpattern'] ='Initial email pattern (not present in valid emails)';
$string['initialemailpattern_help'] = '';
$string['labellevel'] = 'Level {$a}';
$string['levels'] = 'Levels';
$string['levelrequired'] = 'Level <em>{$a}</em> required';
$string['levels_help'] = 'One level by line, with the structure: Level name|max points.<br />
The max points in the last line are not required, unlimited points are assigned.<br />
The line number indicate the level order.';
$string['modulepoints'] = 'Points for each module of this course';
$string['moreinfo'] = 'More information';
$string['maxtickets'] = 'You currently have the maximum quantity of this ticket.';
$string['missingfield'] = 'This field is required';
$string['newblocktitle'] = 'Gamification';
$string['newnickname'] = 'new value to {$a}';
$string['newticket'] = 'New ticket';
$string['nicknameexists'] = 'The alias is already in use by another user, please choose another alias.';
$string['nicknameunasined'] = 'Player {$a}';
$string['noavatars'] = 'Avatars not available yet';
$string['notavailable'] = 'No available to buy.';
$string['notavailabledate'] = 'The date to buy this ticket has already passed.';
$string['notbuy'] = 'Error buying';
$string['notcompliance'] = 'You don\'t compliance the requirements to buy it ticket.';
$string['notcontacts'] = 'You don\'t have contacts yet';
$string['notcostcompliance'] = 'Insufficient coins';
$string['notgive'] = 'Error giving a ticket';
$string['notickets'] = 'Not available tickets yet';
$string['nottopyet'] = 'Not positions yet';
$string['notusertickets'] = 'Not tickets yet';
$string['numcoins'] = '{$a} coins';
$string['numpoints'] = '{$a} points';
$string['owner'] = 'Owner';
$string['playerhead'] = 'Player';
$string['pointshead'] = 'Points';
$string['pointsbychangemail'] = 'Points by change email in personal profile';
$string['pointsbychangemail_help'] = '';
$string['pointsbyembedquestion'] = 'Points by answer an"<i>Embed question</i>"';
$string['pointsbyembedquestion_help'] = '';
$string['pointsbyembedquestion_all'] = 'All "<i>Embed question</i> of this site give points?"';
$string['pointsbyembedquestion_all_help'] = 'Otherwise, write a list with idnumber of questions that give points to users';
$string['pointsbyembedquestion_ids'] = 'Idnumber of "<i>Embed question</i>" that give points';
$string['pointsbyembedquestion_ids_help'] = 'Comma separated list of idnumbers';
$string['pointsbyembedquestion_partial'] = 'Points if embed question is answered partially correct?';
$string['pointsbyembedquestion_partial_help'] = '';
$string['pointsbyendcourse'] = 'Points by complete a course';
$string['pointsbyendcourse_help'] = '';
$string['pointsbyendcourse_all'] = 'All courses of this site give points?';
$string['pointsbyendcourse_all_help'] = 'Otherwise, select a category for look courses or write them as a list...';
$string['pointsbyendcourse_category'] = 'Category Id where courses completed give points';
$string['pointsbyendcourse_category_help'] = '';
$string['pointsbyendcourse_ids'] = 'Courses Id of courses whose completion give points';
$string['pointsbyendcourse_ids_help'] = "Comma separated list of course id's";
$string['pointsbyendcoursemodule'] = 'Points by complete a course module';
$string['pointsbyendcoursemodule_help'] = 'Only if previous option is enabled. Otherwise, will be evaluated each course / module setting.';
$string['pointsbyendcoursemodule_all'] = 'All course module give points?';
$string['pointsbyendcoursemodule_all_help'] = 'Otherwise, previous points will be given when any course module is complete and particular settings in each course will be not considered.';
$string['pointsbynewuser'] = 'Points for every new user';
$string['pointsbynewuser_help'] = '';
$string['pointsbyrecurrentlogin1'] = 'Points to recurrent login';
$string['pointsbyrecurrentlogin1_help'] = '';
$string['pointsbyrecurrentlogin2'] = 'Points aditionals by next days';
$string['pointsbyrecurrentlogin2_help'] = '';
$string['pointsbyviewfolder_all'] = 'Different points when display a folder?';
$string['pointsbyviewfolder_all_help'] = 'If this is not checked, the configuration for the rest of course modules will be used...';
$string['pointsbyviewfolder'] = 'Points when display a folder content';
$string['pointsbyviewfolder_help'] = '';
$string['pointstocoins'] = 'Points to add coins';
$string['pointstocoins_help'] = '';
$string['positionhead'] = 'Pos';
$string['recurrentlogindays'] = 'Recurrent login days';
$string['recurrentlogindays_help'] = 'Days to start to assign points. 0 to don\'t use this.';
$string['requiretext'] = 'Prerequisites';
$string['searchticket'] = 'Search';
$string['store'] = 'Store';
$string['tab_description'] = 'You can set different configuration for each tenant or check the first option to use the default configuration for this site. <a href="{$a}">Click here </a>to view the default configuration of Ludifica.';
$string['tablastmonth'] = 'Tab Top on the last month';
$string['tabprofile'] = 'Tab Profile';
$string['tabtitle_lastmonth'] = 'Last month top';
$string['tabtitle_profile'] = 'Profile';
$string['tabtitle_topbycourse'] = 'Top by course';
$string['tabtitle_topbysite'] = 'Top by site';
$string['tabtopbycourse'] = 'Tab Top by course';
$string['tabtopbysite'] = 'Tab Top by site';
$string['ticket'] = 'Ticket';
$string['ticketdelete'] = 'Delete ticket';
$string['tickets'] = 'Tickets';
$string['ticketstype_default'] = 'Default';
$string['ticketstype'] = 'Type';
$string['ticketcode'] = 'Code';
$string['ticketcode_help'] = 'If empty, the system assign a random string to each user.';
$string['ticketavailabledate'] = 'Available date';
$string['ticketavailable'] = 'Amount available';
$string['ticketnotavailable'] = 'The ticket is not available now, maybe was used in another session.';
$string['ticketbyuser'] = 'Amount by user';
$string['ticketsvalidate'] = 'Validate ticket';
$string['ticketused'] = 'Ticket used';
$string['thumbnail'] = 'Thumbnail';
$string['unlimited'] = 'Unlimited';
$string['use_default_settings'] = 'Use general configuration of Ludifica in this tenant?';
$string['use'] = 'Use';
$string['usedat'] = 'Used at {$a}';
$string['useddate'] = 'Used date';
$string['usercode'] = 'User code';
$string['usercodes'] = 'User codes';
$string['usertickets'] = 'User tickets';
$string['useup'] = 'Use up';
