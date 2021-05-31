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

$string['customtitle'] = 'Custom title';
$string['newblocktitle'] = 'Gamification';
// $string['settingsheaderfields'] = 'Fields';
$string['durationfield'] = 'Duration field';
$string['durationfield_help'] = 'A course field to specify the course duration time.';
$string['pointsbyendcourse'] = 'Points by complete a course';
$string['pointsbyendcourse_help'] = '';
$string['levels'] = 'Levels';
$string['levels_help'] = 'One level by line, with the structure: Level name|max points.<br />
The max points in the last line are not required, unlimited points are assigned.<br />
The line number indicate the level order.';
$string['ticket'] = 'Ticket';
$string['tickets'] = 'Tickets';
// $string['card'] = 'Card';
// $string['cards'] = 'Cards';
$string['generalstate'] = 'General state';
$string['generalsettings'] = 'General';
$string['numpoints'] = '{$a} points';
$string['numcoins'] = '{$a} coins';
$string['recurrentlogindays'] = 'Recurrent login days';
$string['recurrentlogindays_help'] = 'Days to start to assign points. 0 to don\'t use this.';
$string['pointsbyrecurrentlogin1'] = 'Points to recurrent login';
$string['pointsbyrecurrentlogin1_help'] = '';
$string['pointsbyrecurrentlogin2'] = 'Points aditionals by next days';
$string['pointsbyrecurrentlogin2_help'] = '';
$string['coinsbypoints'] = 'Coins by points';
$string['coinsbypoints_help'] = '';
$string['pointstocoins'] = 'Points to add coins';
$string['pointstocoins_help'] = '';
$string['defaultlevel'] = 'Default level';
$string['labellevel'] = 'Level {$a}';
$string['available'] = 'Quantity available';
$string['availabledate'] = 'Available until';
$string['requiretext'] = 'Prerequisites';
$string['cost'] = 'Cost';
$string['buy'] = 'Buy';
$string['notbuy'] = 'Error buying';
$string['notcompliance'] = 'You don\'t compliance the requirements to buy it ticket.';
$string['notavailabledate'] = 'The date to buy this ticket has already passed.';
$string['notavailable'] = 'No available to buy.';
$string['newticket'] = 'New ticket';
$string['notickets'] = 'Not available tickets yet';
$string['changessaved'] = 'Changes saved';
$string['moreinfo'] = 'More information';
$string['ticketstype_default'] = 'Default';
$string['ticketstype'] = 'Type';
$string['ticketcode'] = 'Code';
$string['ticketcode_help'] = 'If empty, the system assign a random string to each user.';
$string['ticketavailabledate'] = 'Available date';
$string['ticketavailable'] = 'Amount available';
$string['ticketbyuser'] = 'Amount by user';
$string['ticketsvalidate'] = 'Validate ticket';
$string['enabled'] = 'Enabled';
$string['infodata'] = 'Related data (JSON format)';
$string['missingfield'] = 'This field is required';
$string['eventticket_created'] = 'Ticket created';
$string['eventticket_updated'] = 'Ticket updated';
$string['eventticket_deleted'] = 'Ticket deleted';
$string['unlimited'] = 'Unlimited';
$string['edit'] = 'Edit';
$string['ticketdelete'] = 'Delete ticket';
$string['deleted'] = 'Record deleted successfully';
$string['thumbnail'] = 'Thumbnail';
$string['notcostcompliance'] = 'Insufficient coins';
$string['maxtickets'] = 'You currently have the maximum quantity of this ticket.';
$string['usertickets'] = 'User tickets';
$string['buyticket'] = 'Buy ticket';
$string['buyticketmessage'] = 'Do you really want to buy this ticket?';
$string['levelrequired'] = 'Level <em>{$a}</em> required';
$string['gift'] = 'Gift';
$string['give'] = 'Give';
$string['notgive'] = 'Error giving a ticket';
$string['giveticket'] = 'Give a ticket';
$string['giveticketmessage'] = 'Choose the target contact';
$string['notcontacts'] = 'You don\'t have contacts yet';
$string['given'] = 'Ticket given';
$string['usercode'] = 'User code';
$string['usercodes'] = 'User codes';
$string['bought'] = 'Ticket bought';
$string['useup'] = 'Use up';
$string['searchticket'] = 'Search ticket';
$string['owner'] = 'Owner';
$string['searchticket'] = 'Search';
$string['ticketused'] = 'Ticket used';
$string['useddate'] = 'Used date';
$string['usedat'] = 'Used at {$a}';
$string['ticketnotavailable'] = 'The ticket is not available now, maybe was used in another session.';
$string['avatar'] = 'Avatar';
$string['avatars'] = 'Avatars';
$string['avatardelete'] = 'Delete Avatar';
$string['avatarbust'] = 'Bust';
$string['avatarnew'] = 'New avatar';
$string['avatartype'] = 'Type';
$string['avatartype_normal'] = 'Normal';
$string['avatartype_user'] = 'For specific user';
$string['avatarsources'] = 'Sources';
$string['avatarsources_help'] = 'List of image URIs. One by line. The 0 line is for level 0 and equal for the other levels.
Can use the next dynamic tags for the URI: {name}, {level} and {levelname}.';
$string['errornotavatardata'] = 'Not avatar data';
$string['noavatars'] = 'Avatars not available yet';
$string['use'] = 'Use';
$string['eventavatar_created'] = 'Avatar created';
$string['eventavatar_updated'] = 'Avatar updated';
$string['eventavatar_deleted'] = 'Avatar deleted';
$string['avatarbuy'] = 'Buy the avatar';
$string['avatarbuymessage'] = 'Do you really want to buy this avatar?';
$string['avataruse'] = 'Use the avatar';
$string['avatarusemessage'] = 'Do you really want change the avatar?';
$string['avatarnotuse'] = 'Not can use this avatar';
$string['avatarused'] = 'Avatar assigned';
$string['errornotticketdata'] = 'Not ticket data';
$string['tabprofile'] = 'Tab Profile';
$string['tabtopbycourse'] = 'Tab Top by course';
$string['tabtopbysite'] = 'Tab Top by site';
$string['tablastmonth'] = 'Tab Top on the last month';
$string['tabtitle_profile'] = 'Profile';
$string['tabtitle_topbycourse'] = 'Top by course';
$string['tabtitle_topbysite'] = 'Top by site';
$string['tabtitle_lastmonth'] = 'Last month top';
$string['positionhead'] = 'Pos';
$string['playerhead'] = 'Player';
$string['pointshead'] = 'Points';
$string['store'] = 'Store';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
