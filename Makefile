all: assets

clean: noassets

assets:
	svn status --verbose --xml |php svn-assets/svnassets.php > asset_versions.php

	find ./ -name '*.css' | xargs -n1 php svn-assets/cssurlrewrite.php

noassets:
	cp svn-assets/no-assets.php asset_versions.php
	find ./ -name '*_deploy.css' | xargs -n10 rm
