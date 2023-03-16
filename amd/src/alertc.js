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
 * Alerts control.
 *
 * @module    block/ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'],
function($) {

    var TIME_AUTOCLOSE = 1500;
    var CONTAINER_SELECTOR = 'body';

    var show = function(type, msg, autohide) {
        var $control = $('<div class="alertc-control"></div>');
        var $alert = $('<div class="alert"></div>');
        var cssclass = '';

        $control.append($alert);

        switch (type) {
            case 'error':
                cssclass = 'alert-danger';
                break;
            case 'success':
                cssclass = 'alert-success';
                break;
            case 'warning':
                cssclass = 'alert-warning';
                break;
            default:
                cssclass = 'alert-secondary';
        }

        $alert.addClass(cssclass);
        $alert.addClass('alert-dismissible');

        var $close = $('<button type="button" class="close">&times;</button>');
        $close.on('click', function() {
            $control.remove();
        });
        $alert.append($close);

        if (autohide || typeof autohide == 'undefined') {
            setTimeout(function() {
                $control.hide(600, function() {
                    $control.remove();
                });
            }, TIME_AUTOCLOSE);
        }

        $alert.append(msg);
        $(CONTAINER_SELECTOR).append($control);
    };

    return {
        "info": function(msg, autohide) {
            show('info', msg, autohide);
        },
        "error": function(msg) {
            show('error', msg, false);
        },
        "success": function(msg, autohide) {
            show('success', msg, autohide);
        },
        "warning": function(msg, autohide) {
            show('warning', msg, autohide);
        },
        "setclosetime": function(secs) {
            TIME_AUTOCLOSE = secs;
        }
    };
});
