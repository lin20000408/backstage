<?php
require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');

# 回應給用戶端的欄位 (格式 JSON)
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用
    'error' => '',
    'code' => 0, # 除錯或追踪程式碼
];


if (!empty($_POST['id'])) {
    $isPass = true;


    // TODO: 檢查資料的格式

    if (mb_strlen($_POST['stars']) < 1 ) {
        $isPass = false;
        $output['error'] = 'star請填一到五顆';
    }





    if ($isPass) {
        # 避免 SQL injection
        $sql = "INSERT INTO `product_reviews`(
          `photo`, `content`, 
        `stars`, `review_time`
        ) VALUES (
        ?, ?,
        ?, NOW()
        )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['url'],
            $_POST['content'],
            $_POST['stars'],

        ]);


        $lastInsertId = $pdo->lastInsertId();
        $orderId = $_POST['id']; // 通过 POST 传递订单 ID
        $Ordersql = "UPDATE `order_details` SET 
                 `product_reviews_id`=?
                 WHERE `id`=?";
        $orderStmt = $pdo->prepare($Ordersql);
        $orderStmt->execute([$lastInsertId, $orderId]);
        // Optional: Insert photo URL into product_reviews table


        // 更新订单详情表中的产品评论ID

        $output['success'] = boolval($stmt->rowCount());
    }
}







echo json_encode($output, JSON_UNESCAPED_UNICODE);
