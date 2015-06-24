//patterns to determine the view mode
var pattern1 = /option=1/;  //tree
var pattern2 = /option=2/; // list
var pattern3 = /option/;
var pattern4 = /option=3/; //popular

$(document).ready(function () {

    //alert("about to tag the cloud");
    if (pattern4.test(document.URL)) { // only in view 'popular'
        $('#doublescroll .tagcloud').tagcloud(); //(doublescroll is the box containing the socialwikitree)
    }
});

/*!
 * jquery.tagcloud.js
 * A Simple Tag Cloud Plugin for JQuery
 *
 * https://github.com/addywaddy/jquery.tagcloud.js
 * created by Adam Groves
 */
(function ($) {
    /*global jQuery*/
    "use strict";

    var compareWeights = function (a, b)
    {
        return a - b;
    };

    $.fn.tagcloud = function () {

        var opts = $.fn.tagcloud.defaults;
        var tagWeights = this.map(function () {
            return $(this).attr("rel");
        });
        tagWeights = jQuery.makeArray(tagWeights).sort(compareWeights);
        var lowest = tagWeights[0];
        var highest = tagWeights.pop();
        var range = highest - lowest;
        //adding stuff to handle range=0
        var zerorange = false;
        if (range === 0) {
            range = 1;
            zerorange = true; //rmember range was 0
        }
        // Sizes
        var fontIncr = (opts.size.end - opts.size.start) / range;
        return this.each(function () {
            var weighting = $(this).attr("rel") - lowest;
            if (zerorange) { //if there's just one value, put it in the middle rather than at the bottom of the sizes range.
                //alert("zero range");
                $(this).css({"font-size": (opts.size.start + opts.size.end) / 2 + opts.size.unit});
            } else {
                $(this).css({"font-size": opts.size.start + (weighting * fontIncr) + opts.size.unit});
            }
        });
    };

    $.fn.tagcloud.defaults = {
        size: {start: 8, end: 18, unit: "pt"}
    };

})(jQuery);
