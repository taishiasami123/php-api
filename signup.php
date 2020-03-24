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
  $pwd = $contents['password'];
  if ($token == "") {
    $token = 0;
  } else {
    $token = intval($token);
  }

  $emailCheck = $db->prepare("SELECT * FROM users WHERE email = :email");
  $emailCheck->bindValue(':email', $email, PDO::PARAM_STR);
  $emailCheck->execute();
  $fetchAllResult = $emailCheck->fetchAll(PDO::FETCH_ASSOC);

  if (count($fetchAllResult) >= 1) {
    $errorMessage = "そのemailは登録されている";
    sendResponse($errorMessage);
  } else {

  $stmt = $db->prepare('INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :pwd, token = :token, created_at = NOW()');
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':bio', $bio, PDO::PARAM_STR);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':pwd', $pwd, PDO::PARAM_STR);
  $stmt->bindValue(':token', $token, PDO::PARAM_STR);
  $stmt->execute();

  $select = $db->prepare('SELECT * FROM users WHERE email = :email');
  $select->bindValue(':email', $email, PDO::PARAM_STR);
  $select->execute();
  $selectResult = $select->fetchAll(PDO::FETCH_ASSOC);
  sendResponse($selectResult[0]);

  }
?>