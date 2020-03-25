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
  $params = json_decode($json, true)['sign_in_user_params'];
  $email = $params['email'];
  $pwd = $params['password'];
  $pwd = hash('sha256', $pwd);  // ハッシュ化
  $pwdCfm = $params['password_confirmation'];
  $pwdCfm = hash('sha256', $pwdCfm); //ハッシュ化

  // email欄が空だったらエラー吐く
  if ($email == "") {
    $errMsg = "そのemailもしくはpasswordが違います";
    sendResponse($errMsg);
  }

  // emailに入力された値と一致する行をdbから拾ってくる
  $slctUsrStmt = $db->prepare('SELECT email, password FROM users WHERE email = :email');
  $slctUsrStmt->bindValue(':email', $email, PDO::PARAM_STR);
  try {
    $slctUsrStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $slctUsrFtchAllRslt = $slctUsrStmt->fetchAll(PDO::FETCH_ASSOC);

?>