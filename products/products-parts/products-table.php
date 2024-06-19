<div class="container">
  <div class="row">
    <div class="col">
      <table id="productTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><i class="fa-solid fa-trash"></i></th>
            <th class="col id">編號</th>
            <th class="col">商品名稱</th>
            <th class="col">類別</th>
            <th class="col">價格</th>
            <th class="col">風格</th>
            <th class="col">圖片</th>
            <th class="col stars">評價</th>
            <th class="col">上架時間</th>
            <th class="col">更新時間</th>
            <th class="col"><i class="fa-solid fa-file-pen"></i></th>
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
              <td><?= $r['name'] ?></td>
              <td><?= $r['pmt_name'] ?></td>
              <td><?= '$' . $r['price'] ?></td>
              <td><?= $r['style'] ?></td>
              <td class="td-img">
                <img src="<?= $r['pp_url'] ?>" alt="">
              </td>
              <td>
                <a href="../product-reviews/product-review-specific-id.php?id=<?= $r['id'] ?>">
                  <?= $r['avg_stars'] ?>
                </a>
              </td>
              <td><?= $r['launched_time'] ?></td>
              <td><?= $r['updated_time'] ?></td>
              <td>
                <a href="products-edit.php?id=<?= $r['id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>

        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <nav class="d-flex justify-content-center <?= isset($_GET['search']) ? 'd-none' : ''; ?>" aria-label="Page navigation example">
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
      </nav>
    </div>
  </div>
</div>