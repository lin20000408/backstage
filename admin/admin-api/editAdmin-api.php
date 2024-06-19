<?php
require __DIR__ . '/../required-admin.php';
require '../../parts/Database-connection.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'error' => '',
    'code' => 0,
];

if (!empty($_POST['id']) && !empty($_POST['adminName'])) {
    $isPass = true;
    // TODO: 檢查資料的格式
    if ($isPass) {
        $params = [
            $_POST['adminName'],
            $_POST['account'],
            $_POST['password'], // Moved password before email
            $_POST['email'],
            $_POST['id'],
        ];

        $sql = "UPDATE `midterm`.admin SET
        `adminName`=?,
        `account`=?,
        `password`=?, 
        `email`=?
        WHERE `id`=?";
        
        // 檢查密碼是否提供並且長度大於 0
        if (!empty($_POST['password']) && strlen($_POST['password']) > 0) {
            // 使用 password_hash() 函式加密密碼
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $params[2] = $hashedPassword; // Update password in params array
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $output['success'] = boolval($stmt->rowCount());
    }
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
