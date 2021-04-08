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

$functions = array(
    'block_ludifica_buyticket' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'buyticket',
        'classpath' => 'blocks/ludifica/classes/externallib.php',
        'description' => 'Buy a ticket',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true
    ),
/*
    'block_ludifica_accessrevoke' => array(
        'classname' => '\block_ludifica\external',
        'methodname' => 'access_revoke',
        'classpath' => 'blocks/ludifica/externallib.php',
        'description' => 'Revoke resource users access',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
        'capabilities' => 'block/ludifica:write'
    ),
*/
);

$services = array(
        'Ludifica webservices' => array(
                'functions' => array ('block_ludifica_buyticket'),
                'restrictedusers' => 0, // if 1, the administrator must manually select which user can use this service.
                // (Administration > Plugins > Web services > Manage services > Authorised users)
                'enabled' => 0, // if 0, then token linked to this service won't work
                'shortname' => 'block_ludifica_ws'
        ),
        'Ludifica manage webservices' => array(
            'functions' => array (),
            'restrictedusers' => 1, // if 1, the administrator must manually select which user can use this service.
            // (Administration > Plugins > Web services > Manage services > Authorised users)
            'enabled' => 0, // if 0, then token linked to this service won't work
            'shortname' => 'block_ludifica_managerws'
    )
);
