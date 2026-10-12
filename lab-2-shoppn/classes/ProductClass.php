<?php
require_once BASE_PATH . '/core/db_class.php';
class ProductClass extends Database {
  public function addBrand($name) {
    $s=$this->conn->prepare('INSERT INTO brands (brand_name) VALUES (?)'); $s->bind_param('s',$name); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function getAllBrands() {
    $r=$this->conn->query('SELECT * FROM brands ORDER BY brand_name ASC'); return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
  }
  public function getBrandById($id) {
    $s=$this->conn->prepare('SELECT * FROM brands WHERE brand_id=?'); $s->bind_param('i',$id); $s->execute(); $row=$s->get_result()->fetch_assoc(); $s->close(); return $row ?: false;
  }
  public function updateBrand($id,$name) {
    $s=$this->conn->prepare('UPDATE brands SET brand_name=? WHERE brand_id=?'); $s->bind_param('si',$name,$id); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function addCategory($name) {
    $s=$this->conn->prepare('INSERT INTO categories (cat_name) VALUES (?)'); $s->bind_param('s',$name); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function getAllCategories() {
    $r=$this->conn->query('SELECT * FROM categories ORDER BY cat_name ASC'); return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
  }
  public function getCategoryById($id) {
    $s=$this->conn->prepare('SELECT * FROM categories WHERE cat_id=?'); $s->bind_param('i',$id); $s->execute(); $row=$s->get_result()->fetch_assoc(); $s->close(); return $row ?: false;
  }
  public function updateCategory($id,$name) {
    $s=$this->conn->prepare('UPDATE categories SET cat_name=? WHERE cat_id=?'); $s->bind_param('si',$name,$id); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function addProduct($cat,$brand,$title,$price,$desc,$image,$keywords) {
    $s=$this->conn->prepare('INSERT INTO products (product_cat,product_brand,product_title,product_price,product_desc,product_image,product_keywords) VALUES (?,?,?,?,?,?,?)');
    $s->bind_param('iisdsss',$cat,$brand,$title,$price,$desc,$image,$keywords); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function updateProduct($id,$cat,$brand,$title,$price,$desc,$image,$keywords) {
    $s=$this->conn->prepare('UPDATE products SET product_cat=?,product_brand=?,product_title=?,product_price=?,product_desc=?,product_image=?,product_keywords=? WHERE product_id=?');
    $s->bind_param('iisdsssi',$cat,$brand,$title,$price,$desc,$image,$keywords,$id); $ok=$s->execute(); $s->close(); return $ok;
  }
  public function getProductById($id) {
    $s=$this->conn->prepare('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id WHERE p.product_id=?');
    $s->bind_param('i',$id); $s->execute(); $row=$s->get_result()->fetch_assoc(); $s->close(); return $row ?: false;
  }
  public function getAllProducts() {
    $r=$this->conn->query('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id ORDER BY p.product_id DESC');
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
  }
  public function getFeaturedProducts($limit=6) {
    $limit=max(1,min(24,(int)$limit));
    $s=$this->conn->prepare('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id ORDER BY RAND() LIMIT ?');
    $s->bind_param('i',$limit); $s->execute(); $rows=$s->get_result()->fetch_all(MYSQLI_ASSOC); $s->close(); return $rows ?: [];
  }
  public function getProductsByCategory($cat_id) {
    $s=$this->conn->prepare('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id WHERE p.product_cat=? ORDER BY p.product_title');
    $s->bind_param('i',$cat_id); $s->execute(); $rows=$s->get_result()->fetch_all(MYSQLI_ASSOC); $s->close(); return $rows ?: [];
  }
  public function getProductsByBrand($brand_id) {
    $s=$this->conn->prepare('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id WHERE p.product_brand=? ORDER BY p.product_title');
    $s->bind_param('i',$brand_id); $s->execute(); $rows=$s->get_result()->fetch_all(MYSQLI_ASSOC); $s->close(); return $rows ?: [];
  }
  public function searchProducts($query) {
    $like='%'.$query.'%';
    $s=$this->conn->prepare('SELECT p.*, c.cat_name, b.brand_name FROM products p LEFT JOIN categories c ON p.product_cat=c.cat_id LEFT JOIN brands b ON p.product_brand=b.brand_id WHERE p.product_title LIKE ? OR p.product_keywords LIKE ? ORDER BY p.product_title');
    $s->bind_param('ss',$like,$like); $s->execute(); $rows=$s->get_result()->fetch_all(MYSQLI_ASSOC); $s->close(); return $rows ?: [];
  }
}
