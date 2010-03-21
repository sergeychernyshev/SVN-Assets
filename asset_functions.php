<?php
$baseAssetURL = '/';

if (!isset($assetVersions)) {
	$assetVersions = array();
}

function assetURL($path) {
	global $baseAssetURL, $assetVersions;

	if (array_key_exists($path, $assetVersions)) {
		$path = preg_replace_callback('|^(.*)\.([^\.]+)$|', 'replace_asset_url', $path);
	}

	return $baseAssetURL.$path; 
}

function replace_asset_url($matches) {
	global $assetVersions;

	$path = preg_replace('|\.css$|', '_deploy.css', $matches[1]);

	return $path.'.'.$assetVersions[$matches[0]].'.'.$matches[2];
}
