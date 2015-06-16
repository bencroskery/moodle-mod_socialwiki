<?php

abstract class socialwiki_table {

    protected $uid; //uid of user viewing
    protected $swid;
    protected $headers;
    
    /**
     * creates a table with the given headers, current uid (userid), subwikiid
     */
    public function __construct($u, $s, $h) {
        Global $PAGE;
        $this->uid = $u;
        $this->swid = $s;
        $this->headers = $h;
    }

    abstract protected function get_table_data();

    /**
     * gets the table in HTML format (string)
     */
    public function get_as_HTML($tableid = 'a_table') {

        $t = "<table id=" . $tableid . " class='datatable'>";
        $tabledata = $this->get_table_data();
        //headers
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
}
