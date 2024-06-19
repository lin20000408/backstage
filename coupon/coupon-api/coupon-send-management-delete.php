<?php
require '../../parts/Database-connection.php';

// 检查是否传入了 send_time 参数，如果传入了，就使用 strtotime 函数将其转换为时间戳
$send_time = isset($_GET['send_time']) ? strtotime($_GET['send_time']) : 0;

// 如果 send_time 不为 0，则执行删除操作
if (!empty($send_time)) {
    // 使用 date() 函数将时间戳转换为 MySQL 的日期时间格式
    $formatted_send_time = date('Y-m-d H:i:s', $send_time);
    
    // 构造 SQL 查询来删除记录
    $sql = "DELETE FROM coupon_send_management WHERE send_time='$formatted_send_time'";
    $pdo->query($sql);
}

// 停留在當下頁面
$backTo = 'coupon-send-management.php';

// 如果這個不是空的
if (!empty($_SERVER['HTTP_REFERER'])) {
    $backTo = $_SERVER['HTTP_REFERER'];
}

header("Location: $backTo");
