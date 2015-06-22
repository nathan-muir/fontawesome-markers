php transform-webfont.php ../bower_components/fontawesome/fonts/fontawesome-webfont.svg ../bower_components/fontawesome/less/variables.less webfont.paths.svg
svgo webfont.paths.svg webfont.paths.min.svg --pretty --disable=mergePaths --disable=cleanupIDs
php extract-paths.php webfont.paths.min.svg ../fontawesome-markers.min.js ../fontawesome-markers.json