<?php
	
	require("config.php");

	if(!isset($_GET["category_id"])) {
		header("HTTP/1.1 400 Bad Request");
		die("400 Bad Request");
	}

	$query = $db->prepare("
		SELECT p.product_id,p.name, c.name AS category
		FROM products p
		INNER JOIN categories c USING(category_id)
		WHERE p.category_id = ?
	");

	$query->execute([
		$_GET["category_id"]
	]);

	$products = $query->fetchAll(PDO::FETCH_ASSOC) ;

	if(empty($products)) {
		include("e404.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>UltraShop - <?php echo $products[0]["category"]; ?></title>
	</head>
	<body>
		<h1><?php echo $products[0]["category"]; ?></h1>
		<ul>
<?php
	foreach($products as $product) {
		echo '
			<li>
				<a href="product_detail.php?product_id='.$product["product_id"].'">'.$product["name"].'</a>
			</li>
		';
	}
?>
		</ul>
	</body>
</html>