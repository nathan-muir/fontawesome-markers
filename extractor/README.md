Fontawesome Extractor
=====================

NOTE: The extractor is only necessary if you want to try and export your own webfont. Just use `fontawesome-markers.min.js`
      and forget about (delete) this folder.

##Dependencies
* SVGO https://github.com/nathan-muir/svgo/tree/nathan-svgo
* Font Awesome http://fortawesome.github.io/Font-Awesome

##How to
First we need two files from Font Awesome:
* `font-awesome/fonts/fontawesome-webfont.svg`
* `font-awesome/less/variables.less`

```bash
$ php transform-webfont.php fontawesome-webfont.svg variables.less webfont.paths.svg
$ svgo webfont.paths.svg webfont.paths.min.svg --pretty --disable=mergePaths --disable=cleanupIDs
$ php extract-paths.php webfont.paths.min.svg fontawesome-markers.min.js
```
