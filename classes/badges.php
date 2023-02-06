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
 * Class to manage the ticket information.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;

/**
 * Badge info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badge extends entity {

    /**
     * @var string Default badge type.
     */
    public static $defaulttype = 'normal';

    /**
     * Class constructor.
     *
     * @param int|object $badge Current badge data or id.
     */
    public function __construct($badge = null) {
        global $DB;

        $this->data = null;

        if ($badge) {

            if (is_object($badge) && property_exists($badge, 'id')) {
                $this->data = $badge;
            } else {
                $this->data = $DB->get_record('block_ludifica_tickets', array('id' => (int)$badge));
            }
        }

        if (!$this->data) {
            throw new \moodle_exception('errornotticketdata', 'block_ludifica');
        }
    }

    /**
     * Get the preisualization image.
     *
     * @return string Image URI.
     */
    public function get_thumbnail() {

        $uri = '';
        $syscontext = \context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'ticket', $this->data->id);
        foreach ($files as $file) {
            $filename = $file->get_filename();

            if (!empty($filename) && $filename != '.') {
                $path = '/' . implode('/', array($file->get_contextid(),
                                                    'block_ludifica',
                                                    'ticket',
                                                    $file->get_itemid() . $file->get_filepath() . $filename));

                return \moodle_url::make_file_url('/pluginfile.php', $path);

                // Only one image by badge.
                break;
            }
        }

        return $uri;
    }

}
