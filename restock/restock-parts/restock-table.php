<div class="row">
  <div class="col">
    <!-- 產品列表的表格 -->
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <!-- 刪除欄位 -->
          <th><i class="fa-solid fa-trash"></i></th>
          <!-- 編號欄位 -->
          <th>編號</th>
          <!-- 產品名稱欄位 -->
          <th>產品名稱</th>
          <!-- 數量欄位 -->
          <th>數量</th>
          <!-- 進貨時間欄位 -->
          <th>進貨時間</th>
          <!-- 編輯欄位 -->
          <th><i class="fa-solid fa-file-pen"></i></th>
        </tr>
      </thead>
      <tbody>
        <!-- 迴圈列出每一筆產品資料 -->
        <?php foreach ($rows as $r) : ?>
          <tr>
            <td>
              <!-- 刪除按鈕 -->
              <a href="javascript: deleteOne(<?= $r['id'] ?>)">
                <i class="fa-solid fa-trash"></i>
              </a>
            </td>
            <!-- 顯示產品編號 -->
            <td><?= $r['id'] ?></td>
            <!-- 顯示產品名稱 -->
            <td><?= $r['product_name'] ?></td>
            <!-- 顯示產品數量 -->
            <td><?= $r['amount'] ?></td>
            <!-- 顯示進貨時間 -->
            <td><?= $r['restock_time'] ?></td>
            <td>
              <!-- 編輯按鈕 -->
              <a href="restock-edit.php?id=<?= $r['id'] ?>">
                <i class="fa-solid fa-file-pen"></i>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>

    
    <!-- 分頁按鈕置中 -->
    <div class="d-flex justify-content-center">
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