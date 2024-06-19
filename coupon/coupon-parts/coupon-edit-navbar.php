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

            <!-- 優惠劵按鈕連線到coupon.php -->
            <a class="navbar-brand" href="coupon.php">優惠劵</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!-- 總表按鈕連線到coupon.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon' ? 'active' : '' ?>" href="coupon.php">總表</a>
                    </li>

                    <li class="nav-item">
                        <!-- 有效按鈕連線到coupons-valid.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon-valid' ? 'active' : '' ?>" href="coupon-valid.php">有效</a>
                    </li>

                    <li class="nav-item">
                        <!-- 過期按鈕連線到coupons-expired.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon-expired' ? 'active' : '' ?>" href="coupon-expired.php">過期</a>
                    </li>

                    <li class="nav-item">
                        <!-- 新增按鈕連線到add.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon-add' ? 'active' : '' ?>" href="coupon-add.php">新增</a>
                    </li>

                    <li class="nav-item">
                        <!-- 發送管理按鈕連線到coupon-send-management.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon-send-management' ? 'active' : '' ?>" href="coupon-send-management.php">發送管理</a>
                    </li>

                    <li class="nav-item">
                        <!-- 統計圖表按鈕連線到coupon-chart.php -->
                        <!-- 反白按鈕外觀 -->
                        <a class="nav-link <?= $pageName == 'coupon-chart' ? 'active' : '' ?>" href="coupon-chart.php">統計圖表</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>