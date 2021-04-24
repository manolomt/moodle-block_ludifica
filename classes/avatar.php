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
 * Class to manage the avatar information.
 *
 * @package   block_ludifica
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_ludifica;
defined('MOODLE_INTERNAL') || die();


/**
 * Avatar info.
 *
 * @copyright 2021 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class avatar {

    /**
     * var \stdClass Info about the avatar.
     */
    private $data;

    public static $DEFAULT_TYPE = 'normal';

    /**
     * Class constructor.
     */
    public function __construct($avatar = null) {
        global $USER, $DB;

        $this->data = null;

        if ($avatar) {

            if (is_object($avatar) && property_exists($avatar, 'id')) {
                $this->data = $avatar;
            } else {
                $this->data = $DB->get_record('block_ludifica_avatars', array('id' => (int)$avatar));
            }
        }

        if (!$this->data) {
            throw new \moodle_exception('errornotavatardata', 'block_ludifica');
        }
    }

    public function get_uri($level = null) {

        if (empty($this->data->sources) || $level === null) {
            return self::get_busturi();
        }

        if (!is_int($level)) {
            $level = 0;
        }

        $levels = controller::get_levels();

        $uris = explode("\n", $this->data->sources);

        $uri = '';
        if (count($uris) > $level) {
            $uri = $uris[$level];
        } else {
            // Return the last avatar available URI if not exists an Avatar URI in the requested level.
            $uri = end($uris);
        }

        if (strpos($uri, '{') !== false) {
            $uri = str_replace('{name}', $this->data->name, $uri);
            $uri = str_replace('{level}', $level, $uri);
            $uri = str_replace('{levelname}', $level < count($levels) ? $levels[$level]->name : '', $uri);
        }

        return $uri;
    }

    public function get_busturi() {

        $syscontext = \context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($syscontext->id, 'block_ludifica', 'avatarbust', $this->data->id);

        foreach ($files as $file) {
            $filename = $file->get_filename();

            if (!empty($filename) && $filename != '.') {
                $path = '/' . implode('/', array($file->get_contextid(),
                                                    'block_ludifica',
                                                    'avatarbust',
                                                    $file->get_itemid() . $file->get_filepath() . $filename));

                return \moodle_url::make_file_url('/pluginfile.php', $path);

                // Only one image by avatar.
                break;
            }
        }

        return self::default_avatar();
    }

    public static function default_avatar() {
        global $OUTPUT;

        return $OUTPUT->image_url('defaultavatar', 'block_ludifica');
    }

    /**
     * List the available avatar types.
     */
    public static function get_types() {
        return array(self::$DEFAULT_TYPE => get_string('avatartype_normal', 'block_ludifica'),
                    'user' => get_string('avatartype_user', 'block_ludifica'));
    }

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