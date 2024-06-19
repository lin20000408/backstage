<?php require __DIR__ . '/../parts/Database-connection.php' ?>

<?php

$title = '所有評價列表';
$pageName = 'product-review-all-id';

include __DIR__ . '/../add-picture.php';



?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<style>
    nav.navbar .nav-item .nav-link.active {
        border-radius: 10px;
        background-color: #0d6efd;
        color: white;
        font-weight: 800;
    }
</style>
<div class="d-flex w-100 h-100">

    <?php include __DIR__ . '/product-reviews-parts/product-review-main.php' ?>

    <?php include __DIR__ . '/product-reviews-parts/product-review-navbar.php' ?>
    <div class="container">
        <?php $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if ($page < 1) {
            header('Location: ?page=1');
            exit;
        }

        # 每一頁有幾筆
        $perPage = 10;

        # 計算總筆數
        $t_sql = "SELECT COUNT(1) FROM (SELECT order_details.order_id,
                    product_name,
                    product_size,
                    product_color,
                    product_reviews.*
                    FROM `midterm`.product_reviews
                    INNER JOIN `midterm`.order_details 
                    ON order_details.product_reviews_id = product_reviews.id) AS subquery
                    ";
        $t_stmt = $pdo->query($t_sql);
        $totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0];
        $totalPages = ceil($totalRows / $perPage); # 總頁數
        $rows  = []; # 預設值為空陣列
        if ($totalRows > 0) {
            # 有資料時, 才往下進行
            if ($page > $totalPages) {
                header('Location: ?page=' . $totalPages);
                exit;
            }

            # 取得分頁的資料
            $sql = sprintf(" SELECT order_details.order_id,
                        product_name,
                        product_size,
                        product_color,
                        product_reviews.*
                        
                        FROM `midterm`.product_reviews
                        INNER JOIN `midterm`.order_details 
                        ON order_details.product_reviews_id = product_reviews.id ORDER BY id ASC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
            $rows  = $pdo->query($sql)->fetchAll();
        } ?>

        <!-- 可參考下面的table 統一格式 -->
        <div class="container">

            <div class="col-md-6">

            </div>
            <div class="col-md-6 m-3">
                <form method="post" action="product-review-csv.php" class="d-flex">
                    <input type="submit" name="export" value="Export" class="btn btn-outline-warning" />
                </form>
            </div>

        </div>
        </form>

        <h2 class="text-center">全部商品評價</h2>
        <div class="row text-center">

            <div class="col">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-trash"></i></th>
                            <th>評價編號</th>
                            <th>訂單編號</th>
                            <th>商品名稱</th>
                            <th>商品尺寸</th>
                            <th>商品顏色</th>
                            <th>評價照片</th>
                            <th>評價內容</th>
                            <th>星等</th>
                            <th>發布時間</th>
                            <th><i class="fa-solid fa-file-pen"></i></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($rows as $r) : ?>
                            <tr>
                                <td>
                                    <!-- javascript:假連結(在script) -->
                                    <a href="javascript: deleteOne(<?= $r['id'] ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                                <td><?= $r['id'] ?></td>
                                <td><?= $r['order_id'] ?></td>
                                <td><?= $r['product_name'] ?></td>
                                <td><?= $r['product_size'] ?></td>
                                <td><?= $r['product_color'] ?></td>
                                <td class="" style="padding: 0 10px;">
                                    <img style="display:block; height:4rem; " src="<?= $r['photo'] ?>" alt="" style="width: 100%;">
                                </td>
                                <td class="block text-truncate align-middle" style="max-width: 180px; "><?= $r['content'] ?></td>
                                <td><?= $r['stars'] ?></td>
                                <td><?= $r['review_time'] ?></td>
                                <td>
                                    <a href="product-review-edit.php?id=<?= $r['id'] ?>">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>

                    </tbody>
                </table>

            </div>
        </div>



    </div>
    <div class="row">

        <div class="col">
            <div aria-label="Page navigation example" class="d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=1">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) : ?>
                        <?php if ($i >= 1 and $i <= $totalPages) : ?>
                            <li class="page-item <?= $i != $page ?: 'active' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endif ?>
                    <?php endfor ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $totalPages ?>">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</nav>
</div>

<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
    const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
    // console.log(myRows);
    function deleteOne(id) {
        if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
            location.href = `product-review-delete.php?id=${id}`;

        }
    }
    t5
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>