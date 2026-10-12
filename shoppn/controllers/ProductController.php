<?php

require_once __DIR__ . "/../classes/ProductClass.php";

class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    public function addBrand($name) { return $this->product->addBrand($name); }
    public function getAllBrands() { return $this->product->getAllBrands(); }
    public function updateBrand($id, $name) { return $this->product->updateBrand($id, $name); }
    public function deleteBrand($id) { return $this->product->deleteBrand($id); }

    public function addCategory($name) { return $this->product->addCategory($name); }
    public function getAllCategories() { return $this->product->getAllCategories(); }
    public function getCategoryById($id) { return $this->product->getCategoryById($id); }
    public function updateCategory($id, $name) { return $this->product->updateCategory($id, $name); }
    public function deleteCategory($id) { return $this->product->deleteCategory($id); }

    public function getLatestBrandIdByName($name) { return $this->product->getLatestBrandIdByName($name); }
    public function setBrandCategories($id, $catIds) { return $this->product->setBrandCategories($id, $catIds); }
    public function getBrandsWithCategories() { return $this->product->getBrandsWithCategories(); }

    public function addProduct($cat, $brand, $title, $price, $desc, $image, $keywords) { return $this->product->addProduct($cat, $brand, $title, $price, $desc, $image, $keywords); }
    public function getAllProducts() { return $this->product->getAllProducts(); }
    public function getProductById($id) { return $this->product->getProductById($id); }
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords) { return $this->product->updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords); }
public function deleteProduct($id) { return $this->product->deleteProduct($id); }
public function getFilteredProducts($catId = 0, $brandId = 0) { return $this->product->getFilteredProducts($catId, $brandId); }
}