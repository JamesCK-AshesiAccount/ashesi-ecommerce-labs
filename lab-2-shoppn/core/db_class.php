<?php
class Database {
  protected $conn;
  public function __construct() {
    require_once __DIR__ . '/db_cred.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $this->conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($this->conn->connect_error) throw new RuntimeException('Database connection unavailable.', $this->conn->connect_errno);
    if (!$this->conn->set_charset('utf8mb4')) throw new RuntimeException('Database character set unavailable.');
  }
}
