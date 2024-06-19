<?php
require '../../parts/Database-connection.php';
header('Content-Type: application/json');

# 回應給用戶端的欄位 (格式 JSON)
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用
    'error' => '欄位資料不足',
    'code' => 0, # 除錯或追踪程式碼
];

if (!empty($_POST['account']) and !empty($_POST['password'])) {
    #確認帳號對不對
    $sql = "SELECT * FROM `midterm`.admin WHERE account=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['account']
    ]);
    $row = $stmt->fetch();
    if (!empty($row)) {
        # 帳號是對的
        if (password_verify($_POST['password'], $row['password'])) {
            # 密碼是對的
            $_SESSION['admin'] = [
                'id' => $row['id'],
                'email' => $row['email'],
                'adminName' => $row['adminName'],
            ];
            $output['success'] = true;
            $output['error'] = '';
            $output['code'] = 200;
        } else {
            # 密碼是錯的
            $output['code'] = 420;
        }
    } else {
        # 帳號是錯的
        $output['code'] = 400;
    }
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
