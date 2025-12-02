<?php
session_start();

if (empty($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}
?>

!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Digitação</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="default">
    <div id="menu-dificuldade" class="menu-overlay">

        <div class="menu-box">
            <h2>Selecione a Dificuldade</h2>

            <div class="opcoes">
                <label><input type="radio" name="dificuldade" value="1" checked> Muito Fácil</label>
                <label><input type="radio" name="dificuldade" value="2"> Fácil</label>
                <label><input type="radio" name="dificuldade" value="3"> Normal</label>
                <label><input type="radio" name="dificuldade" value="4"> Difícil</label>
                <label><input type="radio" name="dificuldade" value="5"> Hardcore</label>
            </div>

            <button id="btn-jogar">JOGAR</button>
        </div>

    </div>

    <div class="hud">
        <div class="placar">PONTOS <br><span>0</span></div>
        <div class="placar">PALAVRAS <br><span>0</span></div>
    </div>

    <div id="combo-hud">
            <div id="combo-label">COMBO</div>
            <div id="combo-mult">x1</div>
        </div>

    <div class="container-texto">
        <div id="box">
            <div id="texto" class="texto-box"></div>
            <div id="digitado" class="texto-box digitado"></div>
        </div>
    </div>
    
    <div class="gameover-overlay" id="gameover-overlay">
        <div class="gameover-box">

            <h2>FIM DE JOGO</h2>

            <p class="go-score">Pontos: <span id="go-pontos">0</span></p>
            <p class="go-score">Palavras: <span id="go-palavras">0</span></p>
            
            <div class="go-buttons">
                <button onclick="reiniciarJogo()">Jogar novamente</button>
                <button onclick="window.location='historico.php'">Jogos anteriores</button>
                <button onclick="window.location='/php/usuario/ranking.php'">Ranking</button>
            </div>

        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>