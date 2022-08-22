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

require_once('../../config.php');

global $DB, $CFG;

foreach($_POST as $form_variable_key => $form_variable_value) {
        if(strcmp('sesskey', $form_variable_key) != 0 AND strcmp('submit', $form_variable_key) != 0 AND strcmp('_qf__ludifica_form', $form_variable_key) != 0) {
           set_config($form_variable_key, $form_variable_value, 'block_ludifica');	
	}
}

$url_to_go = $_SERVER['HTTP_REFERER'].'#ludifica';

header("Location: $url_to_go");
exit();

?>
