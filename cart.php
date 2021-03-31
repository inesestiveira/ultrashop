<?php

	require("config.php");

	if(
		isset($_POST["send"]) &&
		isset($_POST["quantity"]) &&
		isset($_POST["product_id"]) &&
		is_numeric($_POST["quantity"]) &&
		is_numeric($_POST["product_id"]) &&
		$_POST["quantity"] > 0
	) {

		$query = $db->prepare("
            SELECT price, name, image, product_id, stock
            FROM products
            WHERE product_id = ? AND stock >= ?
		");

		$query->execute([
			$_POST["product_id"],
			$_POST["quantity"]
		]);

		$product = $query->fetchAll( PDO::FETCH_ASSOC );

		if(!empty($product)){

			/* Só adicionamos ao carrinho após validar várias coisas */
			$_SESSION["cart"][$product[0]["product_id"]] = [
				"quantity" => (int)$_POST["quantity"], 
				"product_id" => $product[0]["product_id"],
				"name" => $product[0]["name"],
				"price" => $product[0]["price"],
				"stock" => $product[0]["stock"]
			];
		}
	}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
	<title>Ultra Shop - Carrinho</title>
	<style>
	
		table, tr, td, th {
			border: 1px solid black;
			border-collapse: collapse;
		}
	</style>
</head>
<body>
<?php 
    if(isset($_SESSION["cart"])) {
?>
        <table>
            <tr>
                <th>Artigo</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Total</th>
            </tr>

            <?php

            $total = 0;
            
            foreach($_SESSION["cart"] as $item) {
                
                $subtotal=$item["price"]*$item["quantity"];
                echo'
                <tr>
                    <td>'.$item["name"].'</td>
                    <td>' .$item["quantity"]. '</td>
                    <td>'.$item["price"].'€</td>
                    <td><span class="subtotal">'.$subtotal.'</span>€</td>
                </tr>

                ';

                $total += $subtotal;

            }
            
            ?>

            <tr>
                <td colspan="3"></td>
                <td><span class="total"><?php echo $total; ?></span>€</td>
            </tr>

        </table>
        <nav>
            <a href="./">Voltar ao Ínicio</a>
            <a href="checkout.php">Finalizar a compra</a>
        </nav>
<?php
    }
    else {
        echo "<p>Ainda não tem artigos adicionados</p>";
    }
?>
</body>
</html>