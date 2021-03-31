<?php
/*
GET  <-- coisas que não fazem alterações
POST <-- coisas que fazem alterações
*/

require("config.php");

header("Content-Type: application/json");

if( isset($_POST["request"]) ) {

    if(
        $_POST["request"] === "removeProduct" &&
        !empty($_POST["product_id"])
    ) {

        unset( $_SESSION["cart"][ (int)$_POST["product_id"] ] );
        
        echo '{"status":"OK"}';
    }
    else if(
        $_POST["request"] === "changeQuantity" &&
        !empty($_POST["product_id"]) &&
        !empty($_POST["quantity"]) &&
        (int)$_POST["quantity"] > 0
    ) {
        
        $_SESSION["cart"][ (int)$_POST["product_id"] ]["quantity"] = (int)$_POST["quantity"];
        
        echo '{"status":"OK"}';
    }
}
else {
    echo '{"status":"error"}';
}
?>