<?php
// $_SERVER 是一个包含了服务器和执行环境信息的 PHP 超全局变量。
// $_SERVER["PHP_SELF"] 包含了当前正在执行的脚本的文件名和路径信息。
// basename() 函数用于返回路径中的文件名部分，去除了路径信息，只留下文件名部分。
// 获取当前文件名
$currentFile = basename($_SERVER["PHP_SELF"]);


// strpos() 是 PHP 中的一个字符串函数，用于在字符串中查找子字符串第一次出现的位置。如果找到了，则返回子字符串在字符串中的起始位置，否则返回 false。
// $currentFile 是我们之前获取的当前执行的 PHP 文件的文件名。
// 如果 $currentFile 中包含字符串 'coupon'，则 $isCouponPage 被赋值为 true，否则被赋值为 false。

// 检查文件名是否包含 'user' 會員管理
$isUserPage = strpos($currentFile, 'user') !== false;
// 检查文件名是否包含 'order' 訂單管理
$isOrderPage = strpos($currentFile, 'order') !== false;
// 检查文件名是否包含 'products' 商品管理
$isProductsPage = strpos($currentFile, 'products') !== false;
// 检查文件名是否包含 'product-review' 商品評價管理
$isProductReviewPage = strpos($currentFile, 'product-review') !== false;
// 检查文件名是否包含 'coupon' 優惠券管理
$isCouponPage = strpos($currentFile, 'coupon') !== false;
// 检查文件名是否包含 'forum' 論壇管理
$isForumPage = strpos($currentFile, 'forum') !== false;
// 检查文件名是否包含 'restock' 進貨管理
$isRestockPage = strpos($currentFile, 'restock') !== false;
?>



<main class="me-5 vh-100" style="width: 400px">
  <div class="d-flex flex-column p-3 text-white bg-dark vh-100  position-fixed"style="width: 400px ">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4 text-center">後臺管理系統</span>
    </a>
    <hr />
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
        <a class="nav-link text-white <?= $isAdminPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../admin/admin.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          後臺管理者
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isUserPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../user/user.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          會員管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isProductsPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../products/products.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          商品管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isOrderPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../order/order-list.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          訂單管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isCouponPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../coupon/coupon.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          優惠券管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isProductReviewPage  ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../product-reviews/product-review-all-id.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          商品評價管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isRestockPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../restock/restock.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          進貨管理
        </a>
      </li>
      <li>
        <a class="nav-link text-white <?= $isForumPage ? 'active' : '' ?>" href=<?= isset($_SESSION['admin']) ? "../forum/forum.php" : 'javascript:void(0);' ?> onclick=<?= isset($_SESSION['admin']) ? '' : 'showAlert();' ?>>
          論壇管理
        </a>
      </li>
     
    </ul>
    <hr />
    <?php if (isset($_SESSION['admin'])) : ?>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
          <img src=<?= $row['picture'] ?> alt="" width="32" height="32" class="rounded-circle me-2" id="loginPicture" />
          <strong>
            <?= isset($_SESSION['admin']) ? $_SESSION['admin']['adminName'] : '' ?>
          </strong>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
          <!-- <li><a class="dropdown-item" href="#" id="changePictureBtn">更改圖片</a></li> -->
          <li><a class="dropdown-item" href="../logout-admin.php">登出</a></li>
        </ul>

      </div>
    <?php endif; ?>
    <!-- 模態對話框或表單 更改圖片-->
    <div class="modal fade" id="changePictureModal" tabindex="-1" aria-labelledby="changePictureModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" id="sendPictureDone">
          <div class="modal-header">
            <h5 class="modal-title" id="changePictureModalLabel">更改圖片</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="uploadForm" name='form2' onsubmit="sendPictureData(event)">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <div class="mb-3">
                <label for="newPicture" class="form-label">選擇新圖片</label>
                <input class="form-control" type="file" id="newPicture" name="newPicture" accept="image/* ">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary" id="uploadButton">上傳</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>