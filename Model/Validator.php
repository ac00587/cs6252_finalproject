<?php
class Validator {
  
    /**
     * Validates new usernames
     * @param type $username
     * @return string
     */
    public function validateUserName($username) {
      $error = '';
      
      $pattern = '/^(?=.*[[:digit:]])[[:alnum:]]{6,20}$/';
      $check = preg_match($pattern, $username);
      
      if(!$check) {
          $error = 'Username must have a letter and digit. 6 - 20 chars in length.';
      }
      
      return $error;
  }  
  
  /**
   * Validates new password
   * @param type $password
   * @return string
   */
  public function validatePassword($password) {
      $error = '';
      
      $pattern = '/^(?=.*[[:digit:]])(?=.*[[:upper:]])(?=.*[[:lower:]])[[:alnum:]]{6,30}$/';
      $check = preg_match($pattern, $password);
      
      if(!$check) {
          $error = 'Password must have at least 1 upper/lowercase and digit. 6 - 30 chars in length.';
      }
      
      return $error;
  }  
  
  /**
   * Validates new first name
   * @param type $first_name
   * @return string
   */
  public function validateFirstName($first_name) {
      $error = '';
      
      $pattern = '/^[[:alpha:]]{3,}$/';
      $check = preg_match($pattern, $first_name);
      
      if(!$check) {
          $error = 'First name must be at least 3 letters.';
      }
      
      return $error;
  }  
  
  /**
   * Validates new last name
   * @param type $first_name
   * @return string
   */
  public function validateLastName($last_name) {
      $error = '';
      
      $pattern = '/^[[:alpha:]]{3,}$/';
      $check = preg_match($pattern, $last_name);
      
      if(!$check) {
          $error = 'Last name must be at least 3 letters.';
      }
      
      return $error;
  }  
  
  public function validateEmail($email) {
      $error = '';
      
      $pattern = '/^[[:print:]]+@[[:alnum:]]+.[[:lower:]]+$/';
      $check = preg_match($pattern, $email);
      
      if(!$check) {
          $error = 'Email must be in the format a@b.c.';
      }
      
      return $error;
  }  
  
  public function validateCharName($char_name) {
      $error = '';
      
      $pattern = '/^[[:upper:]][[:alpha:]]{2,}$/';
      $check = preg_match($pattern, $char_name);
      
      if(!$check) {
          $error = 'Character name must start with a capital letter and have at least 3 letters.';
      }
      
      return $error;
  }  
  
  
}
?>