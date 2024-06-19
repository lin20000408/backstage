<?php require __DIR__ . '/../parts/Database-connection.php' ?>
<?php include __DIR__ . '/../add-picture.php' ?>
<style>
  .td-img {
    padding: 0 !important;
  }

  td img {
    height: 70px;
  }

  li a i {
    padding: 4px 0px;
  }

  th.id,
  th.stars {
    width: 50px;
  }
</style>
<?php

$title = '商品管理';
$pageName = 'products';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

# 每一頁有幾筆
$perPage = 10;


# 計算總筆數
$t_sql = "SELECT COUNT(1) FROM products";
$t_stmt = $pdo->query($t_sql);
$totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage); # 總頁數

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search keyword to prevent XSS attacks
$keyword = htmlspecialchars($keyword);

// Prepare SQL statement with LIKE operator
$sql = "SELECT p.*,
        COALESCE(pmt.name, '') AS pmt_name,
        COALESCE(pp.url, '') AS pp_url,
        COALESCE(pr.avg_stars, 0) AS avg_stars
    FROM products p
    LEFT JOIN product_main_types pmt ON p.pmt_id = pmt.id
    LEFT JOIN product_photos pp ON p.id = pp.product_id
    LEFT JOIN (
        SELECT product_id, ROUND(AVG(stars), 1) AS avg_stars
        FROM product_reviews
        GROUP BY product_id
    ) pr ON p.id = pr.product_id
    WHERE (p.name LIKE '%$keyword%')
    ORDER BY id ASC
    LIMIT " . (($page - 1) * $perPage) . ", $perPage";

// Execute the query and fetch results
$rows = $pdo->query($sql)->fetchAll();
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/products-parts/products-navbar.php' ?>

  <div class="container">
    <div class="row">
      <div class="col">
        <div aria-label="Page navigation example" class="d-flex justify-content-end">
          <form class="d-flex ms-auto" role="search" method="GET" action="products.php">
            <input class="form-control-sm mx-2" type="search" placeholder="搜尋商品" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES) : ''; ?>" />
            <button class="btn btn-outline-primary" type="submit">
              搜尋
            </button>
          </form>
        </div>
      </div>
    </div>
    <!-- 可參考下面的table 統一格式 -->
    <?php include __DIR__ . '/products-parts/products-table.php' ?>
  </div>
  </nav>
</div>

<?php include __DIR__ . '/../parts/html-js.php' ?>

<script>
  const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
  // console.log(myRows);

  function deleteOne(id) {
    if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
      location.href = `products-delete.php?id=${id}`;
    }
  }
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>