![Magento 2](https://img.shields.io/badge/Magento-2.4.*-orange)
![PHP](https://img.shields.io/badge/php-8.1-blue)
![PHP](https://img.shields.io/badge/php-8.2-blue)
![packagist](https://img.shields.io/badge/packagist-f28d1a)
![build](https://github.com/run-as-root/magento2-product-grid-category-filter/actions/workflows/test_extension.yml/badge.svg)

## Overview 

This open-source Magento 2 module enhances the functionality of the Magento Admin Product Grid by introducing the ability to filter products by categories directly from the product grid interface.  
By enabling category-based filtering, users can quickly and easily find products within specific categories.

After installing the module you will see a new column in the product grid called "Categories". This column will display the categories that the product is assigned to.

An example of the category filter in action is shown below:

![category-filter-opened.png](docs%2Fcategory-filter-opened.png)


![category-filter.png](docs%2Fcategory-filter.png)

An example of the category column on the product grid is shown below:
![product-grid.png](docs%2Fproduct-grid.png)

## Installation

### Via Composer

1. Run the following command in your Magento 2 root directory to install the module via Composer:

```bash
composer require run-as-root/magento2-product-grid-category-filter
```

2. Enabling the module by running:

```bash
php bin/magento module:enable RunAsRoot_ProductGridCategoryFilter
```

## Configuration

No additional configuration is required after installation. The category filter will automatically appear in the Admin Product Grid, allowing you to filter products by categories immediately.

## Support

This module is provided as-is, but we welcome contributions and feedback on GitHub. Please feel free to submit issues or pull requests to improve the module.

## License

This module is open-source and released under the [MIT Licence](https://opensource.org/licenses/MIT).
