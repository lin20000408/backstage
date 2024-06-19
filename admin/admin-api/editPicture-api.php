<?php
require '../../parts/Database-connection.php';
// 告訴前端是json格式
header('Content-Type: application/json');

# 回應給用戶端的欄位 (格式 JSON)
$output = [
    'success' => false, #有沒有新增成功
    'postData' => $_POST, # 除錯用
    'error' => '', #回應給用戶端的錯誤訊息
    'code' => 0, # 除錯或追踪程式碼
];
$sql = "UPDATE `midterm`.admin  SET
`picture` = ? ,
`time` = now()
WHERE `id`= ? ";

/* 拿到pdo的stmt物件， prepare()準備sql語法*/
$stmt = $pdo->prepare($sql);

/* (執行)*/
$stmt->execute([
    $_POST['url'],
    $_POST['id']
]);

//透過pdo的stmt物件呼叫rowCount來看有沒有新增成功
$output['success'] = boolval($stmt->rowCount());




echo json_encode($output, JSON_UNESCAPED_UNICODE);