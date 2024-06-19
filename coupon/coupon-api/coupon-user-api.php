<?php
// 包含数据库连接文件
require '../../parts/Database-connection.php';

// 告诉前端返回的数据格式为 JSON
header('Content-Type: application/json');

// 定义要返回给客户端的输出数组，初始值设置为默认值
$output = [
    'success' => false, // 用于指示是否成功
    'postData' => $_POST, // 用于调试，返回接收到的 POST 数据
    'error' => '', // 用于返回给客户端的错误消息
    'code' => 0, // 用于调试或跟踪代码的代码
    'data' => null, // 用于存储从数据库获取的数据，初始值设置为 null
];

// 获取 POST 数据中的用户ID和优惠券名称
$userID = $_POST["user_id"];
$couponName = $_POST["coupon_name"];

// 构建 SQL 查询语句 - 从 coupon 表中获取数据
$sql_coupon = "SELECT 
            name AS coupon_name,
            money AS coupon_money,
            starting_time AS starting_time,
            end_time AS end_time
        FROM 
            coupon
        WHERE 
            name = :coupon_name";

// 准备并执行 SQL 查询 - 从 coupon 表中获取数据
$stmt_coupon = $pdo->prepare($sql_coupon);
$stmt_coupon->execute(['coupon_name' => $couponName]);
$couponData = $stmt_coupon->fetch(PDO::FETCH_ASSOC);

// 构建 SQL 查询语句 - 从 user 表中获取数据
$sql_user = "SELECT 
            id AS user_id,
            name AS user_name
        FROM 
            user
        WHERE 
            id = :user_id";

// 准备并执行 SQL 查询 - 从 user 表中获取数据
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute(['user_id' => $userID]);
$userData = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 如果查询成功，则返回数据，否则返回错误信息
if ($couponData && $userData) {
    // 如果查询到了优惠券和用户数据，则设置 success 为 true，并将查询结果存储到 data 字段中
    $output['success'] = true;
    $output['data'] = [
        'coupon_name' => $couponData['coupon_name'],
        'coupon_money' => $couponData['coupon_money'],
        'starting_time' => $couponData['starting_time'],
        'end_time' => $couponData['end_time'],
        'user_id' => $userData['user_id'],
        'user_name' => $userData['user_name'],
    ];
} else {
    // 如果未查询到优惠券或用户数据，则设置 error 字段为相应的错误消息
    $output['error'] = 'Coupon or User not found';
}

// 输出 JSON 格式的输出数组
echo json_encode($output);

// 终止脚本的执行
exit;
?>