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
 * Javascript to tickets manage.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/notification', 'core/str', 'core/ajax'],
function($, Notification, Str, Ajax) {

    var wwwroot = M.cfg.wwwroot;
    var s = [];

    /**
     * Initialise all for tickets.
     *
     */
    var init = function() {

        // Load used strings.
        var strings = [
            { key: 'buyticket', component: 'block_ludifica' },
            { key: 'buyticketmessage', component: 'block_ludifica' },
            { key: 'buy', component: 'block_ludifica' },
            { key: 'notbuy', component: 'block_ludifica' },
            { key: 'cancel' },
            { key: 'continue' },
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

            Notification.confirm(s['buyticket'], s['buyticketmessage'], s['buy'], s['cancel'], function() {
                var ticketid = $element.data('id');

                // Buy the ticket.
                Ajax.call([{
                    methodname: 'block_ludifica_buyticket',
                    args: { 'id': ticketid },
                    done: function (data) {

                        if (data && typeof(data) == 'object') {
                            var $ticket = $('#ticket-' + ticketid);

                            $ticket.find('val[key="available"]').html(data.available);
                            $ticket.find('val[key="usertickets"]').html(data.usertickets);

                            if (data.available <= 0 || data.byuser <= data.usertickets) {
                                $ticket.find('[data-action="buy"]').hide();
                            }
                        } else {
                            Notification.alert('', s['notbuy'], s['continue']);
                        }

                    },
                    fail: function (e) {
                        console.log('Error buy ticket');
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
