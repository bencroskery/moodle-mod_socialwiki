<?php

public class SortableTable {
	$data = array();
	$columns = array();

	public function add_row($row) {
		$string = "{";
		$not_first = false;
		foreach($row as $column => $data) {
			if(!in_array($column, $this->columns)) {
				array_push($this->columns, $column);
			}
			if($not_first) {
				$string .= ", ";
			}
			$string .= $column.":\"".$data."\"";
			$not_first = true;
		}
		$string .= "}";
		array_push($this->data, $string);
	}

	public function draw_table() {
		$s = "YAHOO.util.Event.addListener(window, 'load', function() {\n";
		$s .= format_columns();
	}

	private function format_columns() {
		$string = "var columnDefs = [\n"

		foreach($this->columns as $column) {
			$string .= "{";
			$string .= "key:\""."\", sortable:true";
			$string .= "}\n";
		}
		$string .= "];\n";
		return $string;
	}

	private function format_data() {
		$not_first = false;
		$string  = "table = {" .
			"data: [";
			foreach ($data as $value) {
				if($not_first) {
					$string .= ", ";
				}

				$string .= "{".$value."}";

				$not_first = true;
			}
		$string .= "]}";
		return $string;
	}
}