<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$query = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
$query->execute([$id]);
$usuario = $query->fetch();

if (!$usuario) {
    header("Location: gerenciar_usuarios.php");
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caminhoFoto = $usuario['foto'];
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); 
        $nomeArquivo = uniqid('user_', true) . '.' . $extensao;
        $caminhoFoto = 'images/users/' . $nomeArquivo;
        if (!is_dir('../images/users')) mkdir('../images/users', 0777, true);
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $caminhoFoto);
        if (!empty($usuario['foto']) && file_exists('../' . $usuario['foto'])) unlink('../' . $usuario['foto']);
    }
    
    if (!empty($_POST['senha'])) {
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $query = $pdo->prepare("UPDATE usuario SET nome = ?, email = ?, senha = ?, foto = ?, nivel = ? WHERE id = ?");
        $query->execute([$_POST['nome'], $_POST['email'], $senhaHash, $caminhoFoto, $_POST['nivel'], $id]);
    } else {
        $query = $pdo->prepare("UPDATE usuario SET nome = ?, email = ?, foto = ?, nivel = ? WHERE id = ?");
        $query->execute([$_POST['nome'], $_POST['email'], $caminhoFoto, $_POST['nivel'], $id]);
    }
    
    $sucesso = "Atualizado! Redirecionando...";
    header("refresh:2;url=gerenciar_usuarios.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Editar Usuário</title>
</head>
<body>
    <div class="form-container">
        <h1>Editar Usuário</h1>
        <?php if ($sucesso): ?><div class="message success-message"><?php echo $sucesso; ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group"><label>Nome:</label><input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required></div>
            <div class="form-group"><label>Email:</label><input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required></div>
            <div class="form-group"><label>Nova Senha (deixe vazio para não alterar):</label><input type="password" name="senha"></div>
            <div class="form-group">
                <label>Nível:</label>
                <select name="nivel" required>
                    <option value="usuario" <?php echo $usuario['nivel'] === 'usuario' ? 'selected' : ''; ?>>Usuário</option>
                    <option value="admin" <?php echo $usuario['nivel'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group"><label>Nova Foto:</label><input type="file" name="foto" accept="image/*"></div>
            <button type="submit" class="btn-submit">Atualizar</button>
        </form>
        <div class="text-center"><a href="gerenciar_usuarios.php">← Voltar</a></div>
    </div>
</body>
</html>