<?php
require '../vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$database = $client->config;
$collection = $database->profile;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] =="logout") {
  $email    = $_POST['email'];
  $redis = new Redis();
            
  if (!$redis->connect('127.0.0.1', 6379)) {
      http_response_code(400);
      echo ('Unable to connect to Redis server');
      
  } else {
      
      $redis->connect('127.0.0.1', 6379);
      $redis->del($email);
      http_response_code(200);
      echo "Logged out successfully";     
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] =="update") {

 
  $redis = new Redis();
        
  if (!$redis->connect('127.0.0.1', 6379)) {
      http_response_code(400);
      echo ('Unable to connect to Redis server');
      
  } else {
      

$name = $_POST['name'];
$dob = $_POST['dob'];
$contact = $_POST['contact'];
$email = $_POST['email'];

$filter = ['email' => $email];
$result = $collection->findOne($filter);

if ($result !== null) {
   $update = [];
  
    if (isset($name) && $name != $result['name']) {
        $update['name'] = $name;
    }

    if (isset($dob) && $dob != $result['dob']) {
        $update['date_of_birth'] = $dob;
    }
    if (isset($contact) && $contact != $result['contact']) {
        $update['contact'] = $contact;
    }


    $updateResult = $collection->updateOne(
        ['email' => $email],
        ['$set' => $update]
    );
    if ($updateResult->getModifiedCount() === 1) {
      http_response_code(200);
      echo "Profile updated Succesfully";
    } else {
        http_response_code(400);
          echo "Profile updated Succesfully";
      }
} else {
    // Insert a new document with the input data
    $insertResult = $collection->insertOne([
        'email' => $email,
        'name' => $name,
        'dob' => $dob,
        'contact' => $contact
    ]);
    if ($insertResult->getInsertedCount() === 1) {
        http_response_code(200);
        echo "Profile updated Succesfully";
    } else {
        http_response_code(400);
        echo "Document insert failed.";
    }
}  
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] =="getinfo") {
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$email = $_POST['email'];
$result = $collection->findOne(['email' => $email]);
header('Content-Type: application/json');
echo json_encode($result);

}


?>

