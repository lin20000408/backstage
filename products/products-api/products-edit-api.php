<?php
// require __DIR__ .'/parts/admin-required.php';
require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');

// Initialize output array
$output = [
  'success' => false,
  'postData' => $_POST, // For debugging purposes
  'error' => '',
  'code' => 0, // For debugging or tracing code
];

// Check if required fields are present in the POST data
if (!empty($_POST['id']) && !empty($_POST['name'])) {
  // Sanitize and validate input data
  $productId = intval($_POST['id']);
  $name = $_POST['name'];
  $mainType = $_POST['main-type'];
  $material = $_POST['material'];
  $style = $_POST['style'];
  $price = $_POST['price'];

  // Prepare and execute the SQL statement to update product details
  $sql = "UPDATE `products` SET 
            `name` = ?,
            `pmt_id` = ?,
            `material_details` = ?,
            `style` = ?,
            `price` = ?
          WHERE `id` = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$name, $mainType, $material, $style, $price, $productId]);

  $output['success'] = boolval($stmt->rowCount());

  // Check if a photo URL was provided in the POST data
  if (isset($_POST['url']) && !empty($_POST['url'])) {
    // Check if the product_id already exists in product_photos table
    $checkSql = "SELECT COUNT(*) FROM `product_photos` WHERE product_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$productId]);
    $count = $checkStmt->fetchColumn();

    // Determine whether to insert or update the photo URL
    if ($count == 0) {
      // Insert a new row if product_id does not exist
      $photoSql = "INSERT INTO `product_photos` (url, product_id) VALUES (?, ?)";
    } else {
      // Update the existing row if product_id already exists
      $photoSql = "UPDATE `product_photos` SET url = ? WHERE product_id = ?";
    }

    // Prepare and execute the SQL statement for photo URL
    $photoStmt = $pdo->prepare($photoSql);
    $photoUrl = filter_var($_POST['url'], FILTER_SANITIZE_URL); // Sanitize URL
    $photoStmt->execute([$photoUrl, $productId]);
    $output['success'] = boolval($photoStmt->rowCount());
  }
} else {
  $output['error'] = 'Required fields are missing';
}

// Output the response as JSON
echo json_encode($output, JSON_UNESCAPED_UNICODE);
