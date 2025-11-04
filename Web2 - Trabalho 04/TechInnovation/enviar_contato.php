<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $pdo->prepare("INSERT INTO contato (nome, email, mensagem) VALUES (?, ?, ?)");
    $query->execute([$_POST['nome'], $_POST['email'], $_POST['mensagem']]);
    header("Location: index.php?contato=sucesso");
    exit;
}

header("Location: index.php");
exit;
?>