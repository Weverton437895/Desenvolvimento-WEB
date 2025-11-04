<?php
require 'config.php';
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $query = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $query->execute([$email]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_foto'] = $usuario['foto'];
        $_SESSION['usuario_nivel'] = $usuario['nivel'];

        if ($usuario['nivel'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login - Tech Innovation</title>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        
        <?php if ($erro): ?>
            <div class="message error-message"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required placeholder="Sua senha">
            </div>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="text-center">
            <p>Não tem cadastro? <a href="createUser.php">Cadastre-se aqui</a></p>
            <p><a href="index.php">← Voltar para o início</a></p>
        </div>
    </div>
</body>
</html>