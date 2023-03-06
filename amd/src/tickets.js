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
 * @module    block/ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery',
        'core/notification',
        'core/str',
        'core/ajax',
        'block_ludifica/alertc',
        'block_ludifica/player',
        'core/modal_factory',
        'core/log'],
function($,
        Notification,
        Str,
        Ajax,
        Alertc,
        Player,
        ModalFactory,
        Log) {

    var s = [];
    var contacts = null;

    /**
     * Show the give view.
     *
     * @param {Integer} ticketid
     */
    function giveView(ticketid) {

        var $content = $('<div></div>');
        var $contactslist = $('<select id="block_ludifica_ticket_given_contact" class="form-control"></select>');

        $content.append('<h4>' + s.giveticketmessage + '</h4>');

        contacts.forEach(contact => {
            $contactslist.append('<option value="' + contact.id + '">' + contact.name + '</option>');
        });

        $content.append($contactslist);

        Notification.confirm(s.giveticket, $content.html(), s.give, s.cancel, function() {
            var contactid = parseInt($('#block_ludifica_ticket_given_contact').val());

            // Buy the ticket.
            Ajax.call([{
                methodname: 'block_ludifica_give_ticket',
                args: {'ticketid': ticketid, 'contactid': contactid},
                done: function(data) {

                    if (data) {
                        Alertc.success(s.given);
                        updateTicketData(ticketid);
                    } else {
                        Alertc.error(s.notgive);
                    }

                },
                fail: function(e) {
                    Alertc.error(e.message);
                    Log.debug(e);
                }
            }]);

        });
    }

    /**
     * Save the ticket data.
     *
     * @param {Integer} ticketid
     */
    function updateTicketData(ticketid) {
        Ajax.call([{
            methodname: 'block_ludifica_get_ticket',
            args: {'id': ticketid},
            done: function(data) {

                if (data && typeof data == 'object') {
                    var $ticket = $('#ticket-' + ticketid);
                    var $usertickets = $('#moreinfo-ticket-content-' + ticketid + ' .usertickets');
                    var $userticketslist = $usertickets.find('ul');
                    var userticketsavailable = 0;

                    $ticket.find('val[key="available"]').html(data.available);
                    $ticket.find('val[key="usertickets"]').html(data.usertickets.length);

                    $userticketslist.empty();
                    if (data.usertickets.length > 0) {
                        $ticket.find('[data-action="give"]').show();
                        $usertickets.show();

                        data.usertickets.forEach(one => {
                            var $tplitem = $('<li class="list-group-item"></li>');
                            var $tplusercode = $('<span class="usercode">' + one.usercode + ' </span>');

                            $tplitem.append($tplusercode);

                            if (one.timeused) {
                                $tplusercode.addClass('usercode-used');

                                var userdate = s.usedat.replace('USERDATE', one.timeusedformatted);
                                var $tpltimeused = $('<em> ' + userdate + '</em>');
                                $tplitem.append($tpltimeused);
                            }

                            $userticketslist.append($tplitem);
                            if (!one.timeused) {
                                userticketsavailable++;
                            }
                        });
                    } else {
                        $usertickets.hide();
                    }

                    if (userticketsavailable == 0) {
                        $ticket.find('[data-action="give"]').hide();
                    }

                    if (data.available <= 0 || data.byuser <= data.usertickets.length) {
                        $ticket.find('[data-action="buy"]').hide();
                    } else {
                        $ticket.find('[data-action="buy"]').show();
                    }
                }
            },
            fail: function(e) {
                Log.debug(e);
            }
        }]);
    }

    /**
     * Initialise all for tickets.
     *
     * @param {Integer} userid
     *
     */
    var init = function(userid) {

        // Load used strings.
        var strings = [
            {key: 'buyticket', component: 'block_ludifica'},
            {key: 'buyticketmessage', component: 'block_ludifica'},
            {key: 'buy', component: 'block_ludifica'},
            {key: 'notbuy', component: 'block_ludifica'},
            {key: 'give', component: 'block_ludifica'},
            {key: 'given', component: 'block_ludifica'},
            {key: 'notgive', component: 'block_ludifica'},
            {key: 'giveticket', component: 'block_ludifica'},
            {key: 'giveticketmessage', component: 'block_ludifica'},
            {key: 'notcontacts', component: 'block_ludifica'},
            {key: 'bought', component: 'block_ludifica'},
            {key: 'usedat', component: 'block_ludifica', param: 'USERDATE'},
            {key: 'cancel'},
            {key: 'continue'},
            {key: 'error'},
            {key: 'info'},
        ];
        strings.forEach(function(one) {
            s[one.key] = one.key;
        });

        Str.get_strings(strings).then(function(results) {
            results.forEach(function(value, index) {
                s[strings[index].key] = value;
            });

            return true;
        }).
        fail(function(e) {
            Log.debug('Error loading strings');
            Log.debug(e);
        });
        // End load used strings.

        // Buy.
        $('[data-action="buy"]').on('click', function() {
            var $element = $(this);
            var ticketid = $element.data('id');

            Notification.confirm(s.buyticket, s.buyticketmessage, s.buy, s.cancel, function() {

                // Buy the ticket.
                Ajax.call([{
                    methodname: 'block_ludifica_buy_ticket',
                    args: {'id': ticketid},
                    done: function(data) {

                        if (data) {
                            Alertc.success(s.bought);
                            updateTicketData(ticketid);
                            Player.reloadStats();

                        } else {
                            Alertc.error(s.notbuy);

                        }

                    },
                    fail: function(e) {
                        Log.debug('Error buy ticket');
                        Log.debug(e);
                    }
                }]);

            });

        });

        // Give a ticket.
        $('[data-action="give"]').on('click', function() {
            var $element = $(this);
            var ticketid = $element.data('id');

            // Request the user contacts list.
            if (!contacts) {
                Ajax.call([{
                    methodname: 'core_message_get_user_contacts',
                    args: {'userid': userid},
                    done: function(data) {

                        if (data && typeof data == 'object' && data.length > 0) {

                            contacts = [];
                            data.forEach(contact => {
                                if (!contact.isdeleted && !contact.isblocked && contact.iscontact) {
                                    contacts.push({'name': contact.fullname, 'id': contact.id});
                                }
                            });

                            giveView(ticketid);

                        } else {
                            Alertc.warning(s.notcontacts);
                        }

                    },
                    fail: function(e) {
                        Alertc.error(e.message);
                        Log.debug(e);
                    }
                }]);
            } else {
                giveView(ticketid);
            }

        });

        // More info.
        $('[data-action="showmore"]').on('click', function() {
            var $element = $(this);
            var ticketid = $element.data('id');
            var $more = $('#moreinfo-ticket-' + ticketid);
            var props = {
                title: $more.attr('title'),
                body: $more.html()
            };

            ModalFactory.create(props).then(function(modal) {
                modal.show();
                return true;
            }).
            fail(function(e) {
                Log.debug('Error creating modal showmore');
                Log.debug(e);
            });

        });

    };

    return {
        init: init
    };
});
