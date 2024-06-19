<?php include __DIR__ . '/../parts/Database-connection.php'; ?>

<?php
$title = '統計圖表';

// 設定pageName(頁面名稱)
$pageName = 'coupon-chart';

//管理者資訊
include __DIR__ . '/../add-picture.php';

// 从数据库中获取 coupon_name 数据
$query = "SELECT coupon_name FROM coupon_send_management";
$result = $pdo->query($query); // 使用 $pdo 变量执行查询

// 将 coupon_name 数据转换为数组
$coupon_names = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // 使用 PDO::FETCH_ASSOC 获取关联数组
    $coupon_names[] = $row['coupon_name']; // 将每条记录的 coupon_name 添加到数组中
}

// 计算每个 coupon_name 的出现次数
$coupon_counts = array_count_values($coupon_names); // 使用 array_count_values 函数计算每个值的出现次数

// 将数据转换为 Chart.js 所需的格式
$data_labels = array_keys($coupon_counts); // 获取优惠券名称作为图表的标签
$data_values = array_values($coupon_counts); // 获取优惠券发送数量作为图表的数据
$data = [
    'labels' => $data_labels, // 设置图表的标签为优惠券名称数组
    'datasets' => [
        [
            'label' => '發送數量', // 设置数据集的标签为“发送数量”
            'data' => $data_values, // 设置数据集的数据为优惠券发送数量数组
        ]
    ]
];


// 获取每张优惠券的使用情况
$query = "SELECT coupon_name, usage_status FROM coupon_send_management";
$result = $pdo->query($query);
$coupon_usage_counts = []; // 创建一个空数组来存储每张优惠券的使用情况
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $coupon_name = $row['coupon_name']; // 获取优惠券名称
    $usage_status = $row['usage_status']; // 获取使用状态
    if (!isset($coupon_usage_counts[$coupon_name])) { // 如果该优惠券尚未在数组中记录
        // 初始化该优惠券的使用情况数组，已使用和未使用数量均为零
        $coupon_usage_counts[$coupon_name] = ['已使用' => 0, '未使用' => 0];
    }
    // 根据使用状态增加对应计数
    if ($usage_status === '已使用') {
        $coupon_usage_counts[$coupon_name]['已使用']++;
    } else {
        $coupon_usage_counts[$coupon_name]['未使用']++;
    }
}

// 将数据转换为 Chart.js 所需的格式
$usage_labels = []; // 创建一个空数组来存储优惠券名称，作为图表的标签
$usage_data = []; // 创建一个关联数组来存储已使用和未使用数量
foreach ($coupon_usage_counts as $coupon_name => $usage_counts) {
    $usage_labels[] = $coupon_name; // 将优惠券名称添加到标签数组中
    // 将已使用和未使用数量分别添加到数据数组中
    $usage_data['已使用'][] = $usage_counts['已使用'];
    $usage_data['未使用'][] = $usage_counts['未使用'];
}

?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">
    <?php include __DIR__ . '/coupon-chart-main.php' ?>

    <nav class="w-75" style="margin-left: 454px;">
        <?php include __DIR__ . '/coupon-parts/coupon-chart-navbar.php' ?>
        
        <div class="container" style="overflow: hidden;">
            <h2 class="text-center">統計圖表</h2>
            <div class="d-flex flex-column align-items-center">
                <!-- 圓餅圖-發送數量 -->
                <canvas id="pieChart" class="w-100 h-100 my-5"></canvas>
                <!-- 長條圖-使用情況 -->
                <canvas id="barChart" class="w-100 h-100 my-5"></canvas>
            </div>
        </div>
    </nav>
</div>

<?php include __DIR__ . '/../parts/html-js.php' ?>

<script>
    // 使用 PHP 生成的数据(圓餅圖-發送數量)
    const data = <?php echo json_encode($data); ?>; // 使用 PHP 生成的数据作为图表的数据
    const config = {
        type: 'pie', // 图表类型为圆饼图
        data: data, // 设置图表的数据为发送数量数据
        options: {
            responsive: true, // 图表响应式
            plugins: {
                legend: {
                    position: 'top', // 设置图例位置为顶部
                },
                title: {
                    display: true,
                    text: '優惠券發送數量', // 设置图表标题
                    font: {
                        size: 30, // 指定标题的字体大小
                    }
                }
            }
        },
    };
    var ctx = document.getElementById('pieChart'); // 获取圆饼图的 Canvas 元素
    var myChart = new Chart(ctx, config); // 创建圆饼图实例并传入配置


    // 使用 PHP 生成的数据(長條圖-使用情況)
    const usageData = {
        labels: <?php echo json_encode($usage_labels); ?>, // 设置图表的标签为优惠券名称数组
        datasets: [{
                label: '已使用', // 第一个数据集的标签
                data: <?php echo json_encode($usage_data['已使用']); ?>, // 第一个数据集的数据，表示已使用的数量
                backgroundColor: 'rgba(255, 99, 132, 0.5)', // 设置已使用的数据条的背景颜色
                borderColor: 'rgba(255, 99, 132, 1)', // 设置已使用的数据条的边框颜色
                borderWidth: 1 // 设置已使用的数据条的边框宽度
            },
            {
                label: '未使用', // 第二个数据集的标签
                data: <?php echo json_encode($usage_data['未使用']); ?>, // 第二个数据集的数据，表示未使用的数量
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // 设置未使用的数据条的背景颜色
                borderColor: 'rgba(54, 162, 235, 1)', // 设置未使用的数据条的边框颜色
                borderWidth: 1 // 设置未使用的数据条的边框宽度
            }
        ]
    };

    const usageConfig = {
        type: 'bar', // 图表类型为长条图
        data: usageData, // 设置图表的数据为使用数量数据
        options: {
            responsive: true, // 图表响应式
            plugins: {
                legend: {
                    position: 'top', // 设置图例位置为顶部
                },
                title: {
                    display: true,
                    text: '優惠券使用情況', // 设置图表标题
                    font: {
                        size: 30, // 设置标题字体大小
                    }
                }
            }
        },
    };
    var usageCtx = document.getElementById('barChart'); // 获取长条图的 Canvas 元素
    var usageChart = new Chart(usageCtx, usageConfig); // 创建长条图实例并传入配置
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>