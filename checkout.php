<?php

    require("config.php");

    if(!isset($_SESSION["user_id"])){
        header("Location: login.php");
        exit;

    } else if(!isset($_SESSION["cart"])) {
        header("Location: ./");
        exit;

    } else {
        
        $query_orders = $db->prepare("
        INSERT INTO orders (user_id)
        VALUES(?)
		");

		$query_orders->execute([
            (int)$_SESSION["user_id"]
        ]);

        $order_id = $db->lastInsertId();

        foreach($_SESSION["cart"] as $item) {
            $query = $db->prepare("
            INSERT INTO orderdetails (order_id, product_id, quantity, price)
            VALUES(?,?,?,?)
            ");

            $query->execute([
                $order_id,
                $item["product_id"],
                $item["quantity"],
                $item["price"]
            ]);

            $query = $db->prepare("
            UPDATE products
            SET stock = stock - ?
            WHERE product_id = ?
            ");

            $query->execute([
                $item["quantity"],
                $item["product_id"]
            ]);
        }

    }

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Ultra Shop - Encomenda</title>
</head>
<body>
    <p>Encomenda registada com sucesso, iremos entrar em contacto para a efetuação do pagamento da mesma.</p>
</body>
</html>