<?php

declare(strict_types=1);

namespace RunAsRoot\ProductGridCategoryFilter\Test\Unit\Ui\Component\Listing\Column;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\ProductCategoryList;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RunAsRoot\ProductGridCategoryFilter\Ui\Component\Listing\Column\Category;

final class CategoryTest extends TestCase
{
    private Category $sut;
    private ProductCategoryList|MockObject $productCategoryMock;
    private CategoryRepositoryInterface|MockObject $categoryRepositoryMock;
    private ContextInterface|MockObject $contextMock;
    private UiComponentFactory|MockObject $uiComponentFactoryMock;

    public function setUp(): void
    {
        $this->productCategoryMock = $this->createMock(ProductCategoryList::class);
        $this->categoryRepositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->uiComponentFactoryMock = $this->createMock(UiComponentFactory::class);
        $components = [];
        $data = [ 'name' => 'test' ];
        $this->sut = new Category(
            $this->contextMock,
            $this->uiComponentFactoryMock,
            $this->productCategoryMock,
            $this->categoryRepositoryMock,
            $components,
            $data
        );
    }

    public function test_it_should_prepare_correct_data_source(): void
    {
        $dataSource = [
            'data' => [
                'items' => [
                    [
                        'entity_id' => 1231,
                    ],
                ],
            ],
        ];

        $expectedResult = [
            'data' => [
                'items' => [
                    [
                        'entity_id' => 1231,
                        'test' => 'Category Name 1,Category Name 2,Category Name 5',
                    ],
                ],
            ],
        ];
        $categoryIds = [ 1, 2, 5 ];
        $categoryIdsConsecutive = [ [ 1, null ], [ 2, null ], [ 5, null ] ];
        $categoryMocks = $this->getCategoryMocks($categoryIds);

        $this->productCategoryMock->expects($this->once())
            ->method('getCategoryIds')
            ->with(1231)
            ->willReturn($categoryIds);

        $this->categoryRepositoryMock->expects($this->exactly(count($categoryIds)))
            ->method('get')
            ->withConsecutive(...$categoryIdsConsecutive)
            ->willReturnOnConsecutiveCalls(...$categoryMocks);

        $dataSource = $this->sut->prepareDataSource($dataSource);

        $this->assertEquals($expectedResult, $dataSource);
    }

    private function getCategoryMocks(array $categoryIds): array
    {
        $categoryMocks = [];

        foreach ($categoryIds as $categoryId) {
            $categoryMock = $this->createMock(CategoryInterface::class);
            $categoryMock->expects($this->once())
                ->method('getName')
                ->willReturn('Category Name ' . $categoryId);
            $categoryMocks[] = $categoryMock;
        }

        return $categoryMocks;
    }
}
