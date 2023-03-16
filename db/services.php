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
 * External functions and service definitions.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'block_ludifica_get_ticket' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'get_ticket',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Get a ticket',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => false
    ),
    'block_ludifica_buy_ticket' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'buy_ticket',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Buy a ticket',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true
    ),
    'block_ludifica_give_ticket' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'give_ticket',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Give a ticket',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true
    ),
    'block_ludifica_buy_avatar' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'buy_avatar',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Buy an avatar',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true
    ),
    'block_ludifica_use_avatar' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'use_avatar',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Use the specified avatar',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true
    ),
    'block_ludifica_get_profile' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'get_profile',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Get the current user profile',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true
    ),
);

$services = array(
    'Ludifica webservices' => array(
        'functions' => array ('block_ludifica_get_ticket', 'block_ludifica_buy_ticket', 'block_ludifica_give_ticket',
                                'block_ludifica_buy_avatar', 'block_ludifica_use_avatar', 'block_ludifica_get_profile'),
        'restrictedusers' => 0, // If 1, the administrator must manually select which user can use this service.
        // (Administration > Plugins > Web services > Manage services > Authorised users).
        'enabled' => 0, // If 0, then token linked to this service won't work.
        'shortname' => 'block_ludifica_ws'
    ),
    'Ludifica manage webservices' => array(
        'functions' => array (),
        'restrictedusers' => 1, // If 1, the administrator must manually select which user can use this service.
        // (Administration > Plugins > Web services > Manage services > Authorised users).
        'enabled' => 0, // If 0, then token linked to this service won't work.
        'shortname' => 'block_ludifica_managerws'
    )
);
