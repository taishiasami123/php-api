<?php
  // jsonを返して死ぬやつ
  function sendResponse($obj) {
    echo json_encode($obj);
    die();
  }

  // dbに繋ぐ
  require('dbconnect.php');

  // jsonを取得
  $header = getallheaders();
  $bearerToken = $header['Authorization'];
  $token = substr($bearerToken, 7, strlen($bearerToken) - 7);
  $json = file_get_contents("php://input");
  $page = $_GET['page'];
  $limit = $_GET['limit'];
  $keyword = $_GET['query'];

  // dbにtokenを探しに行く
  $slctUsrStmt = $db->prepare('SELECT token FROM users WHERE token = :token');
  $slctUsrStmt->bindValue(':token', $token, PDO::PARAM_STR);
  try {
    $slctUsrStmt->execute();
  } catch (Exception $e) {
    sendResponse($e);
  }
  $slctUsrStmtFtchAllRslt = $slctUsrStmt->fetchAll(PDO::FETCH_ASSOC);
  $slctdToken = $slctUsrStmtFtchAllRslt[0][token];

  // tokenが見つからなかったらエラー吐く
  if (count($slctdToken) == 0) {
    $errMsg = "tokenがおかしい";
    sendResponse($errMsg);
  }

?>