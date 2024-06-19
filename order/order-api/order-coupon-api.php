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
$userID = $_POST["type"];
// 直接運行 SQL 查詢，獲取所有產品列表
$sql = "SELECT coupon_send_management.user_id,coupon_id,coupon_name,money ,user.name,phone,address
FROM `midterm`.coupon 
join coupon_send_management 
on coupon.id = coupon_send_management.coupon_id 
join `midterm`.user 
on coupon_send_management.user_id = user.id 
where coupon_send_management.user_id = $userID";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$userID = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($userID) {
  // 將查詢結果轉換為 JSON 格式並輸出
  echo json_encode($userID);
  exit;
} else {
  // 未找到回傳錯誤訊息
  http_response_code(404);
  echo json_encode(array("error" => "未找到匹配的商品"));
  exit;
}
