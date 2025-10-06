<?php 
    session_start();
    if(isset($_POST['email']) && isset($_POST['senha'])) {

        $email = $_POST['email'];
        $senha = $_POST['senha'];
        
        if(isset($_COOKIE['email_usuario']) && isset($_COOKIE['senha_usuario'])) {
            $email_cookie = $_COOKIE['email_usuario'];
            $senha_cookie = $_COOKIE['senha_usuario'];
            
            if($email === $email_cookie && $senha === $senha_cookie) {
                // Cria a sessão
                $_SESSION['logado'] = true;
                $_SESSION['nome'] = $_COOKIE['nome_usuario'];
                $_SESSION['email'] = $email;
                
                echo "<alert>Login bem sucedido!</alert>";    
                header("Location: areaUsuario.php");
            } else {
                echo "<alert>Email ou senha incorretos.</alert>";
            }
        } else {
            echo "<alert>Nenhum usuário cadastrado. Por favor, cadastre-se primeiro.</alert>";
        }

        if(isset($_POST['voltar'])) {
            header("Location: cadastro.php");
        }
    }

    // Preenche o email automaticamente do cookie
    $email_preenchido = "";
    if(isset($_COOKIE['email_usuario'])) {
        $email_preenchido = $_COOKIE['email_usuario'];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        br {
            display: none;
        }

        input[type="submit"] {
            width: 48%;
            padding: 14px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 25px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        input[type="submit"][value="Entrar"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-right: 4%;
        }

        input[type="submit"][value="Voltar"] {
            background: #e0e0e0;
            color: #333;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        alert {
            display: block;
            padding: 12px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="#" method="POST">
        <label>Email: </label>
        <input type="text" name="email" value="<?php echo $email_preenchido; ?>"><br>
        <label>Senha: </label>
        <input type="password" name="senha"><br>
        <input type="submit" value="Entrar">
        <input type="submit" value="Voltar" name="voltar">
    </form>
</body>
</html>