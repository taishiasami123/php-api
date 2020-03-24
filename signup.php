<?php
  require('dbconnect.php');
  $json = file_get_contents("php://input");
  $contents = json_decode($json, true)['sign_up_user_params'];
  $name = $contents['name'];
  $bio = $contents['bio'];
  $email = $contents['email'];
  $password = $contents['password'];
  file_put_contents("log.txt", var_export($name, true));
  // $statement = $db->prepare('INSERT INTO users SET name=?, bio=?, email=?, password=?, token=?, created=NOW()');
  // $statement->execute(array($name, $bio, $email, $password, $token));
?>