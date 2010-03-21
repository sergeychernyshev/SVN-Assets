<?php
$status = simplexml_load_file("php://stdin");

$total = count($status->target->entry);

$matches = array(
	'/\.css$/',
	'/\.js$/',
	'/\.png$/',
	'/\.jpg$/',
	'/\.gif$/'
);

$items = array();

for ($i = 0; $i < $total; $i++) {
	$entry = $status->target->entry[$i];
	$path = (string) $entry['path'];

	$found = false;

	foreach ($matches as $match) {
		if (preg_match($match, $path)) {
			$found = true;
			break;
		}
	}

	if ($found) {
		$children = $entry->children();

		if (count($children) == 0 ) {
			continue;
		}

		$wc_status = $children[0];

		$commit = $wc_status->commit;

		if ($commit) {
			$revision = $commit['revision'];
			$modified = $wc_status['item'] == 'modified';

			$items[$path] = (string) $revision;

			if ($modified) {
				$items[$path] .= '_m_'.filemtime($path);
			}
		}
	}
}

echo '<?php $assetVersions = '.var_export($items, true).';';
