<?php
date_default_timezone_set('Asia/Taipei');
require '../../parts/Database-connection.php';
header("Content-Type: application/json");

$outPut = [
  'success' => false,
  'postData' => $_POST, // 除錯用
  'error' => '',
  'code' => 0, // 除錯或追蹤程式碼
];
$selectType = $_POST["type"];
// 直接運行 SQL 查詢，獲取所有產品列表
$sql = "SELECT * FROM `midterm`.product_main_types
JOIN `midterm`.products ON product_main_types.id = products.pmt_id WHERE products.pmt_id = $selectType";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($products) {
  // 將查詢結果轉換為 JSON 格式並輸出
  echo json_encode($products);
  exit;
} else {
  // 未找到回傳錯誤訊息
  http_response_code(404);
  echo json_encode(array("error" => "未找到匹配的商品"));
  exit;
}
