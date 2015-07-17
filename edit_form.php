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
 * This file contains all necessary code to define and process an edit form
 *
 * @package mod_socialwiki
 * @copyright 2009 Marc Alier, Jordi Piguillem marc.alier@upc.edu
 * @copyright 2009 Universitat Politecnica de Catalunya http://www.upc.edu
 *
 * @author Josep Arus
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');

class mod_socialwiki_edit_form extends moodleform {

    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('editor', 'newcontent_editor', null, null, page_socialwiki_edit::$attachmentoptions);
        $mform->setType('newcontent_editor', PARAM_RAW); // Processed by trust text or cleaned before the display.

        $buttongroup = array();
        $buttongroup[] = $mform->createElement('submit', 'editoption', get_string('save', 'socialwiki'), array('id' => 'save'));
        $buttongroup[] = $mform->createElement('submit', 'editoption', get_string('cancel'), array('id' => 'cancel'));

        $mform->addGroup($buttongroup, 'buttonar', '', array(' '), false);
    }
}