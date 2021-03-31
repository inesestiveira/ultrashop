<?php

    require("config.php");

    if(isset($_SESSION["user_id"])){
        header("Location: ./");
        exit;
    }

    $query = $db->prepare("
        SELECT country_code as code, country_name as name, priority
        FROM countries
        ORDER BY priority DESC, name
		");

		$query->execute();

        $countries = $query->fetchAll( PDO::FETCH_ASSOC );

        foreach($countries as $country) {
            $country_codes[] = $country["code"];
        }
         
        
        if(isset($_POST["send"])){

            foreach($_POST as $key=>$value){
                $_POST[$key] = strip_tags(trim($value));
            }

            if(
                !empty($_POST["name"]) &&
                !empty($_POST["email"]) &&
                !empty($_POST["password"]) &&
                !empty($_POST["address"]) &&
                !empty($_POST["postal_code"]) &&
                !empty($_POST["city"]) &&
                !empty($_POST["phone"]) &&
                $_POST["password"] === $_POST["password_2"] &&
                mb_strlen($_POST["name"]) > 3 && mb_strlen($_POST["name"]) < 64 &&
                mb_strlen($_POST["email"]) > 3 && mb_strlen($_POST["email"]) < 252 &&
                mb_strlen($_POST["password"]) > 1 && mb_strlen($_POST["password"]) < 1000 &&
                mb_strlen($_POST["address"]) > 1 && mb_strlen($_POST["address"]) < 255 &&
                mb_strlen($_POST["postal_code"]) > 1 && mb_strlen($_POST["postal_code"]) < 32 &&
                mb_strlen($_POST["city"]) > 1 && mb_strlen($_POST["city"]) < 64 &&
                mb_strlen($_POST["phone"]) > 1 && mb_strlen($_POST["phone"]) < 32 &&
                filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
                in_array($_POST["country"], $country_codes)

            ){

            $query = $db->prepare("
            INSERT INTO users (name, email, password, address, postal_code, city, country, phone, active)
            VALUES (?,?,?,?,?,?,?,?,1);
            ");
            
            $query->execute([
            
                $_POST["name"],
                strtolower($_POST["email"]),
                password_hash($_POST["password"], PASSWORD_DEFAULT),
                $_POST["address"],
                $_POST["postal_code"],
                $_POST["city"],
                $_POST["country"],
                $_POST["phone"]

            ]);

            $message = "Utilizador criado com sucesso!";

         }else{
             $message = "Amigo não sei bem qual é o erro mas n fizeste qualquer coisa, descobre tu lol";
        }
     }


?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title> Ultra Shop - Criar Conta</title>
</head>
<body>

    <?php

    if(isset($message)) {
        echo '<p class="alert">'.$message.'</p>';
    }

    ?>
    
    <p>Se já tiver uma conta Ultra Shop,<a href="login.php">faça login</a></p>

    <form method="post" action="register.php">
        <div>
            <label>
                Name
                <input type="text" name="name" required>
            </label>
        </div>
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
            <label>
                Repetir Password
                <input type="password" name="password_2" required>
            </label>
        </div>
        <div>
            <label>
                Address
                <input type="text" name="address" required>
            </label>
        </div>
        <div>
            <label>
                Postal Code
                <input type="text" name="postal_code" required>
            </label>
        </div>
        <div>
            <label>
                Country
                <select name="country" required>
                    <?php 
                        foreach($countries as $country){

                            echo '

                            <option value="'.$country["code"].'">'.$country["name"].'</option>

                            ';

                        }
                    ?>
                </select>
            </label>
        </div>
        <div>
            <label>
                City
                <input type="text" name="city" required>
            </label>
        </div>
        <div>
            <label>
                Phone
                <input type="text" name="phone" required>
            </label>
        </div>
        <div>
            <button type="submit" name="send">Login</button>
        </div>
 
    </form>

</body>
</html>