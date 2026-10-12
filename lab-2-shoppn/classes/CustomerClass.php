<?php
require_once BASE_PATH . '/core/db_class.php';
class CustomerClass extends Database {
  public function emailExists($email) {
    $stmt=$this->conn->prepare('SELECT customer_id FROM customer WHERE customer_email=? LIMIT 1');
    if (!$stmt) throw new RuntimeException('Customer statement preparation failed.', $this->conn->errno);
    $stmt->bind_param('s',$email); if (!$stmt->execute()) throw new RuntimeException('Customer lookup failed.', $stmt->errno); $result=$stmt->get_result(); $exists=$result->num_rows>0; $stmt->close(); return $exists;
  }
  public function addCustomer($name,$email,$pass,$country,$city,$contact) {
    $hash=password_hash($pass,PASSWORD_BCRYPT);
    $stmt=$this->conn->prepare('INSERT INTO customer (customer_name,customer_email,customer_pass,customer_country,customer_city,customer_contact,user_role) VALUES (?,?,?,?,?,?,2)');
    if (!$stmt) throw new RuntimeException('Customer statement preparation failed.', $this->conn->errno);
    $stmt->bind_param('ssssss',$name,$email,$hash,$country,$city,$contact);
    try { $ok=$stmt->execute(); }
    catch (mysqli_sql_exception $error) {
      $stmt->close();
      if ($error->getCode() === 1062) return false;
      throw $error;
    }
    if (!$ok && $stmt->errno !== 1062) { $code=$stmt->errno; $stmt->close(); throw new RuntimeException('Customer insert failed.', $code); } $stmt->close(); return $ok;
  }
  public function getCustomerByEmail($email) {
    $stmt=$this->conn->prepare('SELECT customer_id,customer_name,customer_email,customer_pass,user_role FROM customer WHERE customer_email=? LIMIT 1');
    if (!$stmt) throw new RuntimeException('Customer statement preparation failed.', $this->conn->errno);
    $stmt->bind_param('s',$email); if (!$stmt->execute()) throw new RuntimeException('Customer lookup failed.', $stmt->errno); $row=$stmt->get_result()->fetch_assoc(); $stmt->close(); return $row ?: false;
  }
  public function login($email,$pass) {
    $row=$this->getCustomerByEmail($email);
    if (!$row || !password_verify($pass,$row['customer_pass'])) return false;
    unset($row['customer_pass']); return $row;
  }
}
