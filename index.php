<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include "framework2.php";
include "block.php";

$node = new Lily;
$node->route('/index', function($data = []) use ($node){
	response::render_text('This is a Shelter Chain Node');
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
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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

$node->start($_GET['r']);
