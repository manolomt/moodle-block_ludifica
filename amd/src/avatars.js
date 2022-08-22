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
 * Javascript to avatars manage.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/notification', 'core/str', 'core/ajax', 'block_ludifica/alertc', 'block_ludifica/player'],
function($, Notification, Str, Ajax, Alertc, Player) {

    var wwwroot = M.cfg.wwwroot;
    var s = [];
    var currentavatarid = null;

    /**
     * Initialise all for avatars.
     *
     */
    var init = function(userid, useravatarid) {

        currentavatarid = useravatarid;

        // Load used strings.
        var strings = [
            { key: 'avatarbuy', component: 'block_ludifica' },
            { key: 'avatarbuymessage', component: 'block_ludifica' },
            { key: 'buy', component: 'block_ludifica' },
            { key: 'notbuy', component: 'block_ludifica' },
            { key: 'bought', component: 'block_ludifica' },
            { key: 'avataruse', component: 'block_ludifica' },
            { key: 'avatarusemessage', component: 'block_ludifica' },
            { key: 'use', component: 'block_ludifica' },
            { key: 'avatarnotuse', component: 'block_ludifica' },
            { key: 'avatarused', component: 'block_ludifica' },
            { key: 'cancel' },
            { key: 'continue' },
            { key: 'error' },
            { key: 'info' },
        ];
        strings.forEach(function(one, index) {
            s[one.key] = one.key;
        });

        Str.get_strings(strings).then(function (results) {
            results.forEach(function(value, index) {
                s[strings[index].key] = value;
            });
        });
        // End load used strings.

        // Buy.
        $('[data-action="buy"]').on('click', function() {
            var $element = $(this);
            var avatarid = $element.data('id');

            Notification.confirm(s['avatarbuy'], s['avatarbuymessage'], s['buy'], s['cancel'], function() {

                // Buy the avatar.
                Ajax.call([{
                    methodname: 'block_ludifica_buy_avatar',
                    args: { 'id': avatarid },
                    done: function (data) {

                        if (data) {
                            Alertc.success(s['bought']);
                            var $avatarbox = $('#avatar-' + avatarid);
                            $avatarbox.removeClass('usernothas');
                            $avatarbox.addClass('userhas');
                            Player.reloadStats();
                        } else {
                            Alertc.error(s['notbuy']);
                        }

                    },
                    fail: function (e) {
                        console.log('Error buy avatar');
                        console.log(e);
                    }
                }]);

            });

        });

        // Buy.
        $('[data-action="use"]').on('click', function() {
            var $element = $(this);
            var avatarid = $element.data('id');

            Notification.confirm(s['avataruse'], s['avatarusemessage'], s['use'], s['cancel'], function() {

                // Select the avatar.
                Ajax.call([{
                    methodname: 'block_ludifica_use_avatar',
                    args: { 'id': avatarid },
                    done: function (data) {

                        if (data) {
                            Alertc.success(s['avatarused']);
                            $('.oneavatar.inuse').removeClass('inuse');

                            var $avatarbox = $('#avatar-' + avatarid);
                            $avatarbox.addClass('inuse');
                            console.log($avatarbox);
                        } else {
                            Alertc.error(s['avatarnotuse']);
                        }

                    },
                    fail: function (e) {
                        console.log('Error assigning avatar');
                        console.log(e);
                    }
                }]);

            });

        });

    };

    return {
        init: init
    };
});
