<?php
define('FACEBOOK_SDK_V4_SRC_DIR', 'backend/3rdparty/facebook-sdk/src/Facebook/');

require 'backend/3rdparty/facebook-sdk/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class FacebookService {
	private $config;
	private $token;

	function __construct($config) {
		$this->config = $config;
		FacebookSession::setDefaultApplication( 
			$this->config['facebook_app_id'], $this->config['facebook_app_secret']);

		if ( array_key_exists('facebook_token', $_SESSION) && $_SESSION['facebook_token'] ) {
			$this->token = $_SESSION['facebook_token'];
		}
	}
	
	function getLoginURL() {
		$fbHelper = new FacebookRedirectLoginHelper( $this->config['facebook_return_url'] );
		return $fbHelper->getLoginUrl($this->config['facebook_scope']);
	}

	function handleResponse() {
		$helper = new FacebookRedirectLoginHelper( $this->config['facebook_return_url'] );
		try {
			$session = $helper->getSessionFromRedirect();
			$this->token = $_SESSION['facebook_token']  = $session->getToken();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	function getPhotos($from, $to) {
		if ( $this->token ) {
			$session = new FacebookSession( $this->token );
			$request = new FacebookRequest($session, 'GET', '/me/photos/uploaded?fields=created_time,source,name&since=' . $from . '&until=' . $to);
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			$data = $graphObject->asArray();
			$photos = array();
			if ( $data ) {
				foreach ($data['data'] as $row) {
					$row = (array)$row;
					$photos[] = array(
						'path' 	=> $row['source'],
						'name' 	=> array_key_exists('name', $row) ? $row['name'] : '',
						'date'	=> date('Y-m-d H:i:s', strtotime($row['created_time']))
					);
				}
			}
			return $photos;
	    }
	    return false;
	}

}

?>