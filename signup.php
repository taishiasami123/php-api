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
  $params = json_decode($json, true)['sign_up_user_params'];
  $name = $params['name'];
  $bio = $params['bio'];
  $email = $params['email'];
  $pwd = $params['password'];
  $pwdCfm = $params['password_confirmation'];

  // token生成
  $salt = "phpapi";
  $seed = $salt.$email;
  $token = hash('sha256', $seed);

  // blankチェック
  if ($name == "") {
    $errMsg = "Validation failed: Name can't be blank";
    sendResponse($errMsg);
  } elseif ($bio == "") {
    $errMsg = "Validation failed: Bio can't be blank";
    sendResponse($errMsg);
  } elseif ($email == "") {
    $errMsg = "Validation failed: Email can't be blank";
    sendResponse($errMsg);
  } elseif ($pwd == "") {
    $errMsg = "Validation failed: Password can't be blank";
    sendResponse($errMsg);

  // pwd一致チェック
  } elseif ($pwd != $pwdCfm) {
    $errMsg = "Validation failed: Password confirmation doesn't match password";
    sendResponse($errMsg);
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
    $errMsg = "そのemailは登録されている";
    sendResponse($errMsg);
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