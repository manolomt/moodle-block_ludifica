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
 * Javascript to badges manage.
 *
 * @module    block/ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery',
        'core/str',
        'core/modal_factory',
        'core/log',
        'core/templates'],
function($,
        Str,
        ModalFactory,
        Log,
        Templates) {

    // Load used strings.
    var strings = [
        {key: 'badgelinkcopiedtoclipboard', component: 'block_ludifica'}
    ];

    var s = [];

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

    /**
     * Initialise all for badges.
     *
     * @param {Array} networks
     *
     */
    var init = function(networks) {

        $('.openshare').on('click', function() {
            var $content = $('.share_badge_modal');
            var $title = $content.attr('title');

            var badge = {
                "id": $(this).data('badgeid')
            };

            var $badge = $('#badge-' + badge.id);

            badge.thumbnail = $badge.find('.picture-box img').attr('src');
            badge.url = $badge.find('.shareurl').attr('href');
            badge.name = $badge.data('name');
            badge.year = $badge.data('year');
            badge.month = $badge.data('month');
            badge.uniquehash = $badge.data('uniquehash');

            var badgedata = {
                "networks": [],
                "thumbnailurl": badge.thumbnail,
                "badge": badge
            };

            networks.forEach(network => {
                let networkurl = network.url;
                // Change the "=" for the standard URL code.
                let badgeencode = badge.url.replace(/[=]/g, '%3d');

                networkurl = networkurl.replace(/{name}/gi, encodeURI(badge.name));
                networkurl = networkurl.replace(/{url}/gi, badgeencode);
                networkurl = networkurl.replace(/{badgeyear}/gi, badge.year);
                networkurl = networkurl.replace(/{badgemonth}/gi, badge.month);
                networkurl = networkurl.replace(/{badgeid}/gi, badge.uniquehash);

                badgedata.networks[badgedata.networks.length] = {
                    "url": networkurl,
                    "icon": network.icon,
                    "iconsource": "core",
                    "name": network.icon
                };
            });

            Templates.render('block_ludifica/sharebadge', badgedata).then(function(content) {
                ModalFactory.create({
                    title: $title,
                    body: content
                }).done(function(modal) {
                    var $modalBody = modal.getBody();

                    $modalBody.find('input[name="badgelink"]').on('click', function() {

                        var $input = $(this);
                        $input.select();
                        document.execCommand("copy");

                        var $msg = $('<div class="msg-badgelink-copy">' + s.badgelinkcopiedtoclipboard + '</div>');

                        $input.parent().append($msg);
                        window.setTimeout(function() {
                            $msg.remove();
                        }, 1600);
                    });

                    return modal.show();
                }).fail(function(e) {
                    Log.debug('Error creating modal share buttons');
                    Log.debug(e);
                });
            });
            return true;
        });
    };

    return {
        init: init
    };

});