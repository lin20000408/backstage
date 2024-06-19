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


<!-- 主要的導覽列 -->
<nav class="w-75">
  <div class="container">
    <!-- Bootstrap的Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
      <div class="container-fluid">
        <!-- Navbar的品牌標誌 -->
        <a class="navbar-brand" href="restock.php">進貨紀錄</a>
        
        <!-- Navbar的折疊內容 -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Navbar的連結清單 -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <!-- 第一個連結項目 -->
            <li class="nav-item">
              <a class="nav-link <?= $pageName == 'restock-add' ? 'active' : '' ?>" href="restock-add.php">新增</a>
            </li>
            </button>
          </form>
        </div>
      </div>
    </nav>
  </div>