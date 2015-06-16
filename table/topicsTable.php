<?php

global $CFG;

require_once($CFG->dirroot . '/mod/socialwiki/locallib.php');
require_once($CFG->dirroot . '/mod/socialwiki/table/table.php');

class TopicsTable extends socialwiki_table {

    private $tlist;

    public function __construct($uid, $swid, $list, $headers) {
        parent::__construct($uid, $swid, $headers);
        $this->tlist = $list;
    }

    public static function makeTopicsTable($uid, $swid) {
        $topics = socialwiki_get_topics($swid);

        if (empty($topics)) {
            return null;
        }
        $headers = TopicsTable::get_headers();

        return new TopicsTable($uid, $swid, $topics, $headers);
    }

    public static function get_headers() { //TODO: make configurable
        $col_names = array(
            "Title",
            "Number of Versions",
            "Number of Views",
            "Number of Likes",
        );
        return $col_names;
    }

    protected function get_table_data() {
        Global $COURSE, $PAGE;

        $table = array();

        foreach ($this->tlist as $title => $data) {
            $titlelink = '<a href="search.php?searchstring=' . $title . '&courseid=' . $COURSE->id . '&cmid=' . $PAGE->cm->id . '&exact=1&option=1">' . $title . '</a>';

            $row = array(
                "Title" => $titlelink,
                "Number of Versions" => $data["Versions"],
                "Number of Views" => $data["Views"],
                "Number of Likes" => $data["Likes"],
            );

            $table[] = $row; //add row to table
        }

        return $table;
    }
}
