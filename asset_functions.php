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

	$path = $matches[1];

	if ($matches[2] == 'css') {
		$path .= '_deploy';
	}

	if (getenv('URLVERSIONREWRITE') == 'YES') {
		return $path.'.'.$assetVersions[$matches[0]].'.'.$matches[2];
	} else {
		return $path.'.'.$matches[2].'?v='.$assetVersions[$matches[0]];
	}
}
