<?php include __DIR__ . '/../parts/Database-connection.php'; ?>

<?php
$title = '過期優惠劵';

// 設定pageName(頁面名稱)
$pageName = 'coupon-expired';

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

// 获取当前日期
$currentDate = date('Y-m-d');

// 构建查询语句，只选择過期的优惠券
$sql = "SELECT * FROM coupon WHERE end_time < '$currentDate'";

// 初始化搜索条件
$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : ''; // 获取搜索字段，如果未设置则为空
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // 获取搜索关键字，如果未设置则为空

// 构建带搜索条件的总行数查询
if (!empty($searchField) && !empty($searchKeyword)) { // 检查搜索字段和关键字是否都不为空
    if ($searchField === 'name' || $searchField === 'starting_time' || $searchField === 'end_time') { // 如果搜索字段是 'name'、'starting_time' 或 'end_time'
        $t_sql = "SELECT COUNT(1) FROM coupon WHERE $searchField LIKE '%$searchKeyword%'"; // 构建模糊搜索的 SQL 查询语句
    } elseif ($searchField === 'money') { // 如果搜索字段是 'money'
        $t_sql = "SELECT COUNT(1) FROM coupon WHERE $searchField = '$searchKeyword'"; // 构建精确搜索的 SQL 查询语句
    } else {
        // 如果搜索字段无效，则不包括搜索条件
        $t_sql = "SELECT COUNT(1) FROM coupon WHERE 1 = 0"; // 如果搜索字段无效，则设置条件永远不成立
    }
} else {
    // 没有搜索关键字时，保持原有的查询
    $t_sql = "SELECT COUNT(1) FROM coupon"; // 如果没有搜索关键字，则查询所有行数
}

// 执行查询获取总行数
$t_stmt = $pdo->query("SELECT COUNT(1) FROM coupon WHERE end_time < '$currentDate'");
$totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0]; #總筆數
$totalPage = ceil($totalRows / $perPage); #總頁數

$rows = []; // 初始化结果数组

#有資料時才往下執行
if ($totalRows > 0) { // 如果有数据

    // 超出最後一頁就一律變成最後一頁
    if ($page > $totalPage) { // 如果当前页数超过总页数
        header('Location: ?page=' . $totalPage . '&searchField=' . $searchField . '&keyword=' . urlencode($searchKeyword)); // 換頁時，保持搜索条件
        exit;
    }

    // 构建带搜索条件的查询
    $sql = "SELECT * FROM coupon WHERE end_time < '$currentDate'";
    if (!empty($searchField) && !empty($searchKeyword)) { // 如果搜索字段和关键字都不为空
        if ($searchField === 'name' || $searchField === 'starting_time' || $searchField === 'end_time') { // 如果搜索字段是 'name'、'starting_time' 或 'end_time'
            $sql .= " WHERE $searchField LIKE '%$searchKeyword%'"; // 构建模糊搜索的查询条件
        } elseif ($searchField === 'money') { // 如果搜索字段是 'money'
            $sql .= " WHERE $searchField = '$searchKeyword'"; // 构建精确搜索的查询条件
        } else {
            // 如果搜索字段无效，则不包括搜索条件
            $sql .= " WHERE 1 = 0"; // 如果搜索字段无效，则设置条件永远不成立
        }
    }

    $sql .= " ORDER BY id DESC LIMIT " . ($page - 1) * $perPage . ", $perPage"; // 添加排序和分页限制

    $rows = $pdo->query($sql)->fetchAll(); // 执行查询并获取结果集
}

?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

    <?php include __DIR__ . '/../parts/html-main.php' ?>

    <?php include __DIR__ . '/coupon-parts/coupon-navbar.php' ?>

    <div class="container">

        <h2 class="text-center">過期優惠劵</h2>

        <?php include __DIR__ . '/coupon-parts/coupon-valid-expired-table.php' ?>

    </div>

</div>


<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
    // 再次確認是否刪除的視窗
    function deleteOne(id) {
        if (confirm(`是否要刪除編號${id}項目?`)) {
            location.href = `./coupon-api/coupon-delete.php?id=${id}`;
        }
    }
</script>


<?php include __DIR__ . '/../parts/html-footer.php' ?>