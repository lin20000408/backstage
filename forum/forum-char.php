<?php require __DIR__ . '/../parts/Database-connection.php'; ?>

<?php

//$title = '統計圖表';

// 設定pageName(頁面名稱)
$pageName = 'forum-char';

// 管理者照片
// include '../add-picture.php';

// 获取每月發文情况
$query = " SELECT DATE_FORMAT(`time`, '%Y-%m') AS `yearDate`, COUNT(*) as 
`amount` FROM articles GROUP BY `yearDate` ORDER BY `yearDate` ASC ";
$result = $pdo->query($query);
$labels = []; // 创建一个空数组来存储优惠券名称，作为图表的标签
$data = [];
//用while把每筆資料丟入
$row = $result->fetchAll();

foreach ($row as $r) {
  // 获取年月和发文数量
  $yearDate = $r['yearDate'];
  $amount = $r['amount'];

  // 将年月和发文数量添加到相应的数组中
  $labels[] = $yearDate;
  $data[] = $amount;
}



?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include './forum-parts/forum-chart-main.php' ?>

  <nav class="w-75" style="margin-left: 454px;">
    <?php include './forum-parts/forum-chart-navbar.php' ?>
    <div class="container" style="overflow: hidden;">

      <h2 class="text-center">統計圖表</h2>

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
      label: '發文數量',
      data: <?php echo json_encode($data); ?>,
    }]
  };

  const usageConfig = {
    type: 'bar', // 图表类型为折線图
    data: usageData, // 设置图表的数据为使用数量数据
  };
  var usageCtx = document.getElementById('barChart'); // 获取长条图的 Canvas 元素
  var usageChart = new Chart(usageCtx, usageConfig); // 创建长条图实例并传入配置
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>