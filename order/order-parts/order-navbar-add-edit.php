<?php
if (!isset($pageName)) {
  $pageName = '';
}
?>
<style>
  .nav-item .nav-link.active {
    background-color: #0d6efd;
    color: white;
    font-weight: 800;
    border-radius: 10px;
  }
</style>
<nav class="w-75" style="margin-left: 450px;">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
      <div class="container-fluid">
        <a class="navbar-brand" href=" order-list.php">訂單管理</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="d-flex justify-content-between w-100">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'orderList' ? 'active' : '' ?>" aria-current="page" href="order-list.php">列表</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'orderAdd' ? 'active' : '' ?>" href="order-add.php">新增</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </div>