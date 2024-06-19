    <?php require __DIR__ . '/../parts/Database-connection.php' ;
    include '../add-picture.php'; ?>

    <?php
    // 設定標題和頁面名稱
    //放 sql 查詢
    // $rows = $pdo->query("SELECT * FROM `midterm`.order")->fetchAll();

    $title = '進貨紀錄';
    $pageName = 'restock';

    // 取得當前頁面，如果沒有指定，預設為第1頁
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    // 如果頁面小於1，將使用戶重定向到第1頁
    if ($page < 1) {
      header('Location: ?page=1');
      exit;
    }


    # 每一頁有幾筆
    $perPage = 10;

    # 計算總筆數
    $t_sql = "SELECT COUNT(1)  FROM `midterm`. restock";
    $t_stmt = $pdo->query($t_sql);
    $totalRows = $t_stmt->fetch(PDO::FETCH_NUM)[0];
    $totalPages = ceil($totalRows / $perPage); # 總頁數
    $rows = []; # 預設值為空陣列
    if ($totalRows > 0) {
      # 當有資料時，進行以下操作
      if ($page > $totalPages) {
        // 如果指定的頁面大於總頁數，將使用者重定向到最後一頁
        header('Location: ?page=' . $totalPages);
        exit;
      }


      # 取得分頁的資料
      $sql = sprintf("SELECT * FROM `midterm`. restock ORDER BY id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
      $rows = $pdo->query($sql)->fetchAll();
    }

    ?>

    <?php include __DIR__ . '/../parts/html-head.php' ?>

    <div class="d-flex w-100 h-100">

      <?php include __DIR__ . '/../parts/html-main.php' ?>

      <?php include __DIR__ . '/restock-parts/restock-navbar.php' ?>

      <div class="container">
        <h2 class="text-center">進貨紀錄</h2>
        <!-- 可參考下面的table 統一格式 -->
        <!-- 進貨紀錄的表格 -->
        <?php include __DIR__ . '/restock-parts/restock-table.php' ?>
      </div>
      </nav>
    </div>

    <?php include __DIR__ . '/../parts/html-js.php' ?>
    <script>
      // 刪除一個項目的 JavaScript 函數
      function deleteOne(id) {
        if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
          // 如果確認刪除，將使用者重定向到刪除 API
          location.href = `./restock-api/restock-delete.php?id=${id}`;
        }
      }
    </script>


    <?php include __DIR__ . '/../parts/html-footer.php' ?>