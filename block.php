<?php 
class Block {
	public $time_stamp;
	public $data;
	public $previous_hash;
	public $hash;

	public function __construct($data, $previous_hash) {
		$this->data = $data;
		$this->time_stamp = time();
		$this->previous_hash = $previous_hash;
		$this->hash = $this->hash_data();
	}

	public function hash_data() {
		$nonce = 0;
		while (!is_numeric(substr(sha1($nonce . json_encode($this->data) . $this->time_stamp . $this->previous_hash),0,1) )) {
			$nonce++;
		}
		return sha1($nonce . json_encode($this->data) . $this->time_stamp . $this->previous_hash);
	}

	public function import_block($block_json) {
		$this->data = $block_json['data'];
		$this->hash = $block_json['hash'];
		$this->previous_hash = $block_json['previous_hash'];
		$this->time_stamp = $block_json['time_stamp'];
	}

	public function export_block($json = TRUE) {
		if ($json) {
			return json_encode(array('time_stamp' => $this->time_stamp, 'data' => $this->data, 'previous_hash' => $this->previous_hash, 'hash' => $this->hash));
		}
		return array('time_stamp' => $this->time_stamp, 'data' => $this->data, 'previous_hash' => $this->previous_hash, 'hash' => $this->hash);
	}

	public function generate_genesis_block() {
		$this->data = array();
		$this->time_stamp = '00000000000';
		$this->previous_hash = '00000000000000000000000000000000';
		$this->hash = $this->hash_data();
	}
}