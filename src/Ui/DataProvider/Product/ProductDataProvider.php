<?php

declare(strict_types=1);

namespace RunAsRoot\ProductGridCategoryFilter\Ui\DataProvider\Product;

use Magento\Catalog\Ui\DataProvider\Product\ProductCollection;
use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider as MagentoProductDataProvider;
use Magento\Framework\Api\Filter;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ProductDataProvider extends MagentoProductDataProvider
{
    public function addFilter(Filter $filter): void
    {
        /** @var AbstractCollection|ProductCollection $collection */
        $collection = $this->getCollection();

        if ($collection instanceof ProductCollection && $filter->getField() === 'category_id') {
            $collection->addCategoriesFilter([ 'in' => $filter->getValue() ]);
            return;
        }

        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $condition = [ $filter->getConditionType() => $filter->getValue() ];
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter($this->getCollection(), $filter->getField(), $condition); // @phpstan-ignore-line
            return;
        }

        parent::addFilter($filter);
    }
}
