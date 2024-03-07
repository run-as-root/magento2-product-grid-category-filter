<?php

declare(strict_types=1);

namespace RunAsRoot\ProductGridCategoryFilter\Model\Category;

use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

class CategoryList implements OptionSourceInterface
{
    public function __construct(private readonly CollectionFactory $collectionFactory)
    {
    }

    /**
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect([ 'name', 'is_active', 'parent_id' ]);
        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value' => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $category) {
            foreach ([ $category->getId(), $category->getParentId() ] as $categoryId) {
                if (isset($categoryById[$categoryId])) {
                    continue;
                }

                $categoryById[$categoryId] = [ 'value' => $categoryId ];
            }

            $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'] ?? [];
    }
}
