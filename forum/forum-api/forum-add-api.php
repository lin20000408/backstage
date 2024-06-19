<?php
require '../../parts/Database-connection.php';

# 告訴前端是json格式
header('Content-Type: application/json');

#先決定回應給用戶端的欄位(json格式)
$output = [
    'success' => false, #有沒有新增成功
    'postData' => $_POST, #除錯用
    'error' => '', #回應給用戶端的錯誤訊息
    'code' => 0, #除錯或追蹤程式碼
];

#selectedUser是否有選擇
if (!empty($_POST['selectedUser'])) {
    $isPass = true;
    /* 解析 selectedUser 的值，分割成 user.id 和 user.name 兩個部分*/
    $selectedUser = explode(':', $_POST["selectedUser"]);
    $userId = $selectedUser[0]; // user.id
    $userName = $selectedUser[1]; // user.name

    if ($isPass) {

        $sql = "INSERT INTO `articles`(`user_id`,`user_name`,`type`, `head`,`content`,`picture`,`time`) VALUES (
            ?,?,?,?,?,?,now())";

        /* 拿到pdo的stmt物件， prepare()準備sql語法*/
        $stmt = $pdo->prepare($sql);

        /* (執行)*/
        $stmt->execute([
            $userId,
            $userName,
            $_POST['type'],
            $_POST['articleHead'],
            $_POST['articleContent'],
            $_POST["url"]

        ]);

        /*透過pdo的stmt物件呼叫rowCount來看有沒有新增成功*/
        $output['success'] = boolval($stmt->rowCount());
    }
}
/* 送來的表單資料轉換成json再送回去 */
echo json_encode($output, JSON_UNESCAPED_UNICODE);
// echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
