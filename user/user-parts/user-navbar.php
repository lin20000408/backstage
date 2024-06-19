<?php
if (!isset ($pageName)) {
  $pageName = '';
}
?>
<style>
  nav.navbar .nav-item .nav-link.active {
    border-radius: 10px;
    background-color: #0d6efd;
    color: white;
    font-weight: 800;
  }
</style>

<nav class="w-75">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
      <div class="container-fluid">
        <!-- <a class="navbar-brand" href="index-.php">Navbar</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="user.php">會員管理</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="user.php">基本資料</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $navbarName == 'coupon' ? 'active' : '' ?>"  href="user-coupon.php">優惠券</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $chartName == 'chart' ? 'active' : '' ?>"  href="user-chart.php">圖表</a>
            </li>
          </ul>
          <form class="d-flex ms-auto" role="search" method="GET" action="./user-api/user-search-api.php">
            <select class="form-select me-2" aria-label="Status" name="status">
              <option value="both">優惠券使用+未使用</option>
              <option value="已使用">優惠券已使用</option>
              <option value="未使用">優惠券未使用</option>
            </select>
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="account" />
            <button class="btn btn-outline-success" type="submit" >
              Search
            </button>
          </form>
        </div>
      </div>
    </nav>
  </div>
