<?php
$quiz = [
    "matematica" => [
        ["pergunta" => "Quanto é 7 x 8?", "a" => "54", "b" => "56", "c" => "58", "d" => "60", "resposta" => "b"],
        ["pergunta" => "Qual é a raiz quadrada de 81?", "a" => "7", "b" => "8", "c" => "9", "d" => "10", "resposta" => "c"],
        ["pergunta" => "Quanto é 15 + 25?", "a" => "35", "b" => "40", "c" => "45", "d" => "50", "resposta" => "b"],
        ["pergunta" => "Quanto é 100 dividido por 4?", "a" => "20", "b" => "25", "c" => "30", "d" => "35", "resposta" => "b"],
        ["pergunta" => "Quanto é 12 - 7?", "a" => "3", "b" => "4", "c" => "5", "d" => "6", "resposta" => "c"]
    ],
    "geografia" => [
        ["pergunta" => "Qual a capital do Brasil?", "a" => "São Paulo", "b" => "Rio de Janeiro", "c" => "Brasília", "d" => "Salvador", "resposta" => "c"],
        ["pergunta" => "Qual é o maior estado do Brasil?", "a" => "Amazonas", "b" => "São Paulo", "c" => "Minas Gerais", "d" => "Bahia", "resposta" => "a"],
        ["pergunta" => "Em que região fica São Paulo?", "a" => "Norte", "b" => "Nordeste", "c" => "Sudeste", "d" => "Sul", "resposta" => "c"],
        ["pergunta" => "Qual oceano banha o Brasil?", "a" => "Pacífico", "b" => "Atlântico", "c" => "Índico", "d" => "Ártico", "resposta" => "b"],
        ["pergunta" => "Quantos estados tem o Brasil?", "a" => "24", "b" => "25", "c" => "26", "d" => "27", "resposta" => "c"]
    ],
    "tecnologia" => [
        ["pergunta" => "O que significa HTML?", "a" => "HyperText Markup Language", "b" => "Home Tool Language", "c" => "High Tech Language", "d" => "Hyperlink Text Language", "resposta" => "a"],
        ["pergunta" => "CSS serve para que?", "a" => "Criar banco de dados", "b" => "Estilizar páginas web", "c" => "Programar jogos", "d" => "Editar videos", "resposta" => "b"],
        ["pergunta" => "O que é um navegador?", "a" => "Um programa para navegar na internet", "b" => "Um tipo de computador", "c" => "Uma linguagem", "d" => "Um sistema", "resposta" => "a"],
        ["pergunta" => "PHP é usado para que?", "a" => "Criar imagens", "b" => "Editar textos", "c" => "Programar servidor", "d" => "Fazer planilhas", "resposta" => "c"],
        ["pergunta" => "O que significa WWW?", "a" => "World Wide Web", "b" => "Web World Wide", "c" => "Website World", "d" => "World Web Wide", "resposta" => "a"]
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Educativo</title>
    <link rel="stylesheet" href="Treino7.css">
</head>
<body>
    <div class="main">
        <h1>Quiz Educativo</h1>

<?php

if(!isset($_POST['materia']) && !isset($_POST['finalizar'])) {

    echo "<div class='inicio'>";
    echo "<h2>Escolha uma matéria:</h2>";
    echo "<form method='post'>";
    echo "<select name='materia' required>";
    echo "<option value=''> Selecione um tema </option>";

    foreach($quiz as $tema => $perguntas) {
        echo "<option value='$tema'>" . $tema ."</option>";
    }
    
    echo "</select><br><br>";
    echo "<button type='submit'>Começar Quiz</button>";
    echo "</form>";
    echo "</div>";
    
} else if(isset($_POST['materia']) && !isset($_POST['finalizar'])) {
    
    $materia = $_POST['materia'];
    echo "<div class='quiz'>";
    echo "<h2>Quiz de " . $materia . "</h2>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='materiaEscolhida' value='$materia'>";
    
    for($i = 0; $i < 5; $i++) {
        echo "<div class='questao'>";
        echo "<h3>" . ($i+1) . ". " . $quiz[$materia][$i]['pergunta'] . "</h3>";
        echo "<div class='alternativas'>";
        echo "<label><input type='radio' name='q$i' value='a' required> a) " . $quiz[$materia][$i]['a'] . "</label><br>";
        echo "<label><input type='radio' name='q$i' value='b' required> b) " . $quiz[$materia][$i]['b'] . "</label><br>";
        echo "<label><input type='radio' name='q$i' value='c' required> c) " . $quiz[$materia][$i]['c'] . "</label><br>";
        echo "<label><input type='radio' name='q$i' value='d' required> d) " . $quiz[$materia][$i]['d'] . "</label>";
        echo "</div>";
        echo "</div>";
    }
    echo "<button type='submit' name='finalizar'>Enviar Respostas</button>";
    echo "</form>";
    echo "</div>";
    
} else if(isset($_POST['finalizar'])) {
    
    $materia = $_POST['materiaEscolhida'];
    $pontos = 0;     
    for($i = 0; $i < 5; $i++) {
        if(isset($_POST['q'.$i]) && $_POST['q'.$i] == $quiz[$materia][$i]['resposta']) {
            $pontos++;
        }
     }  
    $nota = ($pontos / 5) * 10;
    echo "<div class='fim'>";
    echo "<h2>Resultado do Quiz: " . $materia . "</h2>";
    echo "<div class='resultado'>";
    echo "<p>Você acertou $pontos de 5 perguntas.</p>";
    echo "<p class='nota'>Sua pontuação: " . number_format($nota, 1) . " de 10</p>";
    echo "</div>";
    
    if($pontos == 5) {
        echo "<p class='msg1 verde'>Parabéns! Você gabaritou!</p>";
        } else if($pontos >= 3) {
        echo "<p class='msg2 '> Bom resultado!</p>";
        } else {
        echo "<p class='msg3'>Estude mais e tente novamente!</p>";
  }
    
    echo "<div class='botoes'>";
    echo "<a href='" . $_SERVER['PHP_SELF'] ."'><button>Voltar ao Início</button></a>";
    echo "</div>";
    echo "</div>";
}
?>
    </div>
</body>
</html>