<?php 
    session_start();
    if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $arquivo = fopen("cadastro.txt", "w");

        fwrite($arquivo, "Nome: " . $nome . "\n");
        fwrite($arquivo, "Email: " . $email . "\n");
        fwrite($arquivo, "Senha: " . $senha . "\n");

        fclose($arquivo);

        echo "<alert>Cadastro salvo com sucesso!</alert>";

        setcookie("nome_usuario", $nome, time() + 3600);
        setcookie("email_usuario", $email, time() + 3600); 
        setcookie("senha_usuario", $senha, time() + 3600); 

        header("Location: login.php");

    } else {
        echo "<alert>Por favor, preencha todos os campos.</alert>";
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }

        br {
            display: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 25px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        alert {
            display: block;
            padding: 12px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="#" method="POST">
    <label>Digite seu nome:</label>
    <input type="text" name="nome" required><br>
    <label>Email: </label>
    <input type="email" name="email" required><br>
    <label>Senha: </label>
    <input type="password" name="senha" required><br>
    <input type="submit" value="Salvar">
    </form>
</body>
</html>