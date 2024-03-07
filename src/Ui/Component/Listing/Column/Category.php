<?php

declare(strict_types=1);

namespace RunAsRoot\ProductGridCategoryFilter\Ui\Component\Listing\Column;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ProductCategoryList;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Category extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly ProductCategoryList $productCategory,
        private readonly CategoryRepositoryInterface $categoryRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        $fieldName = $this->getData('name');

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $productId = (int)$item['entity_id'];
                $categoryIds = $this->getCategoryIds($productId);
                $categories = [];

                if (count($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $categoryData = $this->categoryRepository->get($categoryId);
                        $categories[] = $categoryData->getName();
                    }
                }

                $item[$fieldName] = implode(',', $categories);
            }
        }

        return $dataSource;
    }

    private function getCategoryIds(int $productId): array
    {
        $categoryIds = $this->productCategory->getCategoryIds($productId);
        $category = [];

        if ($categoryIds) {
            $category = array_unique($categoryIds);
        }

        return $category;
    }
}
