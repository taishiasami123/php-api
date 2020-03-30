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

?>