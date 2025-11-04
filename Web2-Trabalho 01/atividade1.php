<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #ffcc70, #ff884d);
        height: 100vh;
        margin: 0;

    }
    .resposta {
        width: 350px;
        background: #fff;
        border: 2px solid #d35400;
        border-radius: 15px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        padding: 20px;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 1.6;
        text-align: left;
    }

    .resposta h2 {
        color: #e74c3c;
        margin-bottom: 15px;
        text-align: center;
    }

    .resposta p {
        margin: 6px 0;
        font-size: 15px;
    }

    .resposta .total {
        font-weight: bold;
        font-size: 18px;
        margin-top: 15px;
        text-align: center;
        color: #27ae60;
    }


</style>

<?php
$nome = $_POST["nome"];
$pizza = $_POST["pizza"];
$borda = isset($_POST["bordaPizza"]) ? $_POST["bordaPizza"] : [];
$bebidas = isset($_POST["bebida"]) ? $_POST["bebida"] : [];

$valorborda = 0;
$valorBebidas = 0;
$valorpizza = 0;

echo "<div class='fundo'>";
echo "<div class='resposta'>";
echo "<h2>Resumo do Pedido</h2>";
echo "<p><strong>Nome:</strong> $nome</p>";
echo "<p><strong>Pizza selecionada:</strong> $pizza</p>";

if ($pizza == "mussarela") {
    $valorpizza = 30;
} else if ($pizza == "calabresa") {
    $valorpizza = 32;
} else {
    $valorpizza = 35;
}
echo "<p><strong>Valor da pizza:</strong> R$ $valorpizza,00</p>";

if ($borda == "sim") {
    $valorborda = 5;
    echo "<p>Borda recheada adicionada (+R$ 5,00)</p>";
} else {
    echo "<p>Sem borda recheada</p>";
}

if (!empty($bebidas)) {
    echo "<p><strong>Bebidas:</strong></p>";
    foreach ($bebidas as $bebida) {
        switch ($bebida) {
            case "refrigerante":
                $valorBebidas += 10;
                echo "<p>- Refrigerante (+R$ 10,00)</p>";
                break;
            case "suco":
                $valorBebidas += 15;
                echo "<p>- Suco (+R$ 15,00)</p>";
                break;
            case "agua":
                $valorBebidas += 5;
                echo "<p>- Água (+R$ 5,00)</p>";
                break;
        }
    }
}

$totalpedido = $valorpizza + $valorborda + $valorBebidas;
echo "<p class='total'>Total do pedido: R$ $totalpedido,00</p>";
echo "</div></div>";
?>
