<?php
class DropboxService {
	private $config;

	function __construct($config) {
		$this->config = $config;
	}
	
	function getLoginURL() {
		return 'https://www.dropbox.com/login';
	}

	function handleResponse() {
	}

	function getPhotos($from, $to) {
	}
}

?>