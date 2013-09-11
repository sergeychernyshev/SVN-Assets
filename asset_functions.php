<?php

if (!isset($baseAssetURL)) {
	$baseAssetURL = '/';
}

if (!isset($assetVersions)) {
	$assetVersions = array();
}

if (!isset($ignoreCSS)) {
	$ignoreCSS = array();
}

function loadAssetVersionsTSV($filename) {
	global $assetVersions;

	if (!file_exists($filename)) {
		return;
	}

	foreach (file($filename) as $line) {
		$pair = explode("\t", rtrim($line));

		$assetVersions[$pair[1]] = $pair[0];
	}
}

function u($path) {
	return assetURL($path);
}

function assetURL($path) {
	global $baseAssetURL, $assetVersions;

	$path = preg_replace_callback('|^(.*)\.([^\.]+)$|', 'replace_asset_url', $path);

	return $baseAssetURL . $path;
}

function replace_asset_url($matches) {
	global $assetVersions, $ignoreCSS;

	$path = $matches[1];

	if ($matches[2] == 'css') {
		$ignore = false;
		foreach ($ignoreCSS as $pattern) {
			if (preg_match($pattern, $path)) {
				$ignore = true;
				break;
			}
		}

		if (!$ignore) {
			$path .= '_deploy';
		}
	}

	if (array_key_exists($path, $assetVersions)) {
		if (getenv('URLVERSIONREWRITE') == 'YES') {
			return $path . '.' . $assetVersions[$matches[0]] . '.' . $matches[2];
		} else {
			return $path . '.' . $matches[2] . '?v=' . $assetVersions[$matches[0]];
		}
	} else {
		return $path . '.' . $matches[2];
	}
}
