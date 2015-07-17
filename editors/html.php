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
 * This file defines a simple editor
 *
 * @author Jordi Piguillem
 * @author Josep Arus
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package mod_socialwiki
 *
 */

/**
 * @TODO: Doc this function
 */
function socialwiki_print_editor_html($pageid, $content = null) {
    global $CFG, $OUTPUT;

    $action = $CFG->wwwroot . '/mod/socialwiki/edit.php?pageid=' . $pageid;

    echo '<form method="post" action="' . $action . '">';
    echo $OUTPUT->container(print_textarea(true, 20, 100, 0, 0, "newcontent",
            $content, 0, true, '', 'form-textarea-advanced'), 'socialwiki-editor');
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';
    $btnhtml = '<input class="socialwiki-button" type="submit" name="editoption" value="';
    echo $btnhtml . get_string('save', 'socialwiki') . '"/>';
    echo $btnhtml . get_string('cancel') . '" />';
    echo '</form>';
}
