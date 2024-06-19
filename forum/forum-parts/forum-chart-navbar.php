<?php
// 沒有設定pageName(頁面名稱)會變成空字串，才不會有錯誤
if (!isset($pageName)) {
  $pageName = "";
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


<div class="container">
  <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
    <div class="container-fluid">
      
      <a class="navbar-brand" href="#">論壇</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'forum' ? 'active' : '' ?>" href="forum.php">貼文管理</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $pageName == 'forum-add' ? 'active' : '' ?>" href="forum-add.php">新增</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= $pageName == 'forum-char' ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              統計圖表
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="forum-char.php">發文數量</a></li>
            </ul>
          </li>
        </ul>
      </div>

    </div>
  </nav>
</div>