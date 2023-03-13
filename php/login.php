<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] =="check") {
  $email    = $_POST['email'];
  $sessionID = $_POST['session'];
  $redis = new Redis();
            
  if (!$redis->connect('127.0.0.1', 6379)) {
      http_response_code(400);
      echo ('Unable to connect to Redis server');
      
  } else {
      
      $redis->connect('127.0.0.1', 6379);
     
      if ($redis->get($email) == $sessionID) {
        
        http_response_code(200);
        echo "valid";

      } 
      
  }




}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] =="auth") {
    $email    = $_POST['email'];
    $password = $_POST['password'];
    
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'test';
    
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    
    if ($conn->connect_error) {
        http_response_code(400);
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            
            $redis = new Redis();
            
            if (!$redis->connect('127.0.0.1', 6379)) {
                http_response_code(400);
                echo ('Unable to connect to Redis server');
                
            } else {
                
                $redis->connect('127.0.0.1', 6379);
                

                if ($redis->exists($email)) {

                  echo $redis->get($email);

                }
                else{
                  $ssid = session_create_id();
                  $redis->set($email, $ssid);
                
                  echo $ssid;
                }
               
                
            }
            
        } else {
            http_response_code(400);
            echo "Invalid Login credentials";
        }
    } else {
        http_response_code(400);
        echo "Invalid Login credentials";
    }
    
    $stmt->close();
    $conn->close();
}
?>