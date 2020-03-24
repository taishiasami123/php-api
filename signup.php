<?php
  // jsonを返して死ぬやつ
  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }

  // dbに繋ぐ
  require('dbconnect.php');

  // jsonを取得
  $json = file_get_contents("php://input");
  $contents = json_decode($json, true)['sign_up_user_params'];
  $name = $contents['name'];
  $bio = $contents['bio'];
  $email = $contents['email'];
  $pwd = $contents['password'];
  $pwdCfm = $contents['password_confirmation'];

  // token生成
  $salt = "phpapi";
  $seed = $salt.$email;
  $token = hash('sha256', $seed);

  // blankチェック
  if ($name == "") {
    $errorMessage = "Validation failed: Name can't be blank";
    sendResponse($errorMessage);
  } elseif ($bio == "") {
    $errorMessage = "Validation failed: Bio can't be blank";
    sendResponse($errorMessage);
  } elseif ($email == "") {
    $errorMessage = "Validation failed: Email can't be blank";
    sendResponse($errorMessage);
  } elseif ($pwd == "") {
    $errorMessage = "Validation failed: Password can't be blank";
    sendResponse($errorMessage);

  // pwd一致チェック
  } elseif ($pwd != $pwdCfm) {
    $errorMessage = "Validation failed: Password confirmation doesn't match password";
    sendResponse($errorMessage);
  }

  // email重複チェック
  $emailCheck = $db->prepare("SELECT * FROM users WHERE email = :email");
  $emailCheck->bindValue(':email', $email, PDO::PARAM_STR);
  try {
    $emailCheck->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $fetchAllResult = $emailCheck->fetchAll(PDO::FETCH_ASSOC);
  if (count($fetchAllResult) >= 1) {
    $errorMessage = "そのemailは登録されている";
    sendResponse($errorMessage);
  }

  // db登録処理
  $stmt = $db->prepare('INSERT INTO users SET name = :name, bio = :bio, email = :email, password = :pwd, token = :token, created_at = NOW()');
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':bio', $bio, PDO::PARAM_STR);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $pwd = hash('sha256', $pwd); // pwdハッシュ化
  $stmt->bindValue(':pwd', $pwd, PDO::PARAM_STR);
  $stmt->bindValue(':token', $token, PDO::PARAM_STR);
  try {
    $stmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }

  // dbからemailが一致するレコードを取得して返却
  $select = $db->prepare('SELECT * FROM users WHERE email = :email');
  $select->bindValue(':email', $email, PDO::PARAM_STR);
  try {
    $select->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $selectResult = $select->fetchAll(PDO::FETCH_ASSOC);
  unset($selectResult[0]['password']); // 配列からpassword要素を削除
  sendResponse($selectResult[0]);

?>