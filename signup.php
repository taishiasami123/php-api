<?php

  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }

  require('dbconnect.php');
  $json = file_get_contents("php://input");
  $contents = json_decode($json, true)['sign_up_user_params'];

  $name = $contents['name'];
  $bio = $contents['bio'];
  $email = $contents['email'];
  $password = $contents['password'];
  if ($token == "") {
    $token = 0;
  } else {
    $token = intval($token);
  }
  // $statement = $db->prepare('INSERT INTO users SET name=?, bio=?, email=?, password=?, token=?, created_at=NOW()');
  // $statement->execute(array($name, $bio, $email, $password, $token));
  $select = $db->query('SELECT * FROM users WHERE email="test"');
  $selectResult = $select->fetch();
  sendResponse($selectResult);

  file_put_contents("log1.txt", var_export($selectResult, true));
?>