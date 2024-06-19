<?php require __DIR__ . '/../parts/Database-connection.php';
$title = '後臺管理者列表';
$pageName = 'admin-';
$navbarName ='list';


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}



#每一頁有幾筆
$perPage =5;


#計算總筆數
$t_sql = "SELECT COUNT(1) FROM  `midterm`.admin";
$t_stmt = $pdo->query($t_sql);
$totalRows = $t_stmt ->fetch(PDO::FETCH_NUM)[0];//總筆數
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
  $sql = sprintf("SELECT * FROM `midterm`.admin ORDER BY id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
  $rows = $pdo->query($sql)->fetchAll();
}

?>

<style>
	.admin{
		color:blue;
		text-align: center;
	}

</style>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<div class="d-flex w-100 h-100">
	<?php include __DIR__ . '/../parts/html-main.php' ?>
	<?php include __DIR__ . '/admin-parts/admin-navbar.php' ?>
	<div class="container">
	  <h2 class="admin">後臺管理者</h2>
		<div class="row">
		<div class="col">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
				<li class="page-item  <?= $page==1 ? 'disabled' : '' ?>">
            <a class="page-link" style="padding-bottom: 9px;"  href="?page=1">
              <i class="fa-solid fa-angles-left "  style="padding-top: 5px;"></i>
            </a>
          </li>
          <li class="page-item <?= $page==1 ? 'disabled' : '' ?>">
            <a class="page-link" style="padding-bottom: 9px;" href="?page=<?= $page - 1 ?>">
              <i class="fa-solid fa-angle-left" style="padding-top: 5px;"></i>
            </a>
          </li>
				<?php for ($i = $page - 2; $i <= $page + 2; $i++) : ?>
            <?php if ($i >= 1 and $i <= $totalPages) : ?>
              <li class="page-item <?= $i != $page ?: 'active' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endif ?>
          <?php endfor ?>
          <li class="page-item <?= $page==$totalPages ? 'disabled' : '' ?>">
            <a class="page-link" style="padding-bottom: 9px;" href="?page=<?= $page + 1 ?>">
              <i class="fa-solid fa-angle-right" style="padding-top: 5px;" ></i>
            </a>
          </li>
          <li class="page-item <?= $page==$totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>" style="padding-bottom: 9px;">
              <i class="fa-solid fa-angles-right" style="padding-top: 5px;"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
	</div>
		<!-- 可參考下面的table 統一格式 -->
		<?php include __DIR__ . '/admin-parts/admin-table-no-login.php' ?>
	</div>
	</nav>
</div>
<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
	const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
  function deleteOne(id) {
    if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
      location.href = `delete-admin.php?id=${id}`;
    }
  }
</script>
<?php include __DIR__ . '/../parts/html-footer.php' ?>