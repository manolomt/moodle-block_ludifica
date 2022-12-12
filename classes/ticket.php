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
defined('MOODLE_INTERNAL') || die();


/**
 * Ticket info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ticket {

    /**
     * var \stdClass Info about the ticket.
     */
    private $data;

    /**
     * var string Default ticket type.
     */
    public static $DEFAULT_TYPE = 'normal';

    /**
     * Class constructor.
     *
     * @param $ticket
     */
    public function __construct($ticket = null) {
        global $DB;

        $this->data = null;

        if ($ticket) {

            if (is_object($ticket) && property_exists($ticket, 'id')) {
                $this->data = $ticket;
            } else {
                $this->data = $DB->get_record('block_ludifica_tickets', array('id' => (int)$ticket));
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

                // Only one image by ticket.
                break;
            }
        }

        return $uri;
    }

    /**
     * List the available ticket types.
     *
     * @return array Tickets type list.
     */
    public static function get_types() {
        return array('default' => get_string('ticketstype_default', 'block_ludifica'));
    }

    /**
     * Magic get function.
     *
     * @param string Property name.
     * @return mixed Name property value.
     */
    public function __get($name) {
        if (property_exists($this, $name)){
            return $this->$name;
        } else if (property_exists($this->data, $name)){
            return $this->data->$name;
        } else if(method_exists($this, 'get_' . $name)) {
            return call_user_func(array($this, 'get_' . $name));
        } else {
            throw new \Exception('propertie_or_method_not_found: ' . get_class($this) . '->'. $name);
        }
    }

    /**
     * Magic ser function.
     *
     * @param string $name Property name.
     * @param mixed $value Property new value.
     */
    public function __set($name, $value) {
        if (property_exists($this, $name)){
            $this->$name = $value;
        } else if (property_exists($this->data, $name)){
            $this->data->$name = $value;
        } else if(method_exists($this, 'set_' . $name)) {
            return call_user_func(array($this, 'set_' . $name), $value);
        } else {
            throw new \Exception('propertie_or_method_not_found: ' . get_class($this) . '->'. $name);
        }
    }
}