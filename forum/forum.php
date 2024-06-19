<?php require '.././parts/Database-connection.php' ?>

<?php
// 設定pageName(頁面名稱)
$pageName = 'forum';

// 管理者照片
include '../add-picture.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
// 字串不能轉換成數字時就會變成0，所以一律跳到第一頁
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}




// 初始化搜索条件
$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : ''; // 获取搜索字段，如果未设置则为空
$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // 获取搜索关键字，如果未设置则为空
#每一頁有幾筆
$perPage = 10;

#計算總筆數 articles   
//有type and keyword
if (!empty($searchField) && !empty($searchKeyword)) {
  $forumCount = "SELECT COUNT(1) FROM articles WHERE type= '$searchField' AND (`articles`.`head` LIKE '%$searchKeyword%' OR `articles`.`content` LIKE '%$searchKeyword%') ";
  //有type and 沒keyword
} else if (!empty($searchField) && empty($searchKeyword)) {
  $forumCount = "SELECT COUNT(1) FROM articles WHERE type= '$searchField' ";
  //沒type and 有keyword
} else if (empty($searchField) && !empty($searchKeyword)) {
  $forumCount = "SELECT COUNT(1) FROM articles WHERE (`articles`.`head` LIKE '%$searchKeyword%' OR `articles`.`content` LIKE '%$searchKeyword%')";
  //都沒有
} else {
  $forumCount = "SELECT COUNT(1) FROM articles";
}



#用query查詢sql語
$totalCount = $pdo->query($forumCount);
$totalRows = $totalCount->fetch(PDO::FETCH_NUM)[0]; #總筆數
$totalPage = ceil($totalRows / $perPage); #總頁數，ceil() 往上取整數
$rows = []; #預設空陣列
#有資料時才往下執行
if ($totalRows > 0) {
  // 超出最後一頁就一律變成最後一頁
  if ($page > $totalPage) {
    header('Location: ?page=' . $totalPage);
    exit;
  }
  #取得分頁資料
  // ORDER BY id DESC 是降冪排序


  //有type and keyword
  if (!empty($searchField) && !empty($searchKeyword)) {
    $sql = sprintf("SELECT `articles`.`id`,`articles`.`user_id`,`articles`.`user_name`,`articles`.`type`,`articles`.`head`,`articles`.`content`,`articles`.`picture`,COUNT(`article_likes`.`article_id`) AS `likes`,`articles`.`time`
  FROM `articles`
  LEFT JOIN `article_likes` ON `article_likes`.`article_id` = `articles`.`id`
  WHERE `articles`.`type` = '%s' AND (`articles`.`head` LIKE '%%%s%%' OR `articles`.`content` LIKE '%%%s%%')
  GROUP BY `articles`.`id`
  LIMIT %d, %d", $searchField, $searchKeyword, $searchKeyword, ($page - 1) * $perPage, $perPage);

    //有type and 沒keyword
  } else if (!empty($searchField) && empty($searchKeyword)) {
    $sql = sprintf("SELECT `articles`.`id`,`articles`.`user_id`,`articles`.`user_name`,`articles`.`type`,`articles`.`head`,`articles`.`content`,`articles`.`picture`,COUNT(`article_likes`.`article_id`) AS `likes`,`articles`.`time`
  FROM `articles`
  LEFT JOIN `article_likes` ON `article_likes`.`article_id` = `articles`.`id`
  WHERE `articles`.`type` = '$searchField'
  GROUP BY `articles`.`id`
  LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    //沒type and 有keyword
  } else if (empty($searchField) && !empty($searchKeyword)) {
    $sql = sprintf("SELECT `articles`.`id`,`articles`.`user_id`,`articles`.`user_name`,`articles`.`type`,`articles`.`head`,`articles`.`content`,`articles`.`picture`,COUNT(`article_likes`.`article_id`) AS `likes`,`articles`.`time`
  FROM `articles`
  LEFT JOIN `article_likes` ON `article_likes`.`article_id` = `articles`.`id`
  WHERE (`articles`.`head` LIKE '%%%s%%' OR `articles`.`content` LIKE '%%%s%%')
  GROUP BY `articles`.`id`
  LIMIT %d, %d", $searchKeyword, $searchKeyword, ($page - 1) * $perPage, $perPage);
    //都沒有
  } else {
    $sql = sprintf("SELECT `articles`.`id`,`articles`.`user_id`,`articles`.`user_name`,`articles`.`type`,`articles`.`head`,`articles`.`content`,`articles`.`picture`,COUNT(`article_likes`.`article_id`) AS `likes`,`articles`.`time`
    FROM `articles`
    LEFT JOIN `article_likes` ON `article_likes`.`article_id` = `articles`.`id`
    GROUP BY `articles`.`id`
    LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
  }

  $rows = $pdo->query($sql)->fetchAll();
}
//
$typeSelect = " SELECT `type` FROM articles GROUP BY `type` ";
$chooseType = $pdo->query($typeSelect)->fetchAll(PDO::FETCH_NUM);
?>

<?php include '.././parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include '.././parts/html-main.php' ?>

  <?php include './forum-parts/forum-navbar.php' ?>

  <div class="container">
    <!-- <h2>a.html</h2> -->
    <!-- 可參考下面的table 統一格式 -->
    <?php include './forum-parts/forum-table.php' ?>
  </div>
  </nav>
</div>

<?php include '.././parts/html-js.php' ?>
<!-- dataTable -->
<script>
  $(document).ready(function() {
    let dataTable = new DataTable('#dataTable', {
      language: {
        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/zh-HANT.json', //中文化
      },
      searching: false,
      ordering: false,
      paging: false,
      info: false,
      lengthChange: false,
    });
  });
</script>
<script>
  // 再次確認是否刪除的視窗
  function deleteOne(id) {
    if (confirm(`是否要刪除編號${id}項目?`)) {
      location.href = `./forum-api./forum-delete-api.php?id=${id}`;
    }
  }
</script>



<?php include '.././parts/html-footer.php' ?>