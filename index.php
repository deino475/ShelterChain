<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include "framework2.php";
include "block.php";

$node = new Lily;
$node->route('/index', function($data = []) use ($node){
	response::redirect_to('dashboard');
});

$node->route('/dashboard', function($data = []) use ($node){
	response::render_template('header');
	response::render_template('dashboard');
	response::render_template('footer');
});

$node->route('/chain', function($data = []) use ($node){
	#Opens up blockchain file.
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);

	#If the blockchain file is empty, make a new blockchain file.
	if (sizeof($chain_data) == 0) { 
		$block = new Block('','','00000000000');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	#Saves blockchain information
	file_put_contents('blockchain.json', json_encode($chain_data));

	#Prints out blockchain file.
	response::render_json($chain_data);
});

$node->route('/join-peer', function($data = []) use ($node){
	header('Access-Control-Allow-Origin: *');
	$peer_data = json_decode(file_get_contents('peers.json'),true);
	$duplicate_peer = json_decode(file_get_contents('peers.json'),true);
	$new_peer = $_POST['peer'];
	if (!in_array($new_peer, $peer_data)) {
		array_push($peer_data, $new_peer);
		for ($i=0; $i < sizeof($duplicate_peer); $i++) { 
			echo $peer;
			$peer = $duplicate_peer[$i];
			$data = array(
				'peer' => $new_peer
			);

			$url = $peer . "/?r=/join-peer";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
		}
		file_put_contents("peers.json", json_encode($peer_data));
		echo "Peer Added.";
	}
	else {
		echo "Peer was already added to network.";
	}
});

$node->route('/mine', function($data = []) use ($node){
	header('Access-Control-Allow-Origin: *');
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','','00000000000');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	$data_to_edit = $end_block['data'];
	$previous_hash = $end_block['hash'];
	$time_stamp = $_POST['time_stamp'] ?: time();
	$data_to_edit[md5($_POST['org_name'])] = array($_POST['org_name'], $_POST['street_name'],$_POST['city_name'],$_POST['state_name'],$_POST['zip_code'],$_POST['available'],$_POST['pets'],$_POST['ada'],$_POST['lat'],$_POST['lng'],$time_stamp);
	$new_block = new Block($data_to_edit, $previous_hash, $time_stamp);
	array_push($chain_data, $end_block);
	array_push($chain_data, $new_block->export_block($json = false));
	file_put_contents('blockchain.json', json_encode($chain_data));	

	echo "Block mined";

	$peers = json_decode(file_get_contents('peers.json'),true);
	foreach ($peers as $peer) {
		$data = array(
			'org_name' => $_POST['org_name'],
			'street_name' => $_POST['street_name'],
			'city_name' => $_POST['city_name'],
			'state_name' => $_POST['state_name'],
			'zip_code' => $_POST['zip_code'],
			'available' => $_POST['available'],
			'pets' => $_POST['pets'],
			'ada' => $_POST['ada'],
			'lat' => $_POST['lat'],
			'lng' => $_POST['lng'],
			'time_stamp' => $time_stamp
		);

		$url = $peer . "/?r=/mine";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
	}
});

$node->route('/last-block', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','','00000000000');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	response::render_json($end_block);
});

$node->route('/last-block/data', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','','00000000000');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	response::render_json($end_block['data']);
});

$node->route('/nearest-shelter', function($data = []) use ($node){
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$address = search_for_address($_POST['Body']);
	if ($address == null) {
		echo '<Response><Message>Hello, this is a Hurricane Shelter Textbot, helping you find the nearest hurricane shelter. Please type your zip code.</Message></Response>';
	}
	else {
		$coords = get_coords($address);
		$closest_org = get_closest_org($coords['lat'], $coords['lng']);
		if ($closest_org == null) {
			echo "<Response><Message>An error occurred. Please try again.</Message></Response>";
		}
		else {
			echo return_message($closest_org['shelter'],$closest_org['address']);
		}	
	}
});


function search_for_address($text) {
	$address = null;
	$words = explode(" ", $text);
	for ($i = 0; $i < sizeof($text); $i++) {
		$word = preg_replace("/[^a-zA-Z 0-9]+/", "", $words[$i]);
		if (strlen($word) == '5') {
			if (is_numeric($word)) {
				$address = $word;
				return $address;
			}
		}
	}
	return $address;
}

function get_coords($address) {
	$coords = array();
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key=AIzaSyD2uPNooAUapFrrB8gRkN3tsPj4kRlgKgw';
	$data = json_decode(file_get_contents($url),true);
	$coords['lat'] = $data['results'][0]['geometry']['location']['lat'];
	$coords['lng'] = $data['results'][0]['geometry']['location']['lng'];
	return $coords;
}

function get_closest_org($lat, $lng) {
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','','00000000000');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	if (sizeof($end_block) == 0) {
		return null;
	}
	$nearest_data = null;
	$shortest_distance = 100000;
	foreach ($end_block['data'] as $shelter) {
		if (sqrt(pow(($lat - $shelter[8]), 2) + pow(($lng - $shelter[9]), 2)) < $shortest_distance) {
			$shortest_distance = sqrt(pow(($lat - $shelter[8]), 2) + pow(($lng - $shelter[9]), 2));
			$nearest_data = $shelter;
		}
	}
	return $nearest_data;
}

function return_message ($org_name, $address) {
	return "<Response><Message>The nearest hurricane shelter is $org_name at $address. For more information, go to florenceresponse.org.</Message></Response>";
}


$node->start($_GET['r']);
