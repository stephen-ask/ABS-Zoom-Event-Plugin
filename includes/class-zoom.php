<?php 

$import_files = array(
	PLUGIN_DIR .'Zoom/index.php',
	PLUGIN_DIR .'Zoom/Client.php',
	PLUGIN_DIR .'Zoom/Meeting.php',
	PLUGIN_DIR .'Zoom/Config.php',
	PLUGIN_DIR .'Zoom/Tools.php',
	PLUGIN_DIR .'base/base.php',
);

array_filter($import_files, function($value, $key){
	if(file_exists($value)) {
		require_once $value;
	}
}, ARRAY_FILTER_USE_BOTH); 

use Zoom\Meeting;
use Zoom\Config;

class Zoom_Meeting extends Zoom\Base\base {
	public $meeting, $data, $meeting_id, $config;

	function __construct() {
		$this->meeting = new Meeting();
	} 
	public function create_meeting($data) {
		return $this->meeting->create($data);
		
	}
	public function list_meeting() {
		return $this->meeting->list;
	}
	public function update_meeting($data, $id) {
		return $this->meeting->update($data, $id);
	}
	public function delete_meeting($id) {
		return $this->meeting->delete($id);
	}
}

