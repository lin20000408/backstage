<?php require __DIR__ . '/../../parts/Database-connection.php'; ?>
<?php
//有照片的（篩選）not null

// 星星數 （排序）order
if (!isset($pageName)) {
    $pageName = "";
}

//  評價內容 (模糊搜尋 %like 包含特定字的時候)
// 
$rows = []; //default
$sql = "SELECT * FROM `midterm`.product_reviews";
$rows = $pdo->query($sql)->fetchAll();


?>
<nav class="w-75">
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
            <div class="container-fluid">
                <a class="navbar-brand  " href="product-review-all-id.php">商品評價管理</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $pageName == 'product-review-all-id' ?  'active' : '' ?>" href="product-review-all-id.php">全部評價</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageName == 'product-review-groupBy-photo' ?  'active' : '' ?>" href="product-review-groupBy-photo.php">附上照片</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                星等
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item nav-link <?= $pageName == 'product-review-star-five' ?  'active' : '' ?>" href="product-review-star-five.php"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></a></li>
                                <li>
                                    <a class="dropdown-item nav-link <?= $pageName == 'product-review-star-four' ?  'active' : '' ?>" href="product-review-star-four.php"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></a>
                                </li>

                                <li>
                                    <a class="dropdown-item nav-link <?= $pageName == 'product-review-star-three' ?  'active' : '' ?>" href="product-review-star-three.php"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link <?= $pageName == 'product-review-star-two' ?  'active' : '' ?>" href="product-review-star-two.php"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></a>
                                </li>
                                <li>
                                    <a class="dropdown-item nav-link  <?= $pageName == 'product-review-star-one' ?  'active' : '' ?>" href="product-review-star-one.php"><i class="fa-solid fa-star"></i></a>
                                </li>
                            </ul>
                        </li>

                    </ul>

                    <form class="d-flex" role="search" id="search">
                        <input class="form-control me-2 " name="search" type="search" placeholder="請輸入評論關鍵字" aria-label="Search" />
                        <button class="btn btn-outline-success" type="submit">
                            search
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <script>
        //瀏覽器
        let searchUrl = 'product-reviews-parts/product-review-navbar-api.php';

        async function fetchResults(url) {
            try {
                let response = await fetch(url);
                let data = await response.json();
                if (data.success) {
                    if (data.ids.length > 0) {
                        // 如果有多个 ID，构建带有所有 ID 的 URL
                        let idsString = data.ids.join(',');
                        location.href = `product-review-search.php?id=${idsString}`;
                    } else {
                        // 如果没有 ID，重定向到相应页面
                        location.href = 'product-review-all-id.php';
                    }
                } else {
                    // 如果没有成功，则处理错误
                    console.error('Search operation was not successful');
                }
            } catch (error) {
                console.log(`Error: ${error}`);
            }
        }

        document.querySelector('#search').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission behavior
            let searchTerm = document.querySelector('input[name="search"]').value;
            let searchParams = new URLSearchParams({
                search: searchTerm // 使用搜索词构建查询参数
            });
            let fullUrl = `${searchUrl}?${searchParams}`;
            fetchResults(fullUrl); // 异步获取搜索结果
        });
    </script>