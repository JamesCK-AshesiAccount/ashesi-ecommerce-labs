<?php
require_once BASE_PATH . '/classes/ProductClass.php';
class ProductController {
  private $products;
  public function __construct() { $this->products=new ProductClass(); }
  public function addBrand($name) { return $this->products->addBrand($name); }
  public function getAllBrands() { return $this->products->getAllBrands(); }
  public function getBrandById($id) { return $this->products->getBrandById($id); }
  public function updateBrand($id,$name) { return $this->products->updateBrand($id,$name); }
  public function addCategory($name) { return $this->products->addCategory($name); }
  public function getAllCategories() { return $this->products->getAllCategories(); }
  public function getCategoryById($id) { return $this->products->getCategoryById($id); }
  public function updateCategory($id,$name) { return $this->products->updateCategory($id,$name); }
  public function addProduct($cat,$brand,$title,$price,$desc,$image,$keywords) { return $this->products->addProduct($cat,$brand,$title,$price,$desc,$image,$keywords); }
  public function updateProduct($id,$cat,$brand,$title,$price,$desc,$image,$keywords) { return $this->products->updateProduct($id,$cat,$brand,$title,$price,$desc,$image,$keywords); }
  public function getProductById($id) { return $this->products->getProductById($id); }
  public function getAllProducts() { return $this->products->getAllProducts(); }
  public function getFeaturedProducts($limit=6) { return $this->products->getFeaturedProducts($limit); }
  public function getProductsByCategory($id) { return $this->products->getProductsByCategory($id); }
  public function getProductsByBrand($id) { return $this->products->getProductsByBrand($id); }
  public function searchProducts($query) { return $this->products->searchProducts($query); }
}
