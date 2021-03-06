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

defined('MOODLE_INTERNAL') || die();

/**
 * assign module data generator class
 *
 * @package   mod_socialwiki
 * @category  test
 * @copyright 2016 NMAI-lab
 *
 * @author Ben Croskery
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_socialwiki_generator extends testing_module_generator
{

    public function create_instance($record = null, array $options = null) {
        $record = (object)(array)$record;

        $defaultsettings = array(
            'wikimode'      => 'collaborative',
            'defaultformat' => 'html',
            'forceformat'   => 0,
            'style'         => 'classic',
        );

        foreach ($defaultsettings as $name => $value) {
            if (!isset($record->{$name})) {
                $record->{$name} = $value;
            }
        }

        $record->defaultformat = strtolower($record->defaultformat);

        return parent::create_instance($record, (array)$options);
    }
}
