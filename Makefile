all: assets

clean: noassets

assets:
	svn status --verbose --xml |php svn-assets/svnassets.php > asset_versions.php

	# generating crc32 hashes of all assets that should be versioned
	find ./ -type f | grep -E '\.(png|jpg|css|js|gif|xml|txt|ico|svn|swf|flv)$$' |xargs -n10 crc32 | sed -e 's/\t\.\//\t/' > asset_versions.tsv

	find ./ -name '*.css' | xargs -n1 php svn-assets/cssurlrewrite.php

noassets:
	cp svn-assets/no-assets.php asset_versions.php
	find ./ -name '*_deploy.css' | xargs -n10 rm
