<?php
require __DIR__ . '/../parts/Database-connection.php';
$title = '會員管理者列表';
$pageName = 'user-';
$navbarName = 'chart';
$id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;
// 管理者資訊
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();


$query = "
    SELECT 
        CASE 
            WHEN address LIKE '%台北市%' THEN '台北市'
            WHEN address LIKE '%新北市%' THEN '新北市'
            WHEN address LIKE '%桃園市%' THEN '桃園市'
            WHEN address LIKE '%台中市%' THEN '台中市'
            WHEN address LIKE '%台南市%' THEN '台南市'
            WHEN address LIKE '%高雄市%' THEN '高雄市'
            WHEN address LIKE '%基隆市%' THEN '基隆市'
            WHEN address LIKE '%新竹市%' THEN '新竹市'
            WHEN address LIKE '%嘉義市%' THEN '嘉義市'
            WHEN address LIKE '%新竹縣%' THEN '新竹縣'
            WHEN address LIKE '%苗栗縣%' THEN '苗栗縣'
            WHEN address LIKE '%彰化縣%' THEN '彰化縣'
            WHEN address LIKE '%南投縣%' THEN '南投縣'
            WHEN address LIKE '%雲林縣%' THEN '雲林縣'
            WHEN address LIKE '%嘉義縣%' THEN '嘉義縣'
            WHEN address LIKE '%屏東縣%' THEN '屏東縣'
            WHEN address LIKE '%宜蘭縣%' THEN '宜蘭縣'
            WHEN address LIKE '%花蓮縣%' THEN '花蓮縣'
            WHEN address LIKE '%台東縣%' THEN '台東縣'
            WHEN address LIKE '%澎湖縣%' THEN '澎湖縣'
            WHEN address LIKE '%金門縣%' THEN '金門縣'
            WHEN address LIKE '%連江縣%' THEN '連江縣'
            ELSE '其他縣市' 
        END AS city,
        COUNT(*) AS amount 
    FROM 
        `midterm`.user 
    WHERE 
        address LIKE '%台北市%' OR
        address LIKE '%新北市%' OR
        address LIKE '%桃園市%' OR
        address LIKE '%台中市%' OR
        address LIKE '%台南市%' OR
        address LIKE '%高雄市%' OR
        address LIKE '%基隆市%' OR
        address LIKE '%新竹市%' OR
        address LIKE '%嘉義市%' OR
        address LIKE '%新竹縣%' OR
        address LIKE '%苗栗縣%' OR
        address LIKE '%彰化縣%' OR
        address LIKE '%南投縣%' OR
        address LIKE '%雲林縣%' OR
        address LIKE '%嘉義縣%' OR
        address LIKE '%屏東縣%' OR
        address LIKE '%宜蘭縣%' OR
        address LIKE '%花蓮縣%' OR
        address LIKE '%台東縣%' OR
        address LIKE '%澎湖縣%' OR
        address LIKE '%金門縣%' OR
        address LIKE '%連江縣%'
    GROUP BY 
        city
    ORDER BY 
        amount DESC;
";

$result = $pdo->query($query);
$labels = []; // 创建一个空数组来存储各縣市名稱，作为圖表的标签
$data = [];

$row = $result->fetchAll();
foreach ($row as $r) {
  // 将縣市名稱和人數添加到相应的数组中
  $labels[] = $r['city'];
  $data[] = $r['amount'];
}
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<style>
  .user {
    color: blue;
    text-align: center;
  }

  nav.navbar .nav-item .nav-link.active {
    border-radius: 10px;
    background-color: #0d6efd;
    color: white;
    font-weight: 800;
  }

  #right-content {
    margin-left: 452px;
  }
</style>
<div class="d-flex w-100 h-100">
  <?php include __DIR__ . '/user-html-main.php' ?>
  <nav class="w-75" id="right-content">
    <div class="container">
      <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="user.php">會員管理</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="user.php">基本資料</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'coupon' ? 'active' : '' ?>" href="user-coup.php">優惠券</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'chart' ? 'active' : '' ?>" href="user-chart.php">統計圖表</a>
              </li>
            </ul>
            <form class="d-flex ms-auto" role="search" method="GET" action="./user-api/user-coupon-api.php" name="form1"
              onsubmit="sendData(event)">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="account" />
              <button class="btn btn-outline-success" type="submit">
                Search
              </button>
            </form>
          </div>
        </div>
      </nav>
    </div>
    <div class="container">
      <h2 class="user" style="text-align:center">統計圖表</h2>
      <div class="d-flex flex-column align-items-center">
        <!-- 長條圖-使用情況 -->
        <canvas id="barChart" class="w-100 h-100 my-5"></canvas>
      </div>
    </div>
  </nav>
</div>
<?php include __DIR__ . '/../parts/html-js.php' ?>

<script>
  // 使用 PHP 生成的数据(長條圖-使用情況)
  const usageData = {
    labels: <?php echo json_encode($labels); ?>,
    datasets: [{
      label: '台灣各區會員人數',
      data: <?php echo json_encode($data); ?>,
      backgroundColor: 'rgba(54, 162, 235, 0.2)', // 長條顏色設定
      borderColor: 'rgba(54, 162, 235, 1)', // 長條邊框顏色設定
      borderWidth: 1 // 長條邊框寬度設定
    }]
  };

  const usageConfig = {
    type: 'bar', // 图表类型为长條图
    data: usageData, // 设置图表的数据为使用数量数据
    options: {
      scales: {
        y: {
          beginAtZero: true // Y轴从0开始
        }
      }
    }
  };
  var usageCtx = document.getElementById('barChart'); // 获取长条图的 Canvas 元素
  var usageChart = new Chart(usageCtx, usageConfig); // 创建长条图实例并传入配置

</script>
<?php include __DIR__ . '/../parts/html-footer.php' ?>