fontawesome-markers
===================

An export of the fontawesome glyphs into named SVG Paths in javascript.

All glyphs have the same names as font-awesome, except they are capitalised, and underscored, eg "exclamation-circle" becomes "EXCLAMATION_CIRCLE"

Usage
-------------------

Install on bower with `bower install fontawesome-markers`
Or, install with NPM with `npm install fontawesome-markers`
Or, just add `fontawesome-markers.min.js` to your page and reference it like the examples below.

If you're a masochist, you can try extracting other webfonts (or newer versions of Font Awesome) using the scripts in `extractor/`


Example - Font Awesome on Google Maps
-------------------


You can use these paths, in products like Google Maps, for example:

```js
new google.maps.Marker({
    map: map,
    icon: {
        path: fontawesome.markers.EXCLAMATION_CIRCLE,
        scale: 0.5,
        strokeWeight: 0.2,
        strokeColor: 'black',
        strokeOpacity: 1,
        fillColor: '#f8ae5f',
        fillOpacity: 0.7,
    },
    clickable: false,
    position: new google.maps.LatLng(lat, lng)
});
```

Example - Font Awesome on Canvas
-------------------

You can also draw them to canvas, using the new Path2D api, you may need to use a [polyfill for unsupported browsers](https://github.com/google/canvas-5-polyfill)

```js
var canvas = document.getElementsByTagName('canvas')[0];
var ctx = canvas.getContext("2d");
var path = new Path2D(fontawesome.markers.EXCLAMATION_CIRCLE);
ctx.strokeStyle="#ff0000";
ctx.lineWidth=2;
ctx.fillStyle="#0000ff";
ctx.translate(0, 64);
ctx.fill(path);
ctx.stroke(path);
```

Example - Loading Font Awesome Markers via JSON/ XHR
-------------------

Instead of including it as an inline script, you can also load it with JSON/XHR.

```js
var fontawesome = {
   markers: null
};
var xhr = new XMLHttpRequest();
xhr.onload = function(){
  fontawesome.markers = JSON.parse(this.responseText);
}
xhr.open("get", "fontawesome-markers.json", true);
xhr.send();
```

Update
===================
 * 18th April 2016 - Made it super easy to build for new versions of fontawesome. Released on Bower & NPM.
 * 16th October 2014 - Updated to fontawesome 4.2.0
 * 7th August 2014 - Updated to fontawesome 4.1.0, see the [Fontawesome Upgrade Guide](https://github.com/FortAwesome/Font-Awesome/wiki/Upgrading-from-3.2.1-to-4) for the list of changed names.
 * 26th September 2013 - Changed font extraction process - Glyph size is a much more manageable 64px now, and rotation / flip corrected.
