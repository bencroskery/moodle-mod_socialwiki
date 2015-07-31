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
 * Defines restore_socialwiki_stepslib class.
 * 
 * @package   mod_socialwiki
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one wiki activity
 * 
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_socialwiki_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('socialwiki', '/activity/socialwiki');
        if ($userinfo) {
            $paths[] = new restore_path_element('socialwiki_subwiki',
                    '/activity/socialwiki/subwikis/subwiki');
            $paths[] = new restore_path_element('socialwiki_page',
                    '/activity/socialwiki/subwikis/subwiki/pages/page');
            $paths[] = new restore_path_element('socialwiki_tag',
                    '/activity/socialwiki/subwikis/subwiki/pages/page/tags/tag');
            $paths[] = new restore_path_element('socialwiki_like',
                    '/activity/socialwiki/subwikis/subwiki/likes/like');
            $paths[] = new restore_path_element('socialwiki_follow',
                    '/activity/socialwiki/subwikis/subwiki/follows/follow');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    protected function process_socialwiki($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->editbegin = $this->apply_date_offset($data->editbegin);
        $data->editend = $this->apply_date_offset($data->editend);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the wiki record.
        $newitemid = $DB->insert_record('socialwiki', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    protected function process_socialwiki_subwiki($data) {
        global $DB;
        $data = (object) $data;
        $oldid = $data->id;
        $data->wikiid = $this->get_new_parentid('socialwiki');
        $data->groupid = $this->get_mappingid('group', $data->groupid);
        $data->userid = $this->get_mappingid('user', $data->userid);

        $newitemid = $DB->insert_record('socialwiki_subwikis', $data);
        $this->set_mapping('socialwiki_subwiki', $oldid, $newitemid);
    }

    protected function process_socialwiki_page($data) {
        global $DB, $USER;
        $data = (object) $data;
        $oldid = $data->id;
        $data->subwikiid = $this->get_new_parentid('socialwiki_subwiki');
        $data->userid = $USER->id;
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->lftid = $this->get_mappingid('socialwiki_page', $data->lftid);
        $data->rgtid = $this->get_mappingid('socialwiki_page', $data->rgtid);

        $newitemid = $DB->insert_record('socialwiki_pages', $data);
        $this->set_mapping('socialwiki_page', $oldid, $newitemid, true); // There are files related to this.
    }

    protected function process_socialwiki_like($data) {
        global $DB;
        $data = (object) $data;
        $oldid = $data->id;
        $data->subwikiid = $this->get_new_parentid('socialwiki_subwiki');
        $data->pageid = $this->get_mappingid('socialwiki_page', $data->pageid);
        $data->userid = $this->get_mappingid('user', $data->userid);

        $newitemid = $DB->insert_record('socialwiki_likes', $data);
    }

    protected function process_socialwiki_follow($data) {
        global $DB;
        $data = (object) $data;
        $oldid = $data->id;
        $data->subwikiid = $this->get_new_parentid('socialwiki_subwiki');
        $data->userfromid = $this->get_mappingid('user', $data->userfromid);
        $data->usertoid = $this->get_mappingid('user', $data->usertoid);

        $newitemid = $DB->insert_record('socialwiki_follows', $data);
    }

    protected function process_socialwiki_tag($data) {
        global $CFG;
        $data = (object) $data;
        $oldid = $data->id;

        if (empty($CFG->usetags)) { // Tags disabled in server, nothing to process.
            return;
        }

        $tag = $data->rawname;
        $itemid = $this->get_new_parentid('socialwiki_page');
        tag_set_add('socialwiki_pages', $itemid, $tag);
    }

    protected function after_execute() {
        // Add wiki related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_socialwiki', 'intro', null);
        $this->add_related_files('mod_socialwiki', 'attachments', 'socialwiki_page');
    }

}
