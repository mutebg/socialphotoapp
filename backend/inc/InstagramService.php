<?php
class InstagramService {
	private $config;
	private $token;
	private $userID;

	function __construct($config) {
		$this->config = $config;

		if ( array_key_exists('insta_token', $_SESSION) && $_SESSION['insta_token'] ) {
			$this->token = $_SESSION['insta_token'];
		}
		if ( array_key_exists('insta_userid', $_SESSION) && $_SESSION['insta_userid'] ) {
			$this->userID = $_SESSION['insta_userid'];
		}
	}
	
	function getLoginURL() {
		return 'https://api.instagram.com/oauth/authorize/?client_id=' . $this->config['insta_app_id'] . 
			'&redirect_uri=' . $this->config['insta_return_url'] . 
			'&scope=' . $this->config['insta_scope'] . 
			'&response_type=code';
	}

	function handleResponse() {
		$code = $_GET['code'];
		$postData = array(
		  'client_id'       => $this->config['insta_app_id'],
		  'client_secret'   => $this->config['insta_app_secret'],
		  'grant_type'      => 'authorization_code',
		  'redirect_uri'    => $this->config['insta_return_url'],
		  'code'            => $code
		);

		$url = 'https://api.instagram.com/oauth/access_token';

		$response = curlPost($url, $postData);

		//echo $response;
		$data = json_decode($response, true);
		print_r($data);
		if ( array_key_exists('access_token', $data) ) {
			$this->token = $_SESSION['insta_token'] = $data['access_token'];
			$this->userID = $_SESSION['insta_userid'] = $data['user']['id'];

			return true;
		}
		return false;
	}

	function getPhotos($from, $to) {
		if ( $this->token && $this->userID ) {
			$url = 'https://api.instagram.com/v1/users/' . $this->userID . 
				'/media/recent?count=50' .
				'&min_timestamp=' . $from . 
				'&max_timestamp=' . $to .
				'&access_token=' . $this->token;

			$result = file_get_contents($url);
			
			if ( $result ) {
				$data = json_decode($result, true);
				$photos = array();
				foreach ($data['data'] as $i => $img) {
					$photos[] = array(
						'path' => $img['images']['standard_resolution']['url'],
						'name' => $img['caption'] ? $img['caption']['text'] : '',
						'date' => date('Y-m-d H:i:s', $img['created_time'])
					);
				}

				return $photos;
			}	

		}
		return false;
	}
}

?>