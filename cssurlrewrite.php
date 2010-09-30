<?php
require_once(dirname(__FILE__).'/asset_functions.php');
require_once(dirname(__FILE__).'/config.php');

if (preg_match('/_deploy.css$/', $argv[1])) {
	exit;
}

if (!preg_match('/.css$/', $argv[1])) {
	exit;
}

$css = implode('', file($argv[1]));

function replace_css_url($matches) {
	global $baseCSSURLprefix;

	$url = $matches[1];

	$pos = strpos($url, $baseCSSURLprefix);

	if ($pos !== FALSE && $pos == 0) {
		$url = substr($url, strlen($baseCSSURLprefix));	
	}

	return "url('".assetURL($url)."')";
}

$output = fopen(preg_replace('/.css$/', '_deploy.css', $argv[1]), 'w');

fwrite($output, preg_replace_callback('/url\(\s*[\'"](.*?)[\'"]?\s*\)/i', 'replace_css_url', $css));

fclose($output);
