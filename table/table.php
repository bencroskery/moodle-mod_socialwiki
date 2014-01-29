<?php

abstract class socialwiki_table {

	public function __construct($col_names) {
		// $this->initTable($col_names);
	}

	// public function initTable($col_names) {
	// 	for ($i = 0; $i<$col_names.count(); $i++) {
	// 		array_push($columns,
	// 			new column($i, $label)
	// 		);
	// 	}
	// }

	// public function has_column($col_name) {
	// 	foreach($columns as $c) {
	// 		if ($c->get_label === $col_name) {
	// 			return true;
	// 		}
	// 	}
	// 	return false;
	// }

	// public function get_column($col_name) {
	// 	foreach($columns as $c) {
	// 		if($c->get_label === $col_name) {
	// 			return $c;
	// 		}
	// 	}
	// 	return null;
	// }

	// public function toggle_visibility($col_name) {
	// 	$col = $this->get_column($col_name)
	// 	if (is_set($col)) {
	// 		$col->toggle_visibility();
	// 	}
	// }

	// public function set_visibility($col_name, $visibility) {
	// 	$col = $this->get_column($col_name)
	// 	if (is_set($col)) {
	// 		$col->set_visible($visibility);
	// 	}
	// } 
}	
