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

?>