<?php
require '../../parts/Database-connection.php';

// 设置时区为亚洲/台北
date_default_timezone_set('Asia/Taipei');

// 设置数据库连接的时区
$pdo->exec("SET time_zone = '+08:00';");

// 告訴前端是json格式
header('Content-Type: application/json');

# 回應給用戶端的欄位 (格式 JSON)
$output = [
    'success' => false, #有沒有新增成功
    'postData' => $_POST, # 除錯用
    'error' => '', #回應給用戶端的錯誤訊息
    'code' => 0, # 除錯或追踪程式碼
];

// 检查是否提交了优惠券名称和用户ID
if (!empty($_POST['coupon_name']) && !empty($_POST['user_id'])) {
    $coupon_name = $_POST['coupon_name'];
    $user_id = $_POST['user_id'];

    // 在插入之前先查询是否已经发送过相同的优惠券给该用户
    $query = "SELECT COUNT(*) AS count FROM coupon_send_management WHERE coupon_name = ? AND user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$coupon_name, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // 如果已经发送过相同的优惠券给该用户，返回错误消息
        $output['error'] = '該會員已经收到過相同的優惠券';
    } else {
        // 插入 coupon_send_management 表
        $sql = "INSERT INTO `coupon_send_management` (`coupon_id`, `user_id`, `usage_status`, `usage_time`, `send_time`, `coupon_name`)
        VALUES (
            (SELECT `id` FROM `coupon` WHERE `name` = ?),
            ?,
            '未使用',
            NULL,
            NOW(),
            ?
            )"; 

        // 拿到pdo的stmt物件 (準備)
        $stmt = $pdo->prepare($sql);

        // (執行)
        $stmt->execute([$coupon_name, $user_id, $coupon_name]); // 将 $coupon_name 作为参数传递两次，一次用于获取 coupon_id，一次用于插入 coupon_name

        //透過pdo的stmt物件呼叫rowCount來看有沒有新增成功
        $output['success'] = boolval($stmt->rowCount());
    }
}

// 送來的表單資料轉換成json再送回去
echo json_encode($output, JSON_UNESCAPED_UNICODE);
