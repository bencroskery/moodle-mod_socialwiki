<?php
global $CFG;

require_once($CFG->dirroot . '/mod/socialwiki/locallib.php');
require_once($CFG->dirroot . '/mod/socialwiki/sortableTable/sortableTable.php');
require_once($CFG->dirroot . '/mod/socialwiki/table/table.php');


class UserTable extends socialwiki_table {
	private $columns;
	private $uid;
	private $swid;

	private $col_names = array(
		"Name",
		"Following?",
		"Number of Followers",
		"Like Similarity",
		"Follow Similarity",
	);

	public function __construct($swid, $uid) {
		parent::__construct($this->col_names);
		$this->uid = $uid;
		$this->swid = $swid;
	}

	public function get_all_users() {
		$ids = socialwiki_get_subwiki_users($this->swid);
		
		return $this->make_table($ids, "all_user_table");
	}

	private function make_table($ids, $table_id) {
		$table = new SortableTable();

		foreach ($ids as $id) {
			$user = socialwiki_get_user_info($id);
			$name = fullname($user);
			if(socialwiki_is_following($this->uid, $id, $this->swid)) {
				$following = "true";
			} else {
				$following = "false";
			}

			$number_of_users = socialwiki_get_user_count($this->swid);

			$peer = new peer($this->uid, $this->swid, $id, $number_of_users, null);

			$row = array(
				"Name" => $name,
				"Following?" => $following,
				"Number of Followers" => $peer->popularity*$number_of_users,
				"Like Similarity" => "$peer->likesim",
				"Follow Similarity" => "$peer->followsim",
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