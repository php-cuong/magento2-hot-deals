<?php
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

namespace PHPCuong\HotDeals\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class DefaultConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $productCollection;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $categoryFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $catalogConfig;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $priceHelper;

    /**
     * @param \Magento\Catalog\Model\ProductFactory $product
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        $this->productCollection = $product;
        $this->categoryFactory = $categoryFactory;
        $this->_storeManager = $storeManager;
        $this->productVisibility = $productVisibility;
        $this->catalogConfig = $catalogConfig;
        $this->priceHelper = $priceHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $store = $this->_storeManager->getStore();
        $categoryId = 41;
        $category = $this->categoryFactory->create()->load($categoryId);
        $_products = $this->productCollection->create()->getCollection()
        ->setStoreId(
            $store->getId()
        )->addAttributeToSelect(
            $this->catalogConfig->getProductAttributes()
        )->addMinimalPrice()->addFinalPrice()->addTaxPercents()->addUrlRewrite(
            $categoryId
        )->addCategoryFilter(
            $category
        )->setVisibility(
            $this->productVisibility->getVisibleInCatalogIds()
        )->setCurPage(1)->setPageSize(10);

        $hotDealsProducts = [];
        foreach ($_products as $_product) {
            $hotDealsProducts[] = [
                'id' => $_product->getId(),
                'name' => $_product->getName(),
                'price' => $this->priceHelper->currency($_product->getFinalPrice(), true, false),
                'thumbnail' => $_product->getThumbnail()
            ];
        }

        return [
            'hot_deals_product' => $hotDealsProducts
        ];
    }
}
