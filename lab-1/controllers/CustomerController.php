<?php
require_once BASE_PATH . '/classes/CustomerClass.php';
require_once BASE_PATH . '/core/registration_validation.php';
class CustomerController {
  private $customer;
  public function __construct() { $this->customer=new CustomerClass(); }
  public function register($name,$email,$pass,$country,$city,$contact,$confirm) {
    $email = strtolower(trim($email));
    $errors = registration_errors(['customer_name'=>$name,'customer_email'=>$email,'customer_pass'=>$pass,'customer_country'=>$country,'customer_city'=>$city,'customer_contact'=>$contact,'confirm_password'=>$confirm]);
    if ($errors) return ['success'=>false,'message'=>reset($errors)];
    if ($this->customer->emailExists($email)) return ['success'=>false,'message'=>'An account with this email already exists.'];
    if (!$this->customer->addCustomer($name,$email,$pass,$country,$city,$contact)) return ['success'=>false,'message'=>'Registration failed. Please try again.'];
    $customer = $this->customer->getCustomerByEmail($email);
    if (!$customer) throw new RuntimeException('New customer could not be loaded.');
    unset($customer['customer_pass']);
    return ['success'=>true,'message'=>'Registration successful. Welcome to Shoppn.','customer'=>$customer];
  }
  public function login($email,$pass) { return $this->customer->login($email,$pass); }
}
