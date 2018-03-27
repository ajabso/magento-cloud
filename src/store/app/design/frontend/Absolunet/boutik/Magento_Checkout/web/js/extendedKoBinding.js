define([
    'jquery',
    'ko'
], function (
    $,
    ko
) {
    'use strict';

    /**
     * htmlDecode
     *
     * @param  value {string}
     * @return value {string}
     *
     * @ex: <span data-bind="attr: {title: ko.absolunet.htmlDecode(name)}"></span>
     */
    ko.absolunet = {
        htmlDecode: function(value) {
            if (typeof value === 'string') {
                return $('<div/>').html(value).text();
            }
        }
    };
});
