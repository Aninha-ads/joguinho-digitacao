<?php
require 'credentials.php';
$conn = mysqli_connect($servername, $username, $db_password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Conectado ao MySQL!<br>";

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    echo "Banco '$dbname' criando ou já existente. <br>";
} else {
    echo "Erro ao criar banco: " . mysqli_error($conn);
}

mysqli_select_db($conn, $dbname);

$sql = "CREATE TABLE IF NOT EXISTS $table_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login_at DATETIME NULL,
    last_logout_at DATETIME NULL
)";

if(mysqli_query($conn,$sql)) {
    echo "Tabela '$table_users' criada com sucesso!<br>";
} else {
    echo "Erro criando tabelas: " . mysqli_error($conn);
}

//tabela de textos do jogo
$sql_textos = "CREATE TABLE IF NOT EXISTS textos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_textos)) {
    echo "Tabela 'textos' criada.<br>";
} else {
    echo "Erro criando tabela 'textos': " . mysqli_error($conn) . "<br>";
}

// tabela de partidas
$sql_partidas = "CREATE TABLE IF NOT EXISTS partidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    user_name VARCHAR(150) DEFAULT 'anonimo',

    pontos INT NOT NULL DEFAULT 0,
    palavras INT NOT NULL DEFAULT 0,
    dificuldade VARCHAR(20) DEFAULT NULL,
    resultado VARCHAR(20) DEFAULT NULL,
    data_jogo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql_partidas)) {
    echo "Tabela 'partidas' criada.<br>";
} else {
    echo "Erro criando tabela 'partidas': " . mysqli_error($conn) . "<br>";
}

/* parte das ligas */

$sql_ligas = "CREATE TABLE IF NOT EXISTS ligas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    palavra_chave VARCHAR(150) NOT NULL,
    criador_id INT UNSIGNED NOT NULL,
    criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (criador_id) REFERENCES $table_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_ligas)) {
    echo "Tabela 'ligas' criada.<br>";
} else {
   echo "Erro criando tabela 'ligas': " . mysqli_error($conn) . "<br>";
}


$sql_ligasUsuario = "CREATE TABLE IF NOT EXISTS usuarios_ligas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    liga_id INT UNSIGNED NOT NULL,
    inscrito_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES $table_users(id),
    FOREIGN KEY (liga_id) REFERENCES ligas(id),
    UNIQUE (user_id, liga_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_ligasUsuario)) {
    echo "Tabela 'usuarios_ligas' criada.<br>";
} else {
    echo "Erro criando tabela 'usuarios_ligas': " . mysqli_error($conn) . "<br>";
}


// Insert de textos ----------------------------------------------------------------
$text_count = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM textos");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $text_count = isset($row['c']) ? (int)$row['c'] : 0;
    mysqli_free_result($result);
} else {
    // Se der erro na query, opcionalmente logue para debug mas não interrompa
    error_log("Erro ao contar textos: " . mysqli_error($conn));
    $text_count = 0; // assume vazia no caso de erro
}

