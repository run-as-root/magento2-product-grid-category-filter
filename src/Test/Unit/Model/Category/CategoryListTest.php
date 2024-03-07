<?php

declare(strict_types=1);

namespace RunAsRoot\ProductGridCategoryFilter\Test\Unit\Model\Category;

use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\DataObject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RunAsRoot\ProductGridCategoryFilter\Model\Category\CategoryList;

final class CategoryListTest extends TestCase
{
    private CategoryList $sut;
    private CollectionFactory|MockObject $categoryCollectionFactory;

    public function setUp(): void
    {
        $this->categoryCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->sut = new CategoryList($this->categoryCollectionFactory);
    }

    public function test_it_should_return_correct_options(): void
    {
        $category = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->addMethods(['getId', 'getParentId', 'getIsActive', 'getName'])
            ->getMock();
        $category->expects($this->exactly(4))
            ->method('getId')
            ->willReturn(1);
        $category->expects($this->exactly(2))
            ->method('getParentId')
            ->willReturn(0);
        $category->expects($this->once())
            ->method('getIsActive')
            ->willReturn(true);
        $category->expects($this->once())
            ->method('getName')
            ->willReturn('Zzzz');

        $iterator = new \ArrayIterator([$category]);

        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->expects($this->once())
            ->method('addAttributeToSelect')
            ->with([ 'name', 'is_active', 'parent_id' ]);
        $collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn($iterator);
        $this->categoryCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $result = $this->sut->toOptionArray();

        $this->assertEquals([], $result);
    }
}
