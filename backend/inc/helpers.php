<?php

function prepareName($string) {
	return md5($string) . '.jpg';
}

function copyImage($url, $dest) {
	try {
		copy($url, $dest);
		return true;
	} catch (Exception $e) {
		return false;
	}
}


function curlPost($url, $data) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1 );
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

?>