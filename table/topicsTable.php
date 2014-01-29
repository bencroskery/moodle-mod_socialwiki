<?php
global $CFG;

require_once($CFG->dirroot . '/mod/socialwiki/locallib.php');
require_once($CFG->dirroot . '/mod/socialwiki/sortableTable/sortableTable.php');
require_once($CFG->dirroot . '/mod/socialwiki/table/table.php');


class TopicsTable extends socialwiki_table {
	private $columns;
	private $uid;
	private $swid;

	private $col_names = array(
		"Title",
		// "Last Update",
		"Number of Versions",
		"Number of Views",
		"Number of Likes",
	);

	public function __construct($swid, $uid) {
		parent::__construct($this->col_names);
		$this->uid = $uid;
		$this->swid = $swid;
	}

	public function get_all_topics() {
		$topics = get_topics($this->swid);
		
		return $this->make_table($topics, "all_topic_table");
	}

	private function make_table($topics, $table_id) {
		$table = new SortableTable();

		foreach ($topics as $title => $data) {
			
			$row = array(
				"Title" => $title,
				"Number of Versions" => $data["Versions"],
				"Number of Views" => $data["Views"],
				"Number of Likes" => $data["Likes"],
			);

            $table->add_row($row);
		}

		$table_markup = "";
        
        $table_markup .= "<div class='yui3-js-endable'>";
        $table_markup .= $table->get_table($table_id);
        $table_markup .= "<div id='$table_id'></div>";
        $table_markup .= "</div>";

        return $table_markup;
	}


}