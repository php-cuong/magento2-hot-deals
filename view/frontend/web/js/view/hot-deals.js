/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_HotDeals
 * @copyright   Copyright (c) 2018-2019 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */

define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'mage/translate',
        'mage/storage',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/url',
        'jquery'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        $t,
        storage,
        fullScreenLoader,
        url,
        $
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'PHPCuong_HotDeals/hot-deals'
            },

            isVisible: ko.observable(true),

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                // register the new step named "Hot Deals"
                stepNavigator.registerStep(
                    'hot_deals',
                    null,
                    $t('Hot Deals'),
                    this.isVisible,
                    _.bind(this.navigate, this),
                    /**
                     * sort order value
                     * 'sort order value' < 10: step displays before shipping step;
                     * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                     * 'sort order value' > 20 : step displays after payment step
                     */
                    1
                );

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {

            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            },

            /**
             * @returns void
             */
            addToCart: function (formElement) {
                console.log($(formElement).serializeArray());
            },

            /**
             * @returns array
             */
            getProductsList: function () {
                return window.checkoutConfig.hot_deals_product;
            }
        });
    }
);
