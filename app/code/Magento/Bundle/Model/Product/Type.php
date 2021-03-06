<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Bundle\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Bundle Type Model
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Type extends \Magento\Catalog\Model\Product\Type\AbstractType
{
    /**
     * Product is composite
     *
     * @var bool
     */
    protected $_isComposite = true;

    /**
     * Cache key for Options Collection
     *
     * @var string
     */
    protected $_keyOptionsCollection = '_cache_instance_options_collection';

    /**
     * Cache key for Selections Collection
     *
     * @var string
     */
    protected $_keySelectionsCollection = '_cache_instance_selections_collection';

    /**
     * Cache key for used Selections
     *
     * @var string
     */
    protected $_keyUsedSelections = '_cache_instance_used_selections';

    /**
     * Cache key for used selections ids
     *
     * @var string
     */
    protected $_keyUsedSelectionsIds = '_cache_instance_used_selections_ids';

    /**
     * Cache key for used options
     *
     * @var string
     */
    protected $_keyUsedOptions = '_cache_instance_used_options';

    /**
     * Cache key for used options ids
     *
     * @var string
     */
    protected $_keyUsedOptionsIds = '_cache_instance_used_options_ids';

    /**
     * Product is possible to configure
     *
     * @var bool
     */
    protected $_canConfigure = true;

    /**
     * Catalog data
     *
     * @var \Magento\Catalog\Helper\Data
     */
    protected $_catalogData = null;

    /**
     * Catalog product
     *
     * @var \Magento\Catalog\Helper\Product
     */
    protected $_catalogProduct = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Bundle\Model\OptionFactory
     */
    protected $_bundleOption;

    /**
     * @var \Magento\Bundle\Model\Resource\Selection
     */
    protected $_bundleSelection;

    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $_config;

    /**
     * @var \Magento\Bundle\Model\Resource\Selection\CollectionFactory
     */
    protected $_bundleCollection;

    /**
     * @var \Magento\Bundle\Model\Resource\BundleFactory
     */
    protected $_bundleFactory;

    /**
     * @var \Magento\Bundle\Model\SelectionFactory $bundleModelSelection
     */
    protected $_bundleModelSelection;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockState;

    /**
     * @param \Magento\Catalog\Model\Product\Option $catalogProductOption
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Catalog\Model\Product\Type $catalogProductType
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Core\Helper\File\Storage\Database $fileStorageDb
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Psr\Log\LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Bundle\Model\SelectionFactory $bundleModelSelection
     * @param \Magento\Bundle\Model\Resource\BundleFactory $bundleFactory
     * @param \Magento\Bundle\Model\Resource\Selection\CollectionFactory $bundleCollection
     * @param \Magento\Catalog\Model\Config $config
     * @param \Magento\Bundle\Model\Resource\Selection $bundleSelection
     * @param \Magento\Bundle\Model\OptionFactory $bundleOption
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Core\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Bundle\Model\SelectionFactory $bundleModelSelection,
        \Magento\Bundle\Model\Resource\BundleFactory $bundleFactory,
        \Magento\Bundle\Model\Resource\Selection\CollectionFactory $bundleCollection,
        \Magento\Catalog\Model\Config $config,
        \Magento\Bundle\Model\Resource\Selection $bundleSelection,
        \Magento\Bundle\Model\OptionFactory $bundleOption,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState
    ) {
        $this->_catalogProduct = $catalogProduct;
        $this->_catalogData = $catalogData;
        $this->_storeManager = $storeManager;
        $this->_bundleOption = $bundleOption;
        $this->_bundleSelection = $bundleSelection;
        $this->_config = $config;
        $this->_bundleCollection = $bundleCollection;
        $this->_bundleFactory = $bundleFactory;
        $this->_bundleModelSelection = $bundleModelSelection;
        $this->priceCurrency = $priceCurrency;
        $this->_stockRegistry = $stockRegistry;
        $this->_stockState = $stockState;
        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository
        );
    }

    /**
     * Return relation info about used products
     *
     * @return \Magento\Framework\Object Object with information data
     */
    public function getRelationInfo()
    {
        $info = new \Magento\Framework\Object();
        $info->setTable('catalog_product_bundle_selection')
            ->setParentFieldName('parent_product_id')
            ->setChildFieldName('product_id');

        return $info;
    }

    /**
     * Retrieve Required children ids
     * Return grouped array, ex array(
     *   group => array(ids)
     * )
     *
     * @param int $parentId
     * @param bool $required
     * @return array
     */
    public function getChildrenIds($parentId, $required = true)
    {
        return $this->_bundleSelection->getChildrenIds($parentId, $required);
    }

    /**
     * Retrieve parent ids array by required child
     *
     * @param int|array $childId
     * @return array
     */
    public function getParentIdsByChild($childId)
    {
        return $this->_bundleSelection->getParentIdsByChild($childId);
    }

    /**
     * Return product sku based on sku_type attribute
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getSku($product)
    {
        $sku = parent::getSku($product);

        if ($product->getData('sku_type')) {
            return $sku;
        } else {
            $skuParts = [$sku];

            if ($product->hasCustomOptions()) {
                $customOption = $product->getCustomOption('bundle_selection_ids');
                $selectionIds = unserialize($customOption->getValue());
                if (!empty($selectionIds)) {
                    $selections = $this->getSelectionsByIds($selectionIds, $product);
                    foreach ($selections->getItems() as $selection) {
                        $skuParts[] = $selection->getSku();
                    }
                }
            }

            return implode('-', $skuParts);
        }
    }

    /**
     * Return product weight based on weight_type attribute
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return float
     */
    public function getWeight($product)
    {
        if ($product->getData('weight_type')) {
            return $product->getData('weight');
        } else {
            $weight = 0;

            if ($product->hasCustomOptions()) {
                $customOption = $product->getCustomOption('bundle_selection_ids');
                $selectionIds = unserialize($customOption->getValue());
                $selections = $this->getSelectionsByIds($selectionIds, $product);
                foreach ($selections->getItems() as $selection) {
                    $qtyOption = $product->getCustomOption('selection_qty_' . $selection->getSelectionId());
                    if ($qtyOption) {
                        $weight += $selection->getWeight() * $qtyOption->getValue();
                    } else {
                        $weight += $selection->getWeight();
                    }
                }
            }

            return $weight;
        }
    }

    /**
     * Check is virtual product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function isVirtual($product)
    {
        if ($product->hasCustomOptions()) {
            $customOption = $product->getCustomOption('bundle_selection_ids');
            $selectionIds = unserialize($customOption->getValue());
            $selections = $this->getSelectionsByIds($selectionIds, $product);
            $virtualCount = 0;
            foreach ($selections->getItems() as $selection) {
                if ($selection->isVirtual()) {
                    $virtualCount++;
                }
            }

            return $virtualCount == count($selections);
        }

        return false;
    }

    /**
     * Before save type related data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return $this|void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function beforeSave($product)
    {
        parent::beforeSave($product);

        // If bundle product has dynamic weight, than delete weight attribute
        if (!$product->getData('weight_type') && $product->hasData('weight')) {
            $product->setData('weight', false);
        }

        if ($product->getPriceType() == Price::PRICE_TYPE_DYNAMIC) {
            /** unset product custom options for dynamic price */
            if ($product->hasData('product_options')) {
                $product->unsetData('product_options');
            }
        }

        $product->canAffectOptions(false);

        if ($product->getCanSaveBundleSelections()) {
            $product->canAffectOptions(true);
            $selections = $product->getBundleSelectionsData();
            if ($selections && !empty($selections)) {
                $options = $product->getBundleOptionsData();
                if ($options) {
                    foreach ($options as $option) {
                        if (empty($option['delete']) || 1 != (int)$option['delete']) {
                            $product->setTypeHasOptions(true);
                            if (1 == (int)$option['required']) {
                                $product->setTypeHasRequiredOptions(true);
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Save type related data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function save($product)
    {
        parent::save($product);
        /* @var $resource \Magento\Bundle\Model\Resource\Bundle */
        $resource = $this->_bundleFactory->create();

        $options = $product->getBundleOptionsData();
        if ($options) {
            $product->setIsRelationsChanged(true);

            foreach ($options as $key => $option) {
                if (isset($option['option_id']) && $option['option_id'] == '') {
                    unset($option['option_id']);
                }

                $optionModel = $this->_bundleOption->create()
                    ->setData($option)
                    ->setParentId($product->getId())
                    ->setStoreId($product->getStoreId());

                $optionModel->isDeleted((bool)$option['delete']);
                $optionModel->save();
                $options[$key]['option_id'] = $optionModel->getOptionId();
            }

            $usedProductIds = [];
            $excludeSelectionIds = [];

            $selections = $product->getBundleSelectionsData();
            if ($selections) {
                foreach ($selections as $index => $group) {
                    foreach ($group as $selection) {
                        if (isset($selection['selection_id']) && $selection['selection_id'] == '') {
                            unset($selection['selection_id']);
                        }

                        if (!isset($selection['is_default'])) {
                            $selection['is_default'] = 0;
                        }

                        $selectionModel = $this->_bundleModelSelection->create()
                            ->setData($selection)
                            ->setOptionId($options[$index]['option_id'])
                            ->setWebsiteId(
                                $this->_storeManager->getStore($product->getStoreId())
                                    ->getWebsiteId()
                            )
                            ->setParentProductId($product->getId());

                        $selectionModel->isDeleted((bool)$selection['delete']);
                        $selectionModel->save();

                        $selection['selection_id'] = $selectionModel->getSelectionId();

                        if ($selectionModel->getSelectionId()) {
                            $excludeSelectionIds[] = $selectionModel->getSelectionId();
                            $usedProductIds[] = $selectionModel->getProductId();
                        }
                    }
                }

                $resource->dropAllUnneededSelections($product->getId(), $excludeSelectionIds);
                $resource->saveProductRelations($product->getId(), array_unique($usedProductIds));
            }

            if ($product->getData('price_type') != $product->getOrigData('price_type')) {
                $resource->dropAllQuoteChildItems($product->getId());
            }
        }

        return $this;
    }

    /**
     * Retrieve bundle options items
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Framework\Object[]
     */
    public function getOptions($product)
    {
        return $this->getOptionsCollection($product)
            ->getItems();
    }

    /**
     * Retrieve bundle options ids
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getOptionsIds($product)
    {
        return $this->getOptionsCollection($product)
            ->getAllIds();
    }

    /**
     * Retrieve bundle option collection
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Bundle\Model\Resource\Option\Collection
     */
    public function getOptionsCollection($product)
    {
        if (!$product->hasData($this->_keyOptionsCollection)) {
            /** @var \Magento\Bundle\Model\Resource\Option\Collection $optionsCollection */
            $optionsCollection = $this->_bundleOption->create()
                ->getResourceCollection();
            $optionsCollection->setProductIdFilter($product->getId());
            $this->setStoreFilter($product->getStoreId(), $product);
            $optionsCollection->setPositionOrder();
            $storeId = $this->getStoreFilter($product);
            if ($storeId instanceof \Magento\Store\Model\Store) {
                $storeId = $storeId->getId();
            }

            $optionsCollection->joinValues($storeId);
            $product->setData($this->_keyOptionsCollection, $optionsCollection);
        }

        return $product->getData($this->_keyOptionsCollection);
    }

    /**
     * Retrieve bundle selections collection based on used options
     *
     * @param array $optionIds
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Bundle\Model\Resource\Selection\Collection
     */
    public function getSelectionsCollection($optionIds, $product)
    {
        $keyOptionIds = is_array($optionIds) ? implode('_', $optionIds) : '';
        $key = $this->_keySelectionsCollection . $keyOptionIds;
        if (!$product->hasData($key)) {
            $storeId = $product->getStoreId();
            $selectionsCollection = $this->_bundleCollection->create()
                ->addAttributeToSelect($this->_config->getProductAttributes())
                ->addAttributeToSelect('tax_class_id')//used for calculation item taxes in Bundle with Dynamic Price
                ->setFlag('require_stock_items', true)
                ->setFlag('product_children', true)
                ->setPositionOrder()
                ->addStoreFilter($this->getStoreFilter($product))
                ->setStoreId($storeId)
                ->addFilterByRequiredOptions()
                ->setOptionIdsFilter($optionIds);

            if (!$this->_catalogData->isPriceGlobal() && $storeId) {
                $websiteId = $this->_storeManager->getStore($storeId)
                    ->getWebsiteId();
                $selectionsCollection->joinPrices($websiteId);
            }

            $product->setData($key, $selectionsCollection);
        }

        return $product->getData($key);
    }

    /**
     * Method is needed for specific actions to change given quote options values
     * according current product type logic
     * Example: the catalog inventory validation of decimal qty can change qty to int,
     * so need to change quote item qty option value too.
     *
     * @param   array $options
     * @param   \Magento\Framework\Object $option
     * @param   mixed $value
     * @param   \Magento\Catalog\Model\Product $product
     * @return $this
     */
    public function updateQtyOption($options, \Magento\Framework\Object $option, $value, $product)
    {
        $optionProduct = $option->getProduct($product);
        $optionUpdateFlag = $option->getHasQtyOptionUpdate();
        $optionCollection = $this->getOptionsCollection($product);

        $selections = $this->getSelectionsCollection($optionCollection->getAllIds(), $product);

        foreach ($selections as $selection) {
            if ($selection->getProductId() == $optionProduct->getId()) {
                foreach ($options as &$option) {
                    if ($option->getCode() == 'selection_qty_' . $selection->getSelectionId()) {
                        if ($optionUpdateFlag) {
                            $option->setValue(intval($option->getValue()));
                        } else {
                            $option->setValue($value);
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Prepare Quote Item Quantity
     *
     * @param mixed $qty
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function prepareQuoteItemQty($qty, $product)
    {
        return intval($qty);
    }

    /**
     * Checking if we can sale this bundle
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function isSalable($product)
    {
        if (!parent::isSalable($product)) {
            return false;
        }

        if ($product->hasData('all_items_salable')) {
            return $product->getData('all_items_salable');
        }

        $optionCollection = $this->getOptionsCollection($product);

        if (!count($optionCollection->getItems())) {
            return false;
        }

        $requiredOptionIds = [];

        foreach ($optionCollection->getItems() as $option) {
            if ($option->getRequired()) {
                $requiredOptionIds[$option->getId()] = 0;
            }
        }

        $selectionCollection = $this->getSelectionsCollection($optionCollection->getAllIds(), $product);

        if (!count($selectionCollection->getItems())) {
            return false;
        }
        $salableSelectionCount = 0;

        foreach ($selectionCollection as $selection) {
            /* @var $selection \Magento\Catalog\Model\Product */
            if ($selection->isSalable()) {
                $selectionEnoughQty = $this->_stockRegistry->getStockItem($selection->getId())
                    ->getManageStock()
                    ? $selection->getSelectionQty() <= $this->_stockState->getStockQty($selection->getId())
                    : $selection->isInStock();

                if (!$selection->hasSelectionQty() || $selection->getSelectionCanChangeQty() || $selectionEnoughQty) {
                    $requiredOptionIds[$selection->getOptionId()] = 1;
                    $salableSelectionCount++;
                }
            }
        }
        $isSalable = array_sum($requiredOptionIds) == count($requiredOptionIds) && $salableSelectionCount;
        $product->setData('all_items_salable', $isSalable);

        return $isSalable;
    }

    /**
     * Prepare product and its configuration to be added to some products list.
     * Perform standard preparation process and then prepare of bundle selections options.
     *
     * @param \Magento\Framework\Object $buyRequest
     * @param \Magento\Catalog\Model\Product $product
     * @param string $processMode
     * @return \Magento\Framework\Phrase|array|string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareProduct(\Magento\Framework\Object $buyRequest, $product, $processMode)
    {
        $result = parent::_prepareProduct($buyRequest, $product, $processMode);

        try {
            if (is_string($result)) {
                throw new \Magento\Framework\Model\Exception($result);
            }

            $selections = [];
            $isStrictProcessMode = $this->_isStrictProcessMode($processMode);

            $skipSaleableCheck = $this->_catalogProduct->getSkipSaleableCheck();
            $_appendAllSelections = (bool)$product->getSkipCheckRequiredOption() || $skipSaleableCheck;

            $options = $buyRequest->getBundleOption();
            if (is_array($options)) {
                $options = $this->recursiveIntval($options);
                $optionIds = array_keys($options);

                if (empty($optionIds) && $isStrictProcessMode) {
                    throw new \Magento\Framework\Model\Exception(__('Please specify product option(s).'));
                }

                $product->getTypeInstance()
                    ->setStoreFilter($product->getStoreId(), $product);
                $optionsCollection = $this->getOptionsCollection($product);
                $this->checkIsAllRequiredOptions(
                    $product,
                    $isStrictProcessMode,
                    $optionsCollection,
                    $options
                );

                $selectionIds = $this->multiToFlatArray($options);
                // If product has not been configured yet then $selections array should be empty
                if (!empty($selectionIds)) {
                    $selections = $this->getSelectionsByIds($selectionIds, $product);

                    // Check if added selections are still on sale
                    $this->checkSelectionsIsSale(
                        $selections,
                        $skipSaleableCheck,
                        $optionsCollection,
                        $options
                    );

                    $optionsCollection->appendSelections($selections, false, $_appendAllSelections);

                    $selections = $selections->getItems();
                } else {
                    $selections = [];
                }
            } else {
                $product->setOptionsValidationFail(true);
                $product->getTypeInstance()
                    ->setStoreFilter($product->getStoreId(), $product);

                $optionCollection = $product->getTypeInstance()
                    ->getOptionsCollection($product);
                $optionIds = $product->getTypeInstance()
                    ->getOptionsIds($product);
                $selectionCollection = $product->getTypeInstance()
                    ->getSelectionsCollection($optionIds, $product);
                $options = $optionCollection->appendSelections($selectionCollection, false, $_appendAllSelections);

                $selections = $this->mergeSelectionsWithOptions($options, $selections);
            }
            if (count($selections) > 0 || !$isStrictProcessMode) {
                $uniqueKey = [$product->getId()];
                $selectionIds = [];
                $qtys = $buyRequest->getBundleOptionQty();

                // Shuffle selection array by option position
                usort($selections, [$this, 'shakeSelections']);

                foreach ($selections as $selection) {
                    $selectionOptionId = $selection->getOptionId();
                    $qty = $this->getQty($selection, $qtys, $selectionOptionId);

                    $selectionId = $selection->getSelectionId();
                    $product->addCustomOption('selection_qty_' . $selectionId, $qty, $selection);
                    $selection->addCustomOption('selection_id', $selectionId);

                    $beforeQty = $this->getBeforeQty($product, $selection);
                    $product->addCustomOption('product_qty_' . $selection->getId(), $qty + $beforeQty, $selection);

                    /*
                     * Create extra attributes that will be converted to product options in order item
                     * for selection (not for all bundle)
                     */
                    $price = $product->getPriceModel()
                        ->getSelectionFinalTotalPrice($product, $selection, 0, $qty);
                    $attributes = [
                        'price' => $this->priceCurrency->convert($price),
                        'qty' => $qty,
                        'option_label' => $selection->getOption()
                            ->getTitle(),
                        'option_id' => $selection->getOption()
                            ->getId(),
                    ];

                    $_result = $selection->getTypeInstance()
                        ->prepareForCart($buyRequest, $selection);
                    $this->checkIsResult($_result);

                    $result[] = $_result[0]->setParentProductId($product->getId())
                        ->addCustomOption('bundle_option_ids', serialize(array_map('intval', $optionIds)))
                        ->addCustomOption('bundle_selection_attributes', serialize($attributes));

                    if ($isStrictProcessMode) {
                        $_result[0]->setCartQty($qty);
                    }

                    $resultSelectionId = $_result[0]->getSelectionId();
                    $selectionIds[] = $resultSelectionId;
                    $uniqueKey[] = $resultSelectionId;
                    $uniqueKey[] = $qty;
                }

                // "unique" key for bundle selection and add it to selections and bundle for selections
                $uniqueKey = implode('_', $uniqueKey);
                foreach ($result as $item) {
                    $item->addCustomOption('bundle_identity', $uniqueKey);
                }
                $product->addCustomOption('bundle_option_ids', serialize(array_map('intval', $optionIds)));
                $product->addCustomOption('bundle_selection_ids', serialize($selectionIds));

                return $result;
            }
        } catch (\Magento\Framework\Model\Exception $e) {
            return $e->getMessage();
        }

        return $this->getSpecifyOptionMessage();
    }

    /**
     * @param array $array
     * @return int[]|int[][]
     */
    private function recursiveIntval(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->recursiveIntval($value);
            } elseif (is_numeric($value) && (int)$value != 0) {
                $array[$key] = (int)$value;
            } else {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @param array $array
     * @return int[]
     */
    private function multiToFlatArray(array $array)
    {
        $flatArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $flatArray = array_merge($flatArray, $this->multiToFlatArray($value));
            } else {
                $flatArray[$key] = $value;
            }
        }

        return $flatArray;
    }

    /**
     * Retrieve message for specify option(s)
     *
     * @return \Magento\Framework\Phrase
     */
    public function getSpecifyOptionMessage()
    {
        return __('Please specify product option(s).');
    }

    /**
     * Retrieve bundle selections collection based on ids
     *
     * @param array $selectionIds
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Bundle\Model\Resource\Selection\Collection
     */
    public function getSelectionsByIds($selectionIds, $product)
    {
        sort($selectionIds);

        $usedSelections = $product->getData($this->_keyUsedSelections);
        $usedSelectionsIds = $product->getData($this->_keyUsedSelectionsIds);

        if (!$usedSelections || $usedSelectionsIds !== $selectionIds) {
            $storeId = $product->getStoreId();
            $usedSelections = $this->_bundleCollection
                ->create()
                ->addAttributeToSelect('*')
                ->setFlag('require_stock_items', true)
                ->setFlag('product_children', true)
                ->addStoreFilter($this->getStoreFilter($product))
                ->setStoreId($storeId)
                ->setPositionOrder()
                ->addFilterByRequiredOptions()
                ->setSelectionIdsFilter($selectionIds);

            if (!$this->_catalogData->isPriceGlobal() && $storeId) {
                $websiteId = $this->_storeManager->getStore($storeId)
                    ->getWebsiteId();
                $usedSelections->joinPrices($websiteId);
            }
            $product->setData($this->_keyUsedSelections, $usedSelections);
            $product->setData($this->_keyUsedSelectionsIds, $selectionIds);
        }

        return $usedSelections;
    }

    /**
     * Retrieve bundle options collection based on ids
     *
     * @param array $optionIds
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Bundle\Model\Resource\Option\Collection
     */
    public function getOptionsByIds($optionIds, $product)
    {
        sort($optionIds);

        $usedOptions = $product->getData($this->_keyUsedOptions);
        $usedOptionsIds = $product->getData($this->_keyUsedOptionsIds);

        if (!$usedOptions || serialize($usedOptionsIds) != serialize($optionIds)) {
            $usedOptions = $this->_bundleOption
                ->create()
                ->getResourceCollection()
                ->setProductIdFilter($product->getId())
                ->setPositionOrder()
                ->joinValues(
                    $this->_storeManager->getStore()
                        ->getId()
                )
                ->setIdFilter($optionIds);
            $product->setData($this->_keyUsedOptions, $usedOptions);
            $product->setData($this->_keyUsedOptionsIds, $optionIds);
        }

        return $usedOptions;
    }

    /**
     * Prepare additional options/information for order item which will be
     * created from this product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getOrderOptions($product)
    {
        $optionArr = parent::getOrderOptions($product);
        $bundleOptions = [];

        if ($product->hasCustomOptions()) {
            $customOption = $product->getCustomOption('bundle_option_ids');
            $optionIds = unserialize($customOption->getValue());
            $options = $this->getOptionsByIds($optionIds, $product);
            $customOption = $product->getCustomOption('bundle_selection_ids');
            $selectionIds = unserialize($customOption->getValue());
            $selections = $this->getSelectionsByIds($selectionIds, $product);
            foreach ($selections->getItems() as $selection) {
                if ($selection->isSalable()) {
                    $selectionQty = $product->getCustomOption('selection_qty_' . $selection->getSelectionId());
                    if ($selectionQty) {
                        $price = $product->getPriceModel()
                            ->getSelectionFinalTotalPrice(
                                $product,
                                $selection,
                                0,
                                $selectionQty->getValue()
                            );

                        $option = $options->getItemById($selection->getOptionId());
                        if (!isset($bundleOptions[$option->getId()])) {
                            $bundleOptions[$option->getId()] = [
                                'option_id' => $option->getId(),
                                'label' => $option->getTitle(),
                                'value' => [],
                            ];
                        }

                        $bundleOptions[$option->getId()]['value'][] = [
                            'title' => $selection->getName(),
                            'qty' => $selectionQty->getValue(),
                            'price' => $this->priceCurrency->convert($price),
                        ];
                    }
                }
            }
        }

        $optionArr['bundle_options'] = $bundleOptions;

        /**
         * Product Prices calculations save
         */
        if ($product->getPriceType()) {
            $optionArr['product_calculations'] = self::CALCULATE_PARENT;
        } else {
            $optionArr['product_calculations'] = self::CALCULATE_CHILD;
        }

        $optionArr['shipment_type'] = $product->getShipmentType();

        return $optionArr;
    }

    /**
     * Sort selections method for usort function
     * Sort selections by option position, selection position and selection id
     *
     * @param  \Magento\Catalog\Model\Product $firstItem
     * @param  \Magento\Catalog\Model\Product $secondItem
     * @return int
     */
    public function shakeSelections($firstItem, $secondItem)
    {
        $aPosition = [
            $firstItem->getOption()
                ->getPosition(),
            $firstItem->getOptionId(),
            $firstItem->getPosition(),
            $firstItem->getSelectionId(),
        ];
        $bPosition = [
            $secondItem->getOption()
                ->getPosition(),
            $secondItem->getOptionId(),
            $secondItem->getPosition(),
            $secondItem->getSelectionId(),
        ];
        if ($aPosition == $bPosition) {
            return 0;
        } else {
            return $aPosition < $bPosition ? -1 : 1;
        }
    }

    /**
     * Return true if product has options
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function hasOptions($product)
    {
        $this->setStoreFilter($product->getStoreId(), $product);
        $optionIds = $this->getOptionsCollection($product)
            ->getAllIds();
        $collection = $this->getSelectionsCollection($optionIds, $product);

        if (count($collection) > 0 || $product->getOptions()) {
            return true;
        }

        return false;
    }

    /**
     * Allow for updates of children qty's
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return boolean true
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getForceChildItemQtyChanges($product)
    {
        return true;
    }

    /**
     * Retrieve additional searchable data from type instance
     * Using based on product id and store_id data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getSearchableData($product)
    {
        $searchData = parent::getSearchableData($product);

        $optionSearchData = $this->_bundleOption->create()
            ->getSearchableData(
                $product->getId(),
                $product->getStoreId()
            );
        if ($optionSearchData) {
            $searchData = array_merge($searchData, $optionSearchData);
        }

        return $searchData;
    }

    /**
     * Check if product can be bought
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     * @throws \Magento\Framework\Model\Exception
     */
    public function checkProductBuyState($product)
    {
        parent::checkProductBuyState($product);
        $productOptionIds = $this->getOptionsIds($product);
        $productSelections = $this->getSelectionsCollection($productOptionIds, $product);
        $selectionIds = $product->getCustomOption('bundle_selection_ids');
        $selectionIds = unserialize($selectionIds->getValue());
        $buyRequest = $product->getCustomOption('info_buyRequest');
        $buyRequest = new \Magento\Framework\Object(unserialize($buyRequest->getValue()));
        $bundleOption = $buyRequest->getBundleOption();

        if (empty($bundleOption)) {
            throw new \Magento\Framework\Model\Exception($this->getSpecifyOptionMessage());
        }

        $skipSaleableCheck = $this->_catalogProduct->getSkipSaleableCheck();
        foreach ($selectionIds as $selectionId) {
            /* @var $selection \Magento\Bundle\Model\Selection */
            $selection = $productSelections->getItemById($selectionId);
            if (!$selection || !$selection->isSalable() && !$skipSaleableCheck) {
                throw new \Magento\Framework\Model\Exception(
                    __('The required options you selected are not available.')
                );
            }
        }

        $product->getTypeInstance()
            ->setStoreFilter($product->getStoreId(), $product);
        $optionsCollection = $this->getOptionsCollection($product);
        foreach ($optionsCollection->getItems() as $option) {
            if ($option->getRequired() && empty($bundleOption[$option->getId()])) {
                throw new \Magento\Framework\Model\Exception(__('Please select all required options.'));
            }
        }

        return $this;
    }

    /**
     * Retrieve products divided into groups required to purchase
     * At least one product in each group has to be purchased
     *
     * @param  \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getProductsToPurchaseByReqGroups($product)
    {
        $groups = [];
        $allProducts = [];
        $hasRequiredOptions = false;
        foreach ($this->getOptions($product) as $option) {
            $groupProducts = [];
            foreach ($this->getSelectionsCollection([$option->getId()], $product) as $childProduct) {
                $groupProducts[] = $childProduct;
                $allProducts[] = $childProduct;
            }
            if ($option->getRequired()) {
                $groups[] = $groupProducts;
                $hasRequiredOptions = true;
            }
        }
        if (!$hasRequiredOptions) {
            $groups = [$allProducts];
        }

        return $groups;
    }

    /**
     * Prepare selected options for bundle product
     *
     * @param  \Magento\Catalog\Model\Product $product
     * @param  \Magento\Framework\Object $buyRequest
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function processBuyRequest($product, $buyRequest)
    {
        $option = $buyRequest->getBundleOption();
        $optionQty = $buyRequest->getBundleOptionQty();

        $option = is_array($option) ? array_filter($option, 'intval') : [];
        $optionQty = is_array($optionQty) ? array_filter($optionQty, 'intval') : [];

        $options = ['bundle_option' => $option, 'bundle_option_qty' => $optionQty];

        return $options;
    }

    /**
     * Check if product can be configured
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function canConfigure($product)
    {
        return $product instanceof \Magento\Catalog\Model\Product && $product->isAvailable() && parent::canConfigure(
            $product
        );
    }

    /**
     * Delete data specific for Bundle product type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }

    /**
     * Return array of specific to type product entities
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getIdentities(\Magento\Catalog\Model\Product $product)
    {
        $identities = parent::getIdentities($product);
        /** @var \Magento\Bundle\Model\Option $option */
        foreach ($this->getOptions($product) as $option) {
            if ($option->getSelections()) {
                /** @var \Magento\Catalog\Model\Product $selection */
                foreach ($option->getSelections() as $selection) {
                    $identities = array_merge($identities, $selection->getIdentities());
                }
            }
        }

        return $identities;
    }

    /**
     * @param \Magento\Framework\Object $selection
     * @param int[] $qtys
     * @param int $selectionOptionId
     * @return float
     */
    protected function getQty($selection, $qtys, $selectionOptionId)
    {
        if ($selection->getSelectionCanChangeQty() && isset($qtys[$selectionOptionId])) {
            $qty = (float)$qtys[$selectionOptionId] > 0 ? $qtys[$selectionOptionId] : 1;
        } else {
            $qty = (float)$selection->getSelectionQty() ? $selection->getSelectionQty() : 1;
        }
        $qty = (float)$qty;

        return $qty;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Object $selection
     * @return float|int
     */
    protected function getBeforeQty($product, $selection)
    {
        $beforeQty = 0;
        $customOption = $product->getCustomOption('product_qty_' . $selection->getId());
        if ($customOption && $customOption->getProduct()->getId() == $selection->getId()) {
            $beforeQty = (float)$customOption->getValue();
            return $beforeQty;
        }

        return $beforeQty;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $isStrictProcessMode
     * @param \Magento\Bundle\Model\Resource\Option\Collection $optionsCollection
     * @param int[] $options
     * @return void
     * @throws \Magento\Framework\Model\Exception
     */
    protected function checkIsAllRequiredOptions($product, $isStrictProcessMode, $optionsCollection, $options)
    {
        if (!$product->getSkipCheckRequiredOption() && $isStrictProcessMode) {
            foreach ($optionsCollection->getItems() as $option) {
                if ($option->getRequired() && !isset($options[$option->getId()])) {
                    throw new \Magento\Framework\Model\Exception(__('Please select all required options.'));
                }
            }
        }
    }

    /**
     * @param \Magento\Bundle\Model\Resource\Selection\Collection $selections
     * @param bool $skipSaleableCheck
     * @param \Magento\Bundle\Model\Resource\Option\Collection $optionsCollection
     * @param int[] $options
     * @return void
     * @throws \Magento\Framework\Model\Exception
     */
    protected function checkSelectionsIsSale($selections, $skipSaleableCheck, $optionsCollection, $options)
    {
        foreach ($selections->getItems() as $selection) {
            if (!$selection->isSalable() && !$skipSaleableCheck) {
                $_option = $optionsCollection->getItemById($selection->getOptionId());
                $optionId = $_option->getId();
                if (is_array($options[$optionId]) && count($options[$optionId]) > 1) {
                    $moreSelections = true;
                } else {
                    $moreSelections = false;
                }
                $isMultiSelection = $_option->isMultiSelection();
                if ($_option->getRequired() && (!$isMultiSelection || !$moreSelections)
                ) {
                    throw new \Magento\Framework\Model\Exception(
                        __('The required options you selected are not available.')
                    );
                }
            }
        }
    }

    /**
     * @param array $_result
     * @return void
     * @throws \Magento\Framework\Model\Exception
     */
    protected function checkIsResult($_result)
    {
        if (is_string($_result)) {
            throw new \Magento\Framework\Model\Exception($_result);
        }

        if (!isset($_result[0])) {
            throw new \Magento\Framework\Model\Exception(
                __('We cannot add this item to your shopping cart.')
            );
        }
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option[] $options
     * @param \Magento\Framework\Object[] $selections
     * @return \Magento\Framework\Object[]
     */
    protected function mergeSelectionsWithOptions($options, $selections)
    {
        foreach ($options as $option) {
            if ($option->getRequired() && count($option->getSelections()) == 1) {
                $selections = array_merge($selections, $option->getSelections());
            } else {
                $selections = [];
                break;
            }
        }

        return $selections;
    }
}
