<?php 

/**
 * Plugin Name: ABS Zoom Event Plugin
 * Version: 1.0
 * Description: Zoom Event Plugin developed by Absolute App Labs
 * Author: ASK <stepheninbox0@gmail.com>
 */

define('PLUGIN_DIR', plugin_dir_path( __FILE__ ));

if(!class_exists('Zoom_Meeting')) {
	require_once PLUGIN_DIR.'includes/class-zoom.php';
}


if(!class_exists('Zoom_Meeting')) {
	require_once PLUGIN_DIR.'includes/class-zoom.php';
}

class zoom extends Zoom_Meeting {

	private $loader;

	protected $plugin_slug;

	protected $version;

	function __construct() {
		parent::__construct();
		$this->register_cpt();
		$this->init_rest_endpoints();	
	}
	public function init_rest_endpoints(){		
		add_action('rest_api_init', function(){
			register_rest_route('v1/zoom', '/create_meeting', array(
				'methods' => 'POST',
				'callback' => [$this, 'create_an_meeting'],
				'permission_callback' => [$this, 'validate'],
			));
			register_rest_route('v1/zoom', '/list', array(
				'methods' => 'POST',
				'callback' => [$this, 'list_meeting'],
				'permission_callback' => [$this, 'validate'],  
			));
			register_rest_route('v1/zoom', '/update', array(
				'methods' => 'POST',
				'callback' => [$this, 'update_meeting'],
				'permission_callback' => [$this, 'validate'],  
			));
			register_rest_route('v1/zoom', '/delete', array(
				'methods' => 'POST',
				'callback' => [$this, 'delete_meeting'],
				'permission_callback' => [$this, 'validate'],  
			));
		});
		
	}
	public function validate($request) {
		
		if(is_user_admin()) {
			return false;
		}

		$parameters = $request->get_params();
		$topic = sanitize_text_field(@$parameters['title']);
		
		$user = $this->rest_is_user_logged_in($request);

		$error_msgs = array(
			// 'user' => array (
			// 	'Authendication Error' , 
			// 	'Invalid User Account'
			// ),
			'topic' => array(
				'Input Error' , 
				'Invalid Topic'
			)
		);
		
		foreach( $error_msgs as $variable => $method_parameters) {
			foreach($method_parameters as $value) {
				if(empty($$variable) && is_array($method_parameters)) {
					return  $this->error_response($method_parameters[0], $method_parameters[1]);
				}
			}
		}
		
		return true;
	}

	public function error_response( $status, $msg ) {
		$error = new WP_Error();
	
		$error->add( $status, $msg );
		return $error;
	}

	public function create_an_meeting($request) {		
		$parameters = $request->get_params();

		$topic = sanitize_text_field(@$parameters['title']);
		$description = sanitize_textarea_field(@$parameters['description']) ? sanitize_textarea_field(@$parameters['description']) : 'description';
		$host_video = sanitize_text_field(@$parameters['host_video']) ? sanitize_text_field(@$parameters['host_video']) : false;
		$participant_video = sanitize_text_field(@$parameters['participant_video']) ? sanitize_text_field(@$parameters['participant_video']): true;
		$join_before_host = sanitize_text_field(@$parameters['join_before_host']) ? sanitize_text_field(@$parameters['join_before_host']) : true;
		$audio = sanitize_text_field(@$parameters['audio']) ? sanitize_text_field(@$parameters['audio']) : true;
		$approval_type = sanitize_text_field(@$parameters['join_before_host']) ? sanitize_text_field(@$parameters['join_before_host']) : 2;
		$waiting_room = sanitize_text_field(@$parameters['waiting_room']) ? sanitize_text_field(@$parameters['waiting_room']) : false;
		$start_time = sanitize_text_field(@$parameters['start_time']) ? sanitize_text_field(@$parameters['start_time']) : '';
		
		$data = [
			'topic' => $topic,
			'agenda' => $description,
			'start_time' => $start_time,
			'settings' => [
				'host_video' => $host_video,
				'participant_video' => $participant_video,
				'join_before_host' => $join_before_host,
				'audio' => $audio,
				'approval_type' => $approval_type,
				'waiting_room' => $waiting_room,
			],
		];
		
		$meeting = $this->create_meeting($data);
		echo $meeting ? 'success' : 'fails';
	}
}

new zoom;
