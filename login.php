<?php

    require("config.php");

    if(isset($_SESSION["user_id"])){
        header("Location: ./");
        exit;
    }

    if(isset($_POST["send"])){

        if(
            !empty($_POST["email"]) &&
            !empty($_POST["password"]) &&
            filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
            mb_strlen($_POST["password"]) > 1 && mb_strlen($_POST["password"]) < 1000 
        ){

            /* Validar se o utilizador existe */
            $query = $db->prepare("
                SELECT user_id, password
                FROM users
                WHERE email=? AND active = 1
            ");

            $query->execute([
                mb_strtolower($_POST["email"])
            ]);

            $user = $query->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($user) && password_verify($_POST["password"], $user[0]["password"])){
            
                $_SESSION["user_id"] = $user[0]["user_id"];
                header("Location: cart.php");
                exit;

            }else {
                $message = "Dados Incorrectos";
            }

        }

    }

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title> Ultra Shop - Login</title>
</head>
<body>

    <?php

        if(isset($message)) {
            echo '<p class="alert">'.$message.'</p>';
        }
    
    ?>
    
    <p>Se ainda não tiver uma conta Ultra Shop,<a href="register.php">clique aqui para fazer o registo</a>  (é fácil)</p>

    <form method="post" action="login.php">
        <div>
            <label>
                Email
                <input type="email" name="email" required>
            </label>
        </div>
        <div>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
        </div>
        <div>
            <button type="submit" name="send">Login</button>
        </div>
 
    </form>

</body>
</html>