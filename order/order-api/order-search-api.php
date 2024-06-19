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

$category = $_POST["category"]; // 獲取類別的值
$searchInput = $_POST["searchInput"];

// 獲取產品列表
$sql = "SELECT * FROM `midterm`.`order`
        WHERE `order`.`$category` LIKE :searchInput";

$stmt = $pdo->prepare($sql);
$stmt->execute(['searchInput' => "%$searchInput%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($products) {
    // 查詢結果 轉成json
    echo json_encode($products);
    exit;
} else {
    // 如果未找到，返回错误消息
    http_response_code(404);
    exit;
}