if ($text_count == 0) {

    $textos_para_inserir = [];

    // Texto 1: Princesa(scatlove)
    $textos_para_inserir[] = "Silêncio, me grita\nMe salva desse espelho\nReflete mentiras\nMe diz que não estou presa aqui\nTorre desse castelo\nEu como a torta da maçã pra não fazer desfeita\nGarfos pro lado e me finjo satisfeita\nAprendi bem cedo a ser uma linda princesa na mesa\nCumprindo ordens de etiqueta\nEngulo os sapos\nFinjo estar com fome\nA culpa, sempre minha\nSou eu que exagero\nMesmo querendo me soltar\nO mundo vai negar, dizendo que eu sou a bruxa\nSei que não é faz de conta , fogueira tá no sangue\nEu como a torta da maçã pra não fazer desfeita\nGarfos pro lado e me finjo satisfeita\nAprendi bem cedo a ser uma linda princesa na mesa\nCumprindo ordens de etiqueta\nEu como a torta da maçã pra não fazer desfeita\nSeguindo regras de etiqueta\nTanto veneno que a gente deixa ecoar por dentro\nVem, destrói e segue o vento\nEu como a torta de maçã pra não fazer desfeita\nGarfos pro lado e eu me finjo e eu me finjo\nAprendi bem cedo a ser uma linda princesa na mesa\nCumprindo ordens de etiqueta\nEu como a torta de maçã pra não fazer desfeita\nSeguindo regras de etiqueta,";

    // Texto 2: Vizinhos de porta (Scatlove)
    $textos_para_inserir[] = "Sopra à vontade\nJá concretei meus sonhos\nPra ninguém derrubar\nTroquei palhas por alvenaria\nEspero um dia, lobos e porcos\nVizinhos de porta\nEnquanto isso a gente tenta\nSe enverga, sem quebrar\nPor que a gente esquece da força?\nSe deixa boicotar\nA mente às vezes tem umas ideias\nSó vem pra atrapalhar\nA mesma mão que enxuga o rosto\nPode erguer um lar\nTijolo por tijolo\nPra lobo nenhum soprar\nTenta à vontade\nA vida é uma arquiteta\nSabe o que faz\nTroquei palhas por alvenaria\nEspero um dia, lobos e porcos\nVizinhos de porta\nEnquanto isso a gente tenta\nSe enverga, sem quebrar\nPor que a gente esquece a força?\nSe deixa boicotar\nA mente às vezes tem umas ideias\nSó vem pra atrapalhar\nA mesma mão que enxuga o rosto (hey)\nPode erguer um lar\nTijolo por tijolo\nPra lobo nenhum soprar\nEnquanto isso a gente tenta\nSe enverga ah ah\nEnquanto isso a gente tenta\nSe enverga ah ah\nEnquanto isso a gente tenta\nSe enverga ah ah\nEnquanto isso a gente tenta\nSe enverga ah ah\nPor que a gente esquece a força?\nSe deixa boicotar\nA mente às vezes tem umas ideias\nSó vem pra atrapalhar\nA mesma mão que enxuga o rosto\nPode erguer um lar\nTijolo por tijolo\nPra lobo nenhum soprar\nTijolo por tijolo\nPra lobo nenhum so...,";

    // Texto 3: Pra você gostar de mim (Scatlove)
    $textos_para_inserir[] = "Então se solta\nO acaso foi certeiro e o ponteiro foi dar meia volta\nMas não me solta\nMelhor do que cair em si é se amar de volta\nE melhor que ter razão é escrever um bom refrão\nA depender da situação porque\nQuando você tá perto\nEu não sei ser discreto\nPeito aberto, eu começo a cantar aquele\nParapaparapa-parapa-papaparara \nParapaparape-parapa-papaparara \nParapaparape-parape-papaparara\nEntão se solta\nO acaso foi certeiro e o ponteiro foi dar meia volta\nMas não me solta\nMelhor do que cair em si é se amar de volta\nE melhor que ter razão é escrever um bom refrão\nA depender da situação porque\nQuando você tá perto\nEu não sei ser discreto\nPeito aberto, eu começo a cantar aquele\nParapaparapa-parapa-papaparara\nParapaparapa-parapa-papaparara\nParapaparapa-parapa-papaparara\nSe você quiser eu posso até continuar essa canção que eu fiz\nSó por fazer você gostar de mim\nAssim enfim\nSe você quiser eu posso até continuar essa canção que eu fiz\nSó por fazer você gostar de mim\nAssim e fim,";

    // Texto 4: Wolf in Sheep's Clothing (Set It Off)
    $textos_para_inserir[] = "It's good to be back\nThis is still about you\nBeware, beware, be skeptical\nOf their smiles, their smiles of plated gold\nDeceit so natural\nBut a wolf in sheep's clothing is more than a warning\nBaa baa, black sheep, have you any soul?\nNo, sir, by the way, what the hell are morals?\nJack be nimble, Jack be quick\nJill's a little whore, and her alibis are turning tricks\nSo could you\nTell me how you're sleeping easy?\nHow you're only thinking of yourself?\nShow me how you justify\nTelling all your lies like second nature\nListen, mark my words, one day (one day)\nYou will pay, you will pay\nKarma's gonna come collect your debt\nAware, aware, you stalk your prey\nWith criminal mentality\nYou sink your teeth into people you depend on\nInfecting everyone, you're quite the problem\nFee-fi-fo-fum, better run and hide\nI smell the blood of a petty little coward\nJack be lethal, oh, Jack be slick\nJill will leave you lonely, dying in a filthy ditch\nSo could you\nTell me how you're sleeping easy?\nHow you're only thinking of yourself?\nShow me how you justify\nTelling all your lies like second nature\nListen, mark my words, one day (one day)\nYou will pay, you will pay\nKarma's gonna come collect your debt\nMaybe you'll change\nAbandon all your wicked ways\nMake amends and start anew again\nMaybe you'll see\nAll the wrongs you did to me\nAnd start all over, start all, oh my God\nWho am I kidding?\nNow, let's not get overzealous here\nYou've always been a huge piece of shit\nIf I could kill you, I would, but it's frowned upon in all 50 states\nHaving said that, burn in Hell!\nSee you in Hell\nBurn in Hell, motherfucker\nOh, oh, oh\nSo tell me how you're sleeping easy?\nHow you're only thinking of yourself?\nOh, show me how you justify\nTelling all your lies like second nature\nListen, mark my words, one day (one day)\nYou will pay, you will pay\nKarma's gonna come collect your debt\nCome collect your debt\nKarma's gonna come collect your debt";


    // Execução
    $sql_insert = "INSERT INTO textos (conteudo) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql_insert);

    if ($stmt) {
        $successful_inserts = 0;
        foreach ($textos_para_inserir as $conteudo) {
            mysqli_stmt_bind_param($stmt, "s", $conteudo);
            if (mysqli_stmt_execute($stmt)) {
                $successful_inserts++;
            } else {
                echo "Erro ao inserir texto: " . mysqli_error($conn) . "<br>";
            }
        }
        mysqli_stmt_close($stmt);
        echo "$successful_inserts textos iniciais inseridos com sucesso!<br>";
    } else {
        echo "Erro na preparação do statement (INSERT): " . mysqli_error($conn) . "<br>";
    }

} else {
    echo " A tabela 'textos' já contém dados. Nenhuma inserção foi feita.<br>";
}

mysqli_close($conn);

echo "<br><b>Tudo pronto! Tabelas criadas com sucesso (mysqli)</br>";


?>