<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include "framework2.php";
include "block.php";

$node = new Lily;
$node->route('/index', function($data = []) use ($node){
	response::render_text('This is a Shelter Chain Node');
});

$node->route('/chain', function($data = []) use ($node){
	#Opens up blockchain file.
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);

	#If the blockchain file is empty, make a new blockchain file.
	if (sizeof($chain_data) == 0) { 
		$block = new Block('','');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	#Saves blockchain information
	file_put_contents('blockchain.json', json_encode($chain_data));

	#Prints out blockchain file.
	response::render_json($chain_data);
});

$node->route('/mine', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}

	if (sizeof($chain_data) != $_POST['chain_size']) {
		$end_block = array_pop($chain_data);
		$data_to_edit = $end_block['data'];
		$previous_hash = $end_block['hash'];

		$data_to_edit[$_POST['shelter_id']] = array($_POST['org_name'], $_POST['street_name'],$_POST['city_name'],$_POST['state_name'],$_POST['zip_code'],$_POST['available'],$_POST['pets'],$_POST['ada'],$_POST['lat'],$_POST['lng']);
		$new_block = new Block($data_to_edit, $previous_hash);
		array_push($chain_data, $end_block);
		array_push($chain_data, $new_block->export_block($json = false));
		file_put_contents('blockchain.json', json_encode($chain_data));	

		#Send Block Information to all of the Peers in the network.
		$peers = json_decode(file_get_contents('peers.json'),true);
		foreach ($peers as $peer) {
			$data = array(
				'shelter_id' => $_POST['shelter_id'],
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
				'chain_size' => sizeof($chain_data)
			);

			$url = $peer . "/?r=/mine";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
		}	
	}
});

$node->route('/test-mine', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	$data_to_edit = $end_block['data'];
	$previous_hash = $end_block['hash'];

	$data_to_edit['nila'] = array(1,2,3,4,5,6,7); 
	$new_block = new Block($data_to_edit, $previous_hash);
	array_push($chain_data, $end_block);
	array_push($chain_data, $new_block->export_block($json = false));
	file_put_contents('blockchain.json', json_encode($chain_data));	
});

$node->route('/last-block', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	response::render_json($end_block);
});

$node->route('/last-block/data', function($data = []) use ($node){
	$chain_data = json_decode(file_get_contents('blockchain.json'),true);
	if (sizeof($chain_data) == 0) {
		$block = new Block('','');
		$block->generate_genesis_block();
		array_push($chain_data, $block->export_block($json = false));
	}
	$end_block = array_pop($chain_data);
	response::render_json($end_block['data']);
});



$node->start($_GET['r']);

#id
#name of organization
#street name
#city
#state
#zip code
#available
#pets
#disability accessible
#lat
#lng
#chain_size