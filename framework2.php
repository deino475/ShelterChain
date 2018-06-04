<?php
class Response {
	
	public static function render_template($file_name, $data = []) {
		include 'templates/'.$file_name.'.php' ;
	}

	public static function render_xml($xml_info) {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/xml;');
		echo $xml_info;
	}

	public static function render_json($data) {
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/json;');
		echo json_encode($data);
	}

	public static function render_csv($data, $name = 'data') {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$name.'.csv');
		$output = fopen('php://output', 'w');
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
	}

	public function render_text($text) {
		echo $text;
	}

	public function redirect_to($name) {
		header('Location: ?m=/'.$name);
	}
}


class Session {
	
	public static function start() {
		session_start();
	}

	public static function get($name) {
		return $_SESSION[$name];
	}

	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	public static function end() {
		session_destroy();
	}
}

class Request {
	
	public static function get_request() { 
		return $_SERVER['REQUEST_METHOD']; 
	}
	
	public static function is_get() { 
		return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false; 
	}
	
	public static function is_post() { 
		return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false; 
	}
	
	public static function is_head() { 
		return $_SERVER['REQUEST_METHOD'] == 'HEAD' ? true : false; 
	}
	
	public static function is_put() { 
		return $_SERVER['REQUEST_METHOD'] == 'PUT' ? true : false; 
	}
	
	public static function is_delete() { 
		return $_SERVER['REQUEST_METHOD'] == 'DELETE' ? true : false; 
	}
}

class Lily {

	private $event_array = array();

	public function route($name, $function) {
		$regex_rule = str_replace('<string>','[a-zA-Z0-9_][a-zA-Z0-9_]*', $name);
		$regex_rule = str_replace('<int>', '[0-9]+', $regex_rule);
		$regex_rule = str_replace("/", "\/", $regex_rule);
		$regex_rule = "/^$regex_rule$/";
		$this->event_array[$regex_rule] = $function;
	}

	public function start($name, $data = []) {
		if (strlen($name) <= 1) {
			$this->event_array['/^\/index$/']();
			return;
		}
		foreach ($this->event_array as $event_rule => $event_response) {
			if (preg_match($event_rule, $name, $match)) {
				$data = explode('/', $name);
				$this->event_array[$event_rule]($data);
				return;
			}
		}
	}

	public function model($name) {
		include 'models/' . $name . '.php';
		return new $name;
	}
}