<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$query = $pdo->prepare("DELETE FROM avaliacao WHERE id = ?");
$query->execute([$_GET['id']]);

header("Location: gerenciar_avaliacoes.php");
exit;
?>