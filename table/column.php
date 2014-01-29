<?php

class column {
	private $index;
	private $label;
	private $visible;

	public function __construct($index, $label, $visible = true) {
		$this->index = $index;
		$this->table_index = $tab_index;
		$this->label = $label;
		$this->visible = $visible;
	}

	public function get_index() {
		return $this->index;
	}

	public function get_label() {
		return $this->label;
	}

	public function is_visible() {
		return $this->visible;
	}

	public function set_visible($visible) {
		$this->visible = $visible;
	}

	public function toggle_visible() {
		$this->visible = !($this->visible);
	}
}