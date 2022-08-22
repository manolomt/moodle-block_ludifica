<?php

//namespace block_ludifica\form;

global $DB, $CFG;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class ludifica_form extends moodleform {

	public function definition() {

		            //parent::definition();

                            $mform = $this->_form;

                            $mform->addElement('header', 'general', get_string('pluginname', 'block_ludifica'));

       }
}
