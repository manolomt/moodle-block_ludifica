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
 * Javascript to initialise the block.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/modal_factory'],
function($, ModalFactory) {

    var wwwroot = M.cfg.wwwroot;

    /**
     * Initialise all for the block.
     *
     */
    var init = function() {

        // Load default controls.

        // Modal.
        $('.block_ludifica-modal').each(function() {
            var $element = $(this);
            var title = $element.attr('title');
            var props = {
                title: title || '',
                body: $element.html()
            };

            if ($element.find('footer').length > 0) {
                props.footer = $element.find('footer');
            }

            if ($element.data('type')) {
                props.type = $element.data('type');
            }

            ModalFactory.create(props, $('.block_ludifica-modalcontroller[data-ref-id="' + $element.attr('id') + '"]'));
        });

        // Tabs.
        $('.block_ludifica-tabs').each(function() {
            var $tabs = $(this);
            var tabslist = [];

            $tabs.find('[data-ref]').each(function() {
                var $tab = $(this);
                tabslist.push($tab);

                $tab.on('click', function() {
                    tabslist.forEach(one => {
                        $(one.data('ref')).removeClass('active');
                    });

                    $tabs.find('.active[data-ref]').removeClass('active');

                    $tab.addClass('active');
                    $($tab.data('ref')).addClass('active');
                });

            });

        });

    };

    return {
        init: init
    };
});
