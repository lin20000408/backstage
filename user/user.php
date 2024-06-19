<?php require __DIR__ . '/../parts/Database-connection.php';
$title = '會員管理者列表';
$pageName = 'user-';
$navbarName = 'list';
$id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();

$page = isset ($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}



#每一頁有幾筆
$perPage = 10;


#計算總筆數
$t_sql = "SELECT COUNT(1) FROM  `midterm`.user";
$t_stmt = $pdo->query($t_sql);
$totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0];//總筆數
$totalPages = ceil($totalRows / $perPage); # 總頁數
$rows = []; # 預設值為空陣列

if ($totalRows > 0) {
  # 有資料時, 才往下進行
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages);
    exit;
  }
  //
  # 取得分頁的資料
  $sql = sprintf("SELECT * FROM `midterm`.user ORDER BY id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
  $rows = $pdo->query($sql)->fetchAll();
}

?>

<style>
  .user {
    color: blue;
    text-align: center;
  }

  nav.navbar .nav-item .nav-link.active {
    border-radius: 10px;
    background-color: #0d6efd;
    color: white;
    font-weight: 800;
  }

  #right-content {
    margin-left: 452px;
    position: absolute;
  }

  #left-corner {
    position: absolute;
    z-index: 3;
  }

  /* #right-content #left-corner .pagination 讓分頁再被其他元素遮住時，能被蓋住*/

  .pagination {
    display:flex;
    justify-content:center;
  }
</style>
<!-- 讓字體不要換行 -->
<style>
  .table td,
  .table th {
    white-space: nowrap;
    /* 设置表格单元格内容不换行 */
  }
</style>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<div class="d-flex w-100 h-100">
  <?php include __DIR__ . '/user-html-main01.php' ?>
  <nav class="w-75" id="right-content">
    <div class="container">
      <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
        <div class="container-fluid">
          <!-- <a class="navbar-brand" href="index-.php">Navbar</a> -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="user.php">會員管理</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="user.php">基本資料</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'coupon' ? 'active' : '' ?>" href="user-coup.php">優惠券</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'chart' ? 'active' : '' ?>" href="user-chart.php">統計圖表</a>
              </li>
            </ul>
            <form class="d-flex ms-auto" role="search" method="GET" action="./user-api/user-search-api.php"
              name="form1" style="margin-top:10px">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="account" />
              <button class="btn btn-outline-success" type="submit">
                Search
              </button>
            </form>
          </div>
        </div>
      </nav>
    </div>

    <div class="container">
      <h2 class="user">會員管理者</h2>
      <!-- 可參考下面的table 統一格式 -->
      <?php include __DIR__ . '/user-parts/user-table.php' ?>
      <div class="row">
        <div class="col">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item  <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" style="padding-bottom: 9px;" href="?page=1">
                  <i class="fa-solid fa-angles-left " style="padding-top: 5px;"></i>
                </a>
              </li>
              <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" style="padding-bottom: 9px;" href="?page=<?= $page - 1 ?>">
                  <i class="fa-solid fa-angle-left" style="padding-top: 5px;"></i>
                </a>
              </li>
              <?php for ($i = $page - 2; $i <= $page + 2; $i++): ?>
                <?php if ($i >= 1 and $i <= $totalPages): ?>
                  <li class="page-item <?= $i != $page ?: 'active' ?>">
                    <a class="page-link" href="?page=<?= $i ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php endif ?>
              <?php endfor ?>
              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" style="padding-bottom: 9px;" href="?page=<?= $page + 1 ?>">
                  <i class="fa-solid fa-angle-right" style="padding-top: 5px;"></i>
                </a>
              </li>
              <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $totalPages ?>" style="padding-bottom: 9px;">
                  <i class="fa-solid fa-angles-right" style="padding-top: 5px;"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div> 
      </div>
    </div>
  </nav>
</div>
<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
  //  滾動事件發生時，將pagination的z-index 設置為"-1"
  // 监听导航栏的滚动事件
  document.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    const pagination = document.querySelector('.pagination');

    // 检查导航栏是否固定（或者根据您的情况检查导航栏的状态）
    // 这里的条件可以根据您的实际情况进行调整
    if (navbar.classList.contains('fixed-top')) {
      // 如果导航栏被固定，将 .pagination 的 z-index 设置为 -1
      pagination.style.zIndex = '-1';
    } else {
      // 如果导航栏未固定，将 .pagination 的 z-index 设置为较高的值（例如 1）
      pagination.style.zIndex = '1';
    }
  });

  const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;

  function sendData(e) {
    // 欄位的外觀要回復原來的狀態
    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出
    let isPass = true; // 有沒有通過檢查, 預設值為 true
    // TODO: 檢查資料的格式
    // 姓名是必填, 長度要 2 以上
    // email 若有填才檢查格式, 沒填不檢查格式

    // 如果欄位都有通過檢查, 才要發 AJAX
    if (isPass) {
      const fd = new FormData(document.form1); // 看成沒有外觀的表單
      fetch('./user-api/user-search.php', {
        method: 'GET',
        body: fd
      })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            location.href = 'user-coup.php';
          } else {
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
        })
    }
  }


</script>
<?php include __DIR__ . '/../parts/html-footer.php' ?>