<?php
// 导入数据库连接
require __DIR__ . '/../parts/Database-connection.php';

// 建立数据库连接
$pdo = mysqli_connect($host, $username, $password, $db_name);

// 检查是否点击了导出按钮
if (isset($_POST["excel"])) {
    // 构建查询语句
    $query = "
        SELECT 
            coupon.id AS coupon_id,
            coupon.name AS coupon_name,
            coupon.money AS coupon_money,
            user.id AS user_id,
            user.name AS user_name,
            usage_status,
            usage_time,
            send_time 
        FROM 
            coupon   
        JOIN 
            coupon_send_management ON coupon.id = coupon_send_management.coupon_id
        JOIN 
            user ON coupon_send_management.user_id = user.id
    ";

    // 执行查询
    $res = mysqli_query($pdo, $query);

    // 检查是否有数据返回
    if ($res) {
        // 检查是否有行
        if (mysqli_num_rows($res) > 0) {
            // 初始化导出变量，并设置表格样式和标题行
            $excel = '<table style="border-collapse: collapse;"> 
                            <tr> 
                                <th style="border: 1px solid #000;">優惠劵編號</th>
                                <th style="border: 1px solid #000;">優惠劵名稱</th>
                                <th style="border: 1px solid #000;">折抵金額</th>
                                <th style="border: 1px solid #000;">會員編號</th>
                                <th style="border: 1px solid #000;">會員姓名</th>
                                <th style="border: 1px solid #000;">使用狀況</th>
                                <th style="border: 1px solid #000;">使用時間</th>
                                <th style="border: 1px solid #000;">發送時間</th>
                            </tr>';

            // 将查询结果添加到导出变量中，并设置单元格样式
            while ($row = mysqli_fetch_array($res)) {
                $excel .= '<tr>
                                <td style="border: 1px solid #000;">' . $row["coupon_id"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["coupon_name"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["coupon_money"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["user_id"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["user_name"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["usage_status"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["usage_time"] . '</td> 
                                <td style="border: 1px solid #000;">' . $row["send_time"] . '</td> 
                            </tr>';
            }
            $excel .= '</table>';

            // 设置响应头，告诉浏览器这是一个 Excel 文件
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=優惠劵發送管理表.xls');

            // 输出 Excel 文件内容
            echo $excel;
        } else {
            echo "没有符合条件的数据。";
        }
    } else {
        echo "查询失败：" . mysqli_error($pdo);
    }
} else {
    echo "未触发导出操作。";
}
?>
