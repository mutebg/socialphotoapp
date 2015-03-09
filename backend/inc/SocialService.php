<?php
require 'backend/inc/FacebookService.php';
require 'backend/inc/InstagramService.php';
require 'backend/inc/DropboxService.php';

class SocialService {
	private $api;

	function __construct($social, $config) {
		$serviceName = ucfirst($social) . 'Service';
		if ( class_exists($serviceName) ) {
			$this->api = new $serviceName($config); 
			return true;
		}
		return false;
	}

	function __call($name, $arguments) {
		if ( method_exists($this->api, $name) ) {
			return call_user_func_array( array($this->api, $name), $arguments);
		}
		return false;
	}
}
?>