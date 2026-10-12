<?php
// Isolated tests: no real database connection or customer writes.
define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/controllers/CustomerController.php';
function check($condition, $message) {
  if (!$condition) throw new RuntimeException($message);
  echo "PASS: $message\n";
}
$data = ['customer_name'=>'Test Customer','customer_email'=>'test@example.com','customer_pass'=>'Cedar!River2026','confirm_password'=>'Cedar!River2026','customer_country'=>'Ghana','customer_city'=>'Accra','customer_contact'=>'+233 123456789',];
check(!registration_errors($data), 'Valid registration accepted');
foreach (['Short1!','abcdefghijkl1!','ABCDEFGHIJKL1!','Abcdefghijkl!!','Abcdefghijkl12','Password1234!','Qwerty123456!'] as $password) check(registration_password_error($password) !== '', 'Weak password rejected');
check(registration_password_error(str_repeat('Ab1!',18)) === '', '72-byte password accepted');
check(registration_password_error(str_repeat('Ab1!',19)) !== '', 'Bcrypt byte overflow rejected');
check(registration_password_error('Ab1!' . str_repeat('é',35)) !== '', 'Unicode byte overflow rejected');
foreach (['confirm_password'=>'Different!1234','customer_email'=>'invalid','customer_contact'=>'letters','customer_name'=>'A'] as $field=>$value) {
  $invalid=$data; $invalid[$field]=$value;
  check(isset(registration_errors($invalid)[$field]), "$field validation enforced");
}
class RegistrationStatement {
  public $errno=0, $values=[], $num_rows=0;
  public function bind_param($types, &...$values) { $this->values=$values; }
  public function execute() { return $this->errno === 0; }
  public function get_result() { return $this; }
  public function fetch_assoc() { return ['customer_id'=>1,'customer_name'=>'Test Customer','customer_email'=>'test@example.com','customer_pass'=>'hash','user_role'=>2]; }
  public function close() {}
}
class RegistrationConnection {
  public $errno=0, $sql='', $statement;
  public function __construct() { $this->statement=new RegistrationStatement(); }
  public function prepare($sql) { $this->sql=$sql; return $this->statement; }
}
class RegistrationCustomer extends CustomerClass {
  public $fake;
  public function __construct() { $this->fake=new RegistrationConnection(); $this->conn=$this->fake; }
}
$model=new RegistrationCustomer();
check($model->addCustomer('Test','test@example.com',$data['customer_pass'],'Ghana','Accra','123456789'), 'Prepared customer insert succeeds');
check(strpos($model->fake->sql,'customer_address') === false && count($model->fake->statement->values) === 6, 'Prepared insert works without an address column');
check(strpos($model->fake->sql,'user_role) VALUES (?,?,?,?,?,?,2)') !== false, 'New customers are explicitly assigned role 2');
check(password_verify($data['customer_pass'],$model->fake->statement->values[2]), 'Stored password uses verifiable hashing');
$model->fake->statement->errno=1062;
check(!$model->addCustomer('Test','test@example.com',$data['customer_pass'],'Ghana','Accra','123456789'), 'Duplicate race handled without fatal error');
$model->fake->statement->errno=1054;
try { $model->addCustomer('Test','test@example.com',$data['customer_pass'],'Ghana','Accra','123456789'); check(false,'Database failure throws'); }
catch (RuntimeException $error) { check($error->getCode() === 1054,'Database failure can be caught by action'); }
$model->fake->statement->errno=0;
$controller=(new ReflectionClass(CustomerController::class))->newInstanceWithoutConstructor();
$property=new ReflectionProperty(CustomerController::class,'customer'); $property->setAccessible(true); $property->setValue($controller,$model);
$args=['Test Customer','test@example.com',$data['customer_pass'],'Ghana','Accra','123456789',$data['customer_pass']];
check($controller->register(...$args)['success'], 'Controller accepts valid details');
$model->fake->statement->num_rows=1;
check(!$controller->register(...$args)['success'], 'Controller rejects duplicate email');
$args[6]='Mismatch123!'; check(!$controller->register(...$args)['success'], 'Controller enforces confirmation');
