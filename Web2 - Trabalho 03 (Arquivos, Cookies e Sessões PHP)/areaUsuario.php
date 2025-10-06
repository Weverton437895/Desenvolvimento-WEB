<?php 
    session_start();
    
    // Verifica se está logado pela sessão
    if(isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
        $nome = $_SESSION['nome'];
        $email = $_SESSION['email'];
        
        echo "<alert>Bem-vindo, $nome! <br> $email</alert>";

        if(isset($_POST['sair'])) {
           // Destroi a sessão
           session_destroy();
           
           // Remove os cookies
           setcookie("nome_usuario", "", time() - 3600);
           setcookie("email_usuario", "", time() - 3600);
           setcookie("senha_usuario", "", time() - 3600);

           header("Location: login.php");
           exit();
        }
    } else {
        echo "<alert>Faça login para acessar esta área.</alert>";
        header("Location: login.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Usuário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        form {
            background: white;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 550px;
            text-align: center;
        }

        alert {
            display: block;
            padding: 30px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
            border: 2px solid #81c784;
            border-radius: 12px;
            margin-bottom: 35px;
            text-align: center;
            font-size: 20px;
            line-height: 1.8;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.15);
        }

        br {
            display: block;
            margin: 10px 0;
        }

        input[type="submit"] {
            padding: 16px 50px;
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
        }

        input[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(229, 57, 53, 0.5);
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
        }

        input[type="submit"]:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
        }

        form::before {
            content: "🎉";
            display: block;
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <form action="#" method="POST"> 
        <input type="submit" value="Sair" name="sair">
    </form>
</body>
</html>