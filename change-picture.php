    <!-- 圖片修改成功視窗 -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">圖片修改結果</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-success" role="alert">
              圖片修改成功
            </div>
          </div>

          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button> -->
            <a href="admin-login.php" class="btn btn-primary">回到總表頁</a>
          </div>

        </div>
      </div>
    </div>

    <!-- 圖片修改失敗視窗 -->
    <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">圖片修改結果</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" role="alert">
              圖片修改失敗
            </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">修改</button> -->
            <a href="admin-login.php" class="btn btn-primary">回到總表頁</a>
          </div>
        </div>
      </div>
    </div>
    <!-- 可參考下面的table 統一格式 -->
    <?php include __DIR__ . '/admin-parts/admin-table.php' ?>
  </div>
  </nav>
</div>

