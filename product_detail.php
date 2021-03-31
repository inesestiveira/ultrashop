<?php
	
	// Quando começamos a parte do cart, modificamos a nossa Query atual e adicionamos o product_id, para o carrinho saber qual o produto estamos a usar. Num input type hidden, fora do label.

	require("config.php");

	if(!isset($_GET["product_id"])) {
		header("HTTP/1.1 400 Bad Request");
		die("400 Bad Request");
	}

	$query = $db->prepare("
		SELECT product_id,name,description,image,price,stock
		FROM products
		WHERE product_id = ?
	");

	$query->execute([
		$_GET["product_id"]
	]);

	$product = $query->fetchAll( PDO::FETCH_ASSOC );

	if(empty($product)) {
		include("e404.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title><?php echo $product[0]["name"]; ?></title>
		<style>
			.picture img {
				width:50%;
			}
		</style>
	</head>
	<body>
		<h1><?php echo $product[0]["name"]; ?></h1>
		<div class="description">
			<?php echo $product[0]["description"]; ?>
		</div>
		<div class="price"> <?php echo $product[0]["price"]; ?>$ </div>
		<div class="add2cart">
			<form method="post" action="cart.php">
				<label>
					Quantidade
					<input type="number" name="quantity" value="1" min="1" max="<?php echo $product[0]["stock"]; ?>">
				</label>
				<input type="hidden" name="product_id" value="<?php echo $product[0]["product_id"]; ?>">
				<button type="submit" name="send">Adicionar</button>
			</form>	
		</div>
		<div class="picture">
			<img src="images/<?php echo $product[0]["image"];?>" alt="">
		</div>
	</body>
</html>