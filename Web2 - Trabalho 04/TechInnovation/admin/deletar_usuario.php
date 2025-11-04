<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
if ($id != $_SESSION['usuario_id']) {
    $query = $pdo->prepare("SELECT foto FROM usuario WHERE id = ?");
    $query->execute([$id]);
    $usuario = $query->fetch();
    
    $query = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
    $query->execute([$id]);
    
    if (!empty($usuario['foto']) && file_exists('../' . $usuario['foto'])) unlink('../' . $usuario['foto']);
}

header("Location: gerenciar_usuarios.php");
exit;
?>