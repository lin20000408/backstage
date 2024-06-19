<?php include __DIR__ . '/../parts/Database-connection.php'; ?>

<?php
$title = '發送管理';

// 設定pageName(頁面名稱)
$pageName = 'coupon-send-management';

//管理者資訊
include __DIR__ . '/../add-picture.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 字串不能轉換成數字時就會變成0，所以一律跳到第一頁
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

#每一頁有幾筆
$perPage = 10;

$updateSql = "UPDATE coupon_send_management 
INNER JOIN coupon ON coupon_send_management.coupon_id = coupon.id 
INNER JOIN `order` ON coupon.id = `order`.coupon_id 
INNER JOIN `order_details` ON `order`.id = `order_details`.order_id AND coupon_send_management.user_id = `order_details`.user_id
SET coupon_send_management.usage_time = `order`.`order_creation_time`, 
    coupon_send_management.usage_status = 
        CASE 
            WHEN `order`.`order_creation_time` IS NOT NULL THEN '已使用'
            ELSE '未使用'
        END;
";

$pdo->exec($updateSql);

// 初始化搜索条件
$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : ''; // 获取搜索字段，如果未设置则为空
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // 获取搜索关键字，如果未设置则为空

// 构建带搜索条件的总行数查询
if (!empty($searchField) && !empty($searchKeyword)) { // 检查搜索字段和关键字是否都不为空
    if ($searchField === 'coupon_name' || $searchField === 'usage_time' || $searchField === 'send_time') { // 如果搜索字段是 'coupon_name'、'usage_time'、'send_time'
        $t_sql = "SELECT COUNT(1) FROM coupon_send_management WHERE $searchField LIKE '%$searchKeyword%'"; // 构建模糊搜索的 SQL 查询语句
    } elseif ($searchField === 'usage_status') { // 如果搜索字段是 'usage_status'
        $t_sql = "SELECT COUNT(1) FROM coupon_send_management WHERE $searchField = '$searchKeyword'"; // 构建精确搜索的 SQL 查询语句
    } else {
        // 如果搜索字段无效，则不包括搜索条件
        $t_sql = "SELECT COUNT(1) FROM coupon_send_management WHERE 1 = 0"; // 如果搜索字段无效，则设置条件永远不成立
    }
} else {
    // 没有搜索关键字时，保持原有的查询
    $t_sql = "SELECT COUNT(1) FROM coupon_send_management"; // 如果没有搜索关键字，则查询所有行数
}

$t_stmt = $pdo->query($t_sql); // 执行总行数查询
$totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0]; // 获取总行数
$totalPage = ceil($totalRows / $perPage); // 计算总页数

$rows = []; // 初始化结果数组

#有資料時才往下執行
if ($totalRows > 0) { // 如果有数据

    // 超出最後一頁就一律變成最後一頁
    if ($page > $totalPage) { // 如果当前页数超过总页数
        header('Location: ?page=' . $totalPage . '&searchField=' . $searchField . '&keyword=' . urlencode($searchKeyword)); // 換頁時，保持搜索条件
        exit;
    }

    // 构建带搜索条件的查询
    $sql = "
        SELECT 
            coupon.id AS coupon_id,
            coupon.name AS coupon_name,
            coupon.money AS coupon_money,
            user.id AS user_id,
            user.name AS user_name,
            usage_status,
            usage_time,
            send_time 
        FROM 
            coupon   
        JOIN 
            coupon_send_management ON coupon.id = coupon_send_management.coupon_id
        JOIN 
            user ON coupon_send_management.user_id = user.id
    ";

    if (!empty($searchField) && !empty($searchKeyword)) { // 如果搜索字段和关键字都不为空
        if ($searchField === 'coupon_name' || $searchField === 'usage_time' || $searchField === 'send_time') { // 如果搜索字段是 'coupon_name'、'usage_time'、'send_time'
            $sql .= " WHERE $searchField LIKE '%$searchKeyword%'"; // 构建模糊搜索的查询条件
        } elseif ($searchField === 'usage_status') { // 如果搜索字段是 'usage_status'
            $sql .= " WHERE $searchField = '$searchKeyword'"; // 构建精确搜索的查询条件
        } else {
            // 如果搜索字段无效，则不包括搜索条件
            $sql .= " WHERE 1 = 0"; // 如果搜索字段无效，则设置条件永远不成立
        }
    }

    $sql .= " ORDER BY send_time DESC LIMIT " . ($page - 1) * $perPage . ", $perPage"; // 添加排序和分页限制

    $rows = $pdo->query($sql)->fetchAll(); // 执行查询并获取结果集
}
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

    <?php include __DIR__ . '/../parts/html-main.php' ?>

    <?php include __DIR__ . '/coupon-parts/coupon-navbar.php' ?>

    <div class="container">

        <h2 class="text-center">發送管理</h2>

        <?php include __DIR__ . '/coupon-parts/coupon-send-management-table.php' ?>

    </div>

</div>


<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
    // 再次確認是否刪除的視窗
    function deleteOne(send_time) {
        if (confirm(`是否要刪除時間${send_time}項目?`)) {
            location.href = `./coupon-api/coupon-send-management-delete.php?send_time=${send_time}`;
        }
    }
</script>


<?php include __DIR__ . '/../parts/html-footer.php' ?>