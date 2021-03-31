<?php
	
	require("config.php");

	if(!isset($_GET["parent_id"])) {
		header("HTTP/1.1 400 Bad Request");
		die("400 Bad Request");
	}

	// Como aqui já nao é parent_id = 0 e vai ser buscada atraves de GET, tem que ser com o ponto de interrogacao
	// Para mostrarmos no título h1, vamos na query fazer um JOIN e esse JOIN vai ser feito em cima de sí memsmo pois os nomes estão na mesma tabela

	$query = $db->prepare("
		SELECT cat1.category_id, cat1.name, cat2.name AS parent_name
		FROM categories AS cat1
		INNER JOIN categories AS cat2 ON (cat1.parent_id = cat2.category_id)
		WHERE cat1.parent_id = ?
	");

	$query->execute([
		$_GET["parent_id"]
	]);

	$subcategories = $query->fetchAll( PDO::FETCH_ASSOC );

	//echo "<pre>";
	//print_r($subcategories);

	if(empty($subcategories)) {
		include("e404.php");
	}
?>

<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title><?php echo $subcategories[0]["parent_name"]; ?></title>
	</head>
	<body>
		<h1><?php echo $subcategories[0]["parent_name"]; ?></h1>
		<ul>
<?php
	foreach($subcategories as $subcategory) {
		echo '
			<li>
				<a href="product_list.php?category_id='.$subcategory["category_id"].'">'.$subcategory["name"].'</a>
			</li>
		';
	}
?>
		</ul>
		<nav>
			<a href="index.php">Voltar aos artigos</a>
		</nav>
	</body>
</html>