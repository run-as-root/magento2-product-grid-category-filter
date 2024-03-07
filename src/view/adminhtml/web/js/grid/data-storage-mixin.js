define([
    'jquery',
    'mageUtils'
], function ($, utils) {
    'use strict';

    let mixin = {
        /**
         * Sends request to the server with provided parameters.
         * Overridden to prevent 414 error (GET => POST for filter request)
         * @param {Object} params - Request parameters.
         * @returns {jQueryPromise}
         */
        requestData: function (params) {
            let query = utils.copy(params),
                handler = this.onRequestComplete.bind(this, query),
                request;

            this.requestConfig.data = query;

            let requestConfig = utils.copy(this.requestConfig);
            if(
                params.hasOwnProperty('namespace') &&
                params.hasOwnProperty('filters') &&
                params.namespace === 'product_listing'
            ) {
                requestConfig.method = 'POST';
            }

            request = $.ajax(requestConfig).done(handler);

            return request;
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});
