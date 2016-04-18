Fontawesome Extractor
=====================

NOTE: The extractor is only necessary if you want to try and export your own webfont. Just use `fontawesome-markers.min.js`
      and forget about (delete) this folder.

##How to
###For Font Awesome

```sh
cd fontawesome-markers
bower install
npm install
bash extractor/extract.sh
```

###For all others
You need two files:
* `yourfont/font.svg`
* `yourfont/variables.css`

Adjust `transform-webfont.php` to parse your variables correctly

Then do:

```bash
$ php transform-webfont.php font.svg variables.css webfont.paths.svg
$ svgo webfont.paths.svg webfont.paths.min.svg --pretty --disable=mergePaths --disable=cleanupIDs
$ php extract-paths.php webfont.paths.min.svg yourfont-markers.min.js yourfont-markers.json
```
