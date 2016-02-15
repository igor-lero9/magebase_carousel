define([
    'jquery',
    'magebase/carousel'
], function($){
    "use strict";
    return function (config, element) {
        $(element).owlCarousel(config);
    }
});