<?php
require __DIR__ . '/../parts/Database-connection.php';
$pdo = mysqli_connect($host, $username,$password, $db_name ); 

if(isset($_POST["export"]))
{
    $query = "SELECT order_details.order_id,
                     product_name,
                     product_size,
                     product_color,
                     product_reviews.*
              FROM `midterm`.product_reviews
              INNER JOIN `midterm`.order_details 
              ON order_details.product_reviews_id = product_reviews.id";
    $res = mysqli_query($pdo, $query);
    if(mysqli_num_rows($res) > 0)
    {
        $export = '<table> 
                        <tr> 
                            <th>評論編號</th>
                            <th>訂單編號</th>
                            <th>商品名稱</th>
                            <th>商品尺寸</th>
                            <th>商品顏色</th>
                            <th>評價照片</th>
                            <th>評價內容</th>
                            <th>星等</th>
                            <th>發布時間</th>
                        </tr>';

        while($row = mysqli_fetch_array($res))
        {
            $export .= '<tr>
                            <td>'.$row["id"].'</td> 
                            <td>'.$row["order_id"].'</td> 
                            <td>'.$row["product_name"].'</td> 
                            <td>'.$row["product_size"].'</td> 
                            <td>'.$row["product_color"].'</td> 
                            <td>'.$row["photo"].'</td> 
                            <td>'.$row["content"].'</td> 
                            <td>'.$row["stars"].'</td> 
                            <td>'.$row["review_time"].'</td> 
                        </tr>';
        }
        $export .= '</table>';
        
        // 设置响应头，告诉浏览器这是一个 Excel 文件
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=product-reviews.xls');
        
        // 输出 Excel 文件内容
        echo $export;
    }
}

