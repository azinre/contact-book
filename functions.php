<?php
  require "db.php";

  /**
   * formatPhone
   * Formats a 10 digit phone number
   * @param string $phone
   * @return string
   */
  function formatPhone ($phone) {
    if (strlen($phone) !== 10) {
      return $phone;
    }
    
    $area_code = substr($phone, 0, 3);
    $prefix = substr($phone, 3, 3);
    $line_number = substr($phone, 6, 4);
    return "({$area_code}) {$prefix}-{$line_number}";
  }

  /**
   * sanitize
   * Sanitizes data from a form submission
   * @param array $data
   * @return array
   */
  function sanitize ($data) {
    foreach ($data as $key => $value) {
      if ($key === 'phone') {
        $value = preg_replace('/[^0-9]/', '', $value);
      } 

      $data[$key] = htmlspecialchars(stripslashes(trim($value)));
    }

    return $data;
  }

  /**
   * getContacts
   * Retrieves all contacts from the database
   * @return array
   */
  // function getContacts () {
  //   // replace the following with a call to the database
  //   $contacts = [
  //     [
  //       "id" => 1,
  //       "first_name" => "John",
  //       "last_name" => "Smith",
  //       "email" => "john.smith@email.com",
  //       "phone" => "5555555555",
  //       "birthday" => "1990-01-01"
  //     ],
  //   ];

  //   return $contacts;
  // }

  function getContacts() {
    global $db; 
    try {
      $query = "SELECT * FROM contacts";
      $statement = $db->query($query);
      $contacts = $statement->fetchAll(PDO::FETCH_ASSOC); 
      return $contacts;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return [];
    }
  }
  
  /**
   * searchContacts
   * Retrieves contacts from the database that match the search term
   * @param string $search
   * @return array
   */
  // function searchContacts ($search) {
  //   // replace the following with a call to the database
  //   $contacts = [
  //     [
  //       "id" => 1,
  //       "first_name" => "John",
  //       "last_name" => "Smith",
  //       "email" => "john.smith@email.com",
  //       "phone" => "5555555555",
  //       "birthday" => "1990-01-01"
  //     ],
  //   ];

  //   return $contacts;
  // }

  function searchContacts($search) {
    global $db; 
    try {     
      $query = "SELECT * FROM contacts WHERE first_name LIKE :search OR last_name LIKE :search";
      $statement = $db->prepare($query);
      $statement->bindValue(':search', "%$search%", PDO::PARAM_STR);
      $statement->execute();
      $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);
      return $contacts;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return []; 
    }
  }
  
  /**
   * getContact
   * Retrieves a single contact from the database
   * @param int $id
   * @return array
   */
  // function getContact ($id) {
  //   global  $db;

  //   // replace the following with a call to the database
  //   // $contact = [
  //   //     "id" => 1,
  //   //     "first_name" => "John",
  //   //     "last_name" => "Smith",
  //   //     "email" => "john.smith@email.com",
  //   //     "phone" => "5555555555",
  //   //     "birthday" => "1990-01-01"
  //   // ];

  //   return $contact;
  // }
  function getContact($id) {
    global $db;
  
    $query = "SELECT * FROM contacts WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->execute(['id' => $id]);
  
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
  
  
  /**
   * validate
   * Validates the data from the form
   * @param array $data
   * @return array $errors
   */
  function validate ($data) {
    $fields = ['first_name', 'last_name', 'email', 'phone', 'birthday'];
    $errors = [];

    foreach ($fields as $field) {
      
      switch ($field) {
        case 'first_name':
          
          // update the conditions to match the requirements
          if (empty($data[$field])) {
            $errors[$field] = 'First name is required';
          } elseif (!preg_match('/^[a-zA-Z]+$/', $data[$field])) {
            $errors[$field] = 'First name must contain only letters';
          }
          break;
          
          break;
        case 'last_name':

            // update the conditions to match the requirements
            if (empty($data[$field])) {
              $errors[$field] = 'Last name is required';
            } elseif (!preg_match('/^[a-zA-Z]+$/', $data[$field])) {
              $errors[$field] = 'Last name must contain only letters';
            }

          break;
        case 'email':

          // update the conditions to match the requirements
          if (empty($data[$field])) {
            $errors[$field] = 'Email is required';
          } elseif (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = 'Email is invalid';
          }

          break;
        case 'phone':
          
          // update the conditions to match the requirements
          
          if (!empty($data[$field]) && !preg_match('/^\d{15}$/', $data[$field])) {
            $errors[$field] = 'Phone number is invalid';
          }
          
          break;
          
        case 'birthday':

          // update the conditions to match the requirements
          if (!empty($data[$field]) && !strtotime($data[$field])) {
            $errors[$field] = 'Birthday is invalid';
          }
          break;
      }
    }

    return $errors;
  }

  /**
   * createContact
   * Creates a new contact in the database
   * @param array $data
   * @return int
   */
  // function createContact ($data) {
  //   // replace the following with a call to the database returning the new contact id

  //   return 1; 
  // }

  function createContact($data) {
    $host = 'localhost';
    $dbname = 'contact_book'; 
    $username = 'root'; 
    $password = ''; 

  $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $sql = "INSERT INTO contacts (first_name, last_name, email, phone, birthday) 
          VALUES (:first_name, :last_name, :email, :phone, :birthday)";

  $stmt = $db->prepare($sql);
  $stmt->bindParam(':first_name', $data['first_name']);
  $stmt->bindParam(':last_name', $data['last_name']);
  $stmt->bindParam(':email', $data['email']);
  $stmt->bindParam(':phone', $data['phone']);
  $stmt->bindParam(':birthday', $data['birthday']);

  if ($stmt->execute()) {
    return $db->lastInsertId();
  }

  return false;
}
  
  
  
  
  
  /**
   * updateContact
   * Updates a contact in the database
   * @param array $data
   * @return bool
   */
  
   function updateContact($data)
   {
       $host = 'localhost';
       $dbname = 'contact_book'; 
       $username = 'root'; 
       $password = '';
   
       $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $sql = "UPDATE contacts SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, birthday = :birthday WHERE id = :id";
   
       
       $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
   
       $stmt = $db->prepare($sql);
   
       if ($stmt->execute($data)) {
           return true;
       }
   
       return false;
   }
   
  
    

  

  /**
   * deleteContact
   * Deletes a contact from the database
   * @param int $id
   * @return bool
   */
  // functions.php

function deleteContact($contactId)
{
    $host = 'localhost';
    $dbname = 'contact_book'; 
    $username = 'root'; 
    $password = '';

    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $sql = "DELETE FROM contacts WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $contactId);

    if ($stmt->execute()) {
        return true;
    }

    return false;
}
