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

abstract class socialwiki_table {

    protected $uid; // UID of user viewing.
    protected $swid;
    protected $headers;

    /**
     * creates a table with the given headers, current uid (userid), subwikiid
     */
    public function __construct($u, $s, $h) {
        $this->uid = $u;
        $this->swid = $s;
        $this->headers = self::getheaders($h);
    }

    abstract protected function get_table_data();

    /**
     * gets the table in HTML format (string)
     */
    public function get_as_html($tableid = 'a_table') {

        $t = "<table id=" . $tableid . " class='datatable'>";
        $tabledata = $this->get_table_data();
        // Headers.
        $t .= "<thead><tr>";
        foreach ($this->headers as $h) {
            $t .= "<th>" . $h . "</th>";
        }
        $t .= "</tr></thead><tbody>";

        foreach ($tabledata as $row) {
            $t .= "<tr>";
            foreach ($row as $k => $val) {
                $t .= "<td>" . $val . "</td>";
            }
            $t .= "</tr>";
        }

        $t .= "</tbody></table>";
        return $t;
    }

    public static function getheaders($type) {
        switch ($type) {
            case "version":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('contributors', 'socialwiki'),
                    get_string('updated', 'socialwiki'),
                    get_string('likes', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('favourite', 'socialwiki'),
                    get_string('popularity', 'socialwiki'),
                    get_string('likesim', 'socialwiki'),
                    get_string('followsim', 'socialwiki'),
                    get_string('networkdistance', 'socialwiki')
                );
            case "mystuff":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('contributors', 'socialwiki'),
                    get_string('updated', 'socialwiki'),
                    get_string('likes', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('favourite', 'socialwiki')
                );
            case "topics":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('versions', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('likes', 'socialwiki')
                );
            case "user":
                return array(
                    get_string('name', 'socialwiki'),
                    get_string('popularity', 'socialwiki'),
                    get_string('likesim', 'socialwiki'),
                    get_string('followsim', 'socialwiki'),
                    get_string('networkdistance', 'socialwiki')
                );
            default:
                return array('error in getheaders: ' . $type);
        }
    }
}