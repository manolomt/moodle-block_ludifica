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
 * @module    block/ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/modal_factory', 'block_ludifica/alertc'],
function($, str, ModalFactory, Alertc, Log) {

    // Load strings.
    var strings = [];
    strings.push({key: 'badgelinkcopiedtoclipboard', component: 'block_ludifica'});

    var s = [];

    if (strings.length > 0) {

        strings.forEach(one => {
            s[one.key] = one.key;
        });

        str.get_strings(strings).then(function(results) {
            var pos = 0;
            strings.forEach(one => {
                s[one.key] = results[pos];
                pos++;
            });
            return true;
        }).fail(function(e) {
            Log.debug('Error loading strings');
            Log.debug(e);
        });
    }
    // End of Load strings.

    // Based in https://philipwalton.com/articles/responsive-components-a-solution-to-the-container-queries-problem/
    var resizeobserver = function() {

        // Find all elements with the `data-observe-resizes` attribute
        // and start observing them.
        $('[data-observe-resizes]').each(function() {

            // Only run if ResizeObserver is supported.
            if ('ResizeObserver' in self) {
                // Create a single ResizeObserver instance to handle all
                // container elements. The instance is created with a callback,
                // which is invoked as soon as an element is observed as well
                // as any time that element's size changes.
                let ro = new ResizeObserver(function(entries) {

                    // Default breakpoints that should apply to all observed
                    // elements that don't define their own custom breakpoints.
                    var defaultBreakpoints = {XS: 0, SM: 384, MD: 576, LG: 768, XL: 960};

                    entries.forEach(function(entry) {

                        // If breakpoints are defined on the observed element,
                        // use them. Otherwise use the defaults.
                        var breakpoints = entry.target.dataset.breakpoints ?
                            JSON.parse(entry.target.dataset.breakpoints) :
                            defaultBreakpoints;

                        // Update the matching breakpoints on the observed element.
                        Object.keys(breakpoints).forEach(function(breakpoint) {
                            var minWidth = breakpoints[breakpoint];
                            if (entry.contentRect.width >= minWidth) {
                                entry.target.classList.add(breakpoint);
                            } else {
                                entry.target.classList.remove(breakpoint);
                            }
                        });
                    });
                });

                ro.observe(this);
            }
        });
    };

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

        $('body').on('updatefailed', '[data-inplaceeditable]', function(e) {
            var exception = e.exception; // The exception object returned by the callback.
            e.preventDefault(); // This will prevent default error dialogue.

            if (exception.errorcode == "nicknameexists") {
                Alertc.error(exception.message);

                // Cleared the error code because the event is twice called.
                exception.errorcode = null;
            }
        });

        // Share badge buttons.
        $('.openshare').on('click', function() {
            var $content = $('.share_badge_modal');
            var $title = $content.attr('title');

            ModalFactory.create({
                title: $title,
                body: $content.html(),
            }).then(function(modal) {
                return modal.show().then(function() {
                    $('input[name="badgelink"]').on('click', function() {
                        var $input = $(this);
                        $input.select();
                        document.execCommand("copy");

                        var $msg = $('<div class="msg-badgelink-copy">' + s.badgelinkcopiedtoclipboard + '</div>');

                        $input.parent().append($msg);
                        window.setTimeout(function() {
                            $msg.remove();
                        }, 1600);
                    });
                    return true;
                });
            }).fail(function(e) {
                Log.debug('Error creating modal share buttons');
                Log.debug(e);
            });
            return true;
        });
        // End of share badge buttons.

        resizeobserver();

    };

    return {
        init: init
    };

});
