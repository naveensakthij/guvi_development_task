<?php


$email = $_POST['email'];
$password = $_POST['password'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

  echo("$email is not a valid email address");
  exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = mysqli_connect($servername, $username, $password, $dbname);


$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){
  
  echo "User already exists";
}
else{

  $stmt = mysqli_prepare($conn, "INSERT INTO users (email, password) VALUES (?, ?)");
  mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
  if(mysqli_stmt_execute($stmt)){
    require '../vendor/autoload.php';
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $database = $client->config;
    $collection = $database->profile;
    $insertResult = $collection->insertOne([
      'email' => $email,
      'name' => null,
      'dob' => null,
      'contact' => null
  ]);
  if ($insertResult->getInsertedCount() === 1) {
      http_response_code(200);
      echo "Success, you can log in now";
  } else {
      http_response_code(400);
      echo "Document insert failed.";
  }
    echo "Success, you can log in to database";
  }
  else{
  
    echo "Error";
  }
}

mysqli_close($conn);

?>
