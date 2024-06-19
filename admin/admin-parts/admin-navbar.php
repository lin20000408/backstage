<?php
if (!isset($pageName)) {
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

<nav class="w-75" id="right-content">
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
                <a class="nav-link" href="admin.php">後臺管理</a>
              </li>
            <li class="nav-item">
              <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="admin.php">列表</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $navbarName == 'addAdmin' ? 'active' : '' ?>" href="add-admin.php">新增</a>
            </li>

          </ul>
          <ul class="navbar-nav mb-2 mb-lg-0">
            <?php if (isset($_SESSION['admin'])): ?>
              <!-- <li class="nav-item">
              <a class="nav-link"><?= $_SESSION['admin']['adminName'] ?></a>
            </li> -->
              <!-- <li class="nav-item">
                <a class="nav-link" href="logoutAdmin.php">登出</a>
              </li> -->
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="login-admin.php">登入</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="register.php">註冊</a>
              </li> -->
            <?php endif ?>
        </div>
      </div>
    </nav>
  </div>