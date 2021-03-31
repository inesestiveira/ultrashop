<?php

	require("config.php");

	$query = $db->prepare("
		SELECT category_id,name
		FROM categories
		WHERE parent_id = 0
	");

	$query->execute();

	$categories = $query->fetchAll( PDO::FETCH_ASSOC );

?>

<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>UltraShop Homepage</title>
	</head>
	<body>
		<ul>
<?php
	foreach($categories as $category) {
		echo '
			<li>
				<a href="categories.php?parent_id='.$category["category_id"].'">'.$category["name"].'</a> 
			</li>
		';
	}

	// O numero que queremos passar de uma pagina para a outra é o category_id
?>
		</ul>
		<nav>
			<a href="cart.php">Ver carrinho</a>

			<?php

				if(!isset($_SESSION["user_id"])){
					echo '<a href="login.php">Login</a>';
				}else {
					echo '<a href="logout.php">Logout</a>';
				}

			?>

		</nav>
	</body>
</html>