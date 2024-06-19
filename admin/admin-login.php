<?php require __DIR__ . '/../parts/Database-connection.php';
$title = '後臺管理者列表';
$pageName = 'admin-';
$navbarName = 'list';

$isAdminPage = 'admin';

$page = isset ($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

$id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;

//管理者資訊
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();

// var_dump($row);



#每一頁有幾筆
$perPage =10;


#計算總筆數
$t_sql = "SELECT COUNT(1) FROM  `midterm`.admin";
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
  $sql = sprintf("SELECT * FROM `midterm`.admin ORDER BY id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
  $rows = $pdo->query($sql)->fetchAll();
}

?>

<style>
  .admin {
    color: blue;
    text-align: center;
  }

  #right-content {
    margin-left: 452px;
    position: absolute;
  }

  #left-corner {
    position: absolute;
    z-index: 3;
  }

  .pagination {
    display:flex;
    justify-content: center;
  }

  .modal-backdrop.show {
  z-index: 1 !important; /* 使用 !important 来确保覆盖可能存在的其他样式 */
}


</style>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<div class="d-flex w-100 h-100">
  <?php include __DIR__ . '/admin-main.php' ?>
  <?php include __DIR__ . '/admin-parts/admin-navbar.php' ?>
  <div class="container">
    <h2 class="admin">後臺管理者</h2>


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
    <div class="row">
      <div class="col">
        <nav aria-label="Page navigation example" id='nav'>
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
// 获取模态框元素
var modal = document.getElementById('changePictureModal');

// 当模态框显示时，将其移到最前面
modal.addEventListener('show.bs.modal', function () {
  var backdrop = document.querySelector('.modal-backdrop');
  var backdropZIndex = window.getComputedStyle(backdrop).getPropertyValue('z-index');
  var modalZIndex = parseInt(backdropZIndex) +100;
  modal.style.zIndex = modalZIndex;
});

//讓pagination在滑動的時候為"-1"
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

  const { newPicture: selectNewPicture } = document.form2;
  async function sendPictureData(e) {
    e.preventDefault();
    let isPass = true;
    // var myModal = new bootstrap.Modal(document.getElementById('changePictureModal'));
    // myModal.hide();

    //圖片是否有選擇

    if (selectNewPicture.value) {
      if (selectNewPicture.files.length <= 1) {
        //呼叫Imgur api
        Imgur = await uploadImage(selectNewPicture);
        console.log(Imgur);

        if (!Imgur) {
          isPass = false;
          selectNewPicture.style.border = "2px solid red";
          selectNewPicture.nextElementSibling.innerHTML = '檔案格式錯誤';
        }
      } else {
        selectNewPicture.style.border = "2px solid red";
        selectNewPicture.nextElementSibling.innerHTML = '最多一張圖片';
      }
    } else {
      Imgur = "<?= $row['picture'] ?>";
    }

    if (isPass) {
      const fd = new FormData(document.form2);
      fd.append("url", Imgur);
      //傳送
      fetch('./admin-api./editPicture-api.php', {
        method: 'POST',
        body: fd
      })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            const modal = new bootstrap.Modal(document.getElementById('changePictureModal'));
            modal.hide()
            successModal.show();
          } else {

            if (result.error) {
              failureInfo.innerHTML = result.error;
            } else {
              failureInfo.innerHTML = '圖片新增失敗';
            }

            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
          failureInfo.innerHTML = '圖片新增發生錯誤' + ex;
          failureModal.show();
        })

    }

    // 透過js控制資料新增成功/失敗視窗
    const successModal = new bootstrap.Modal('#successModal');
    const failureModal = new bootstrap.Modal('#failureModal');

    // 抓錯誤訊息
    const failureInfo = document.querySelector('#failureModal .alert-danger');


  }

  const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
  function deleteOne(id) {
    if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
      location.href = `delete-admin.php?id=${id}`;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const changePictureBtn = document.getElementById('changePictureBtn');
    const changePictureModal = new bootstrap.Modal(document.getElementById('changePictureModal'));

    // 點擊圖片時顯示模態對話框
    const loginPicture = document.getElementById('loginPicture');
    loginPicture.addEventListener('click', function () {
      changePictureModal.show();
    });

    // 點擊更改圖片按鈕時顯示模態對話框
    changePictureBtn.addEventListener('click', function () {
      changePictureModal.show();
    });
  });
</script>
<?php include __DIR__ . '/../parts/html-footer.php' ?>