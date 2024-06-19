<?php
require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');
$ids = []; // 创建一个数组来存储搜索结果的ID

// 检查URL中是否设置了search参数
if (!empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql = "SELECT id FROM product_reviews WHERE content LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search]);
    $rows = $stmt->fetchAll();

    // 遍历结果集，将所有ID存储到数组中
    foreach ($rows as $row) {
        $ids[] = $row['id'];
    }
}

// 编码所有ID数组作为JSON响应
echo json_encode(['success' => !empty($ids), 'ids' => $ids]);

// echo json_encode(['success' => !empty($rows), 'ids' => $rows]);
// if (!empty($_GET['search'])) {
//     $search = '%' . $_GET['search'] . '%';
//     $sql = "SELECT id FROM product_reviews WHERE content LIKE ?";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([$search]);
//     $rows = $stmt->fetchAll();
//   }

//     $search = '%' . $_GET['search'] . '%';
//     $sql = "SELECT id FROM product_reviews WHERE content LIKE ?";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([$search]);
//     $rows = $stmt->fetchAll();

// // var_dump($rows);id=1,41
// // echo json_encode('id',JSON_UNESCAPED_UNICODE);
// //!empty($rows) 這個部分是在檢查 $rows 變數是否為空。如果 $rows 不為空值(例如空陣列)，則 !empty($rows) 的結果為 true。
// //$rows[0]['id'] ?? null 這部分是嘗試獲取 $rows 陣列中第一個元素的 'id' 鍵對應的值。如果 $rows 為空陣列或者第一個元素沒有 'id' 這個鍵,則返回 null。
// // Output the results as JSON
// echo json_encode(['success' => !empty($rows), 'id'
//  => !empty($rows) ? $rows[0]['id'] : null]);
