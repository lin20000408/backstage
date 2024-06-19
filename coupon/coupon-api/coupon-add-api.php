<?php
require '../../parts/Database-connection.php';

// 告訴前端是json格式
header('Content-Type: application/json');

#回應給用戶端的欄位(json格式)
$output = [
    'success' => false, #有沒有新增成功
    'postData' => $_POST, #除錯用
    'error' => '', #回應給用戶端的錯誤訊息
    'code' => 0, #除錯或追蹤程式碼
];


// 如果name這欄不是空的,才往下執行
if (!empty($_POST['name'])) {
    $isPass = true;

    if ($isPass) {

        // TODO:檢查資料格式

        // 檢查starting_time格式
        $starting_time = null;

        // 如果starting_time欄位不是空的
        if (!empty($_POST['starting_time'])) {

            $t = strtotime($_POST['starting_time']); #取得timestamp

            // 如果$t不是false
            if ($t !== false) {

                // timestamp轉換成日期的格式
                $starting_time = date("Y-m-d", $t);
            }
        }

        // 檢查end_time格式
        $end_time = null;

        // 如果end_time欄位不是空的
        if (!empty($_POST['end_time'])) {

            $t = strtotime($_POST['end_time']); #取得timestamp

            // 如果$t不是false
            if ($t !== false) {

                // timestamp轉換成日期的格式
                $end_time = date("Y-m-d", $t);
            }
        }

        #避免sal injection(注入)
        $sql = "INSERT INTO `coupon`(`name`, `money`, `starting_time`,`end_time`) VALUES (
    ?,?,?,?
    )";

        // 拿到pdo的stmt物件 (準備)
        $stmt = $pdo->prepare($sql);

        // (執行)
        $stmt->execute([
            $_POST['name'],
            $_POST['money'],
            $starting_time,
            $end_time
        ]);

        //透過pdo的stmt物件呼叫rowCount來看有沒有新增成功
        $output['success'] = boolval($stmt->rowCount());
    }
}
// 送來的表單資料轉換成json再送回去
echo json_encode($output, JSON_UNESCAPED_UNICODE);
