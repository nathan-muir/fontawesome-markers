fontawesome-markers
===================

An export of the fontawesome glyphs into named SVG Paths in javascript.

All glyphs have the same names as font-awesome, except they are capitalised, and underscored, eg "exclamation-sign" becomes "EXCLAMATION_SIGN"

You can use these paths, in products like Google Maps, for example:

```js
new google.maps.Marker({
    map: map,
    icon: {
        path: fontawesome.markers.EXCLAMATION,
        scale: 0.02,
        strokeWeight: 1,
        strokeColor: 'black',
        strokeOpacity: 1,
        fillColor: '#f8ae5f',
        fillOpacity: 0.7,
        rotation: 180
    },
    clickable: false,
    position: new google.maps.LatLng(lat, lng)
});
```
