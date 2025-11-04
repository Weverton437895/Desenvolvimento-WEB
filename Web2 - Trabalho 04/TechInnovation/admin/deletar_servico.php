<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$query = $pdo->prepare("SELECT foto FROM servico WHERE id = ?");
$query->execute([$id]);
$produto = $query->fetch();

$query = $pdo->prepare("DELETE FROM servico WHERE id = ?");
$query->execute([$id]);

if (!empty($produto['foto']) && file_exists('../' . $produto['foto'])) unlink('../' . $produto['foto']);

header("Location: gerenciar_servicos.php");
exit;
?>