<?php
// create_db.php
// Coloque este arquivo em: C:\xampp\htdocs\dashboard\web\create_db.php
// Acesse: http://localhost/dashboard/web/create_db.php

// Ajuste estes dados se necessário
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';    // se tem senha, coloque aqui
$dbName = 'jogo_digitacao';

try {
    // conecta ao MySQL sem selecionar DB (para criar o DB)
    $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // cria o banco com charset
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // conecta ao banco recém-criado
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // cria a tabela
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS textos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            conteudo TEXT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // textos a inserir (cada string pode ter aspas e quebras de linha sem problemas)
    $texts = [
"Eram explosões, incêndios em praças
E confusões, pessoas armadas
Você se foi
(Cadê você?)
Eu me preocupo
Vê se não morre
Toma cuidado
Mande noticias
Seja um sinal de fumaça
Foi assim, nem deu tempo pra pensar
Quando eu caí, os prédios em chamas
Eu vi você me olhando de longe
O sol se pôs
Seguiu você
Sobrou pra mim um buquê
No altar ao sol, em busca
Eu me preocupo
Vê se não morre
Toma cuidado
Mande noticias
Seja um sinal de fumaça
Vê se não morre
Toma cuidado
Mande noticias
Seja um sinal de fumaça
Está tão distante
Está tão distante
Sumiu na multidão
Levou meu coração
Está tão distante
Sumiu na multidão
Levou meu coração
Está tão distante
Vê se não morre
Toma cuidado
Mande notícias
Seja um sinal de fumaça",

"Nem toda nuvem chove
Nem toda sombra cobre
Um dia cê desce, no outro sobe

(Eu sei que um dia)
(Eu sei que um dia)
(Eu sei que um dia)

O Sol não te derrete, o ar não te sufoca
A grama não morde, o chão não desaba

(Eu sei que um dia)
(Eu sei que um dia)
(Eu sei que um dia)

Se você deixar
Rapunzel, solta sua trança, se espalhe
Esse quarto é pequeno pra você
Da torre a vista é linda
Mas aqui embaixo ela tem vida

Eu sei que um dia
Eu sei que um dia
Eu sei que um dia
Cê lança moda

Conto não define história da sua vida
(Eu sei que um dia)
(Eu sei que um dia)
Só você

Ou será que alguém te fez
(Duvidar, duvidar de)
Ou será que alguém te fez
Duvidar, duvidar de si

Rapunzel, solta sua trança, se espalhe
Esse quarto é pequeno pra você
Da torre a vista é linda
Mas aqui embaixo ela tem vida

Rapunzel, solta sua trança, se espalhe
Esse quarto é pequeno pra você
Da torre a vista é linda
Mas aqui embaixo ela tem vida

Eu sei que um dia
Eu sei que um dia
Eu sei que um dia
Cê lança moda",

"Me desculpe a minha ausência
Eu tô ocupado pedindo licença
Por favor não fique bravo
Prometo que eu vou arrumar um trabalho

Minha vida nunca esfria
De bar em bar o mundo gira
Trago comigo fumaça e farinha
Garrafa, moeda, melhores amigas

Seja no beco ou seja na praça
Os carros me sugam com a sua fumaça
Não sei se é abril, fevereiro ou março
No tempo eu esbarro, da vida disfarço

Asfalto de rua, tampa de bueiro
Sem uma cama e sem travesseiro
Durmo com medo e acordo cansado
Não tenho futuro, eu vivo o passado

Eu que escolhi ver o mundo em preto e branco, sem amor
Mas quando sonho coberto com meu manto, sinto dor
Eu que escolhi ver o mundo em preto e branco, sim senhor
Mas quando sonho coberto com meu manto, tanta cor

Me desculpe a minha ausência
Eu tô ocupado pedindo licença
Por favor não fique bravo
Prometo que eu vou arrumar um trabalho

Minha vida nunca esfria
De bar em bar o mundo gira
Trago comigo fumaça e farinha
Garrafa, moeda, melhores amigas

Eu que escolhi ver o mundo em preto e branco, sem amor
Mas quando sonho coberto com meu manto, sinto dor
Eu que escolhi ver o mundo em preto e branco, sim senhor
Mas quando sonho coberto com meu manto, quanta cor"
    ];

    // opcional: limpar tabela antes de inserir (descomente se quiser substituir)
    // $pdo->exec("TRUNCATE TABLE textos");

    // prepara e insere cada texto com segurança
    $insert = $pdo->prepare("INSERT INTO textos (conteudo) VALUES (:conteudo)");
    $countInserted = 0;
    foreach ($texts as $t) {
        // pode checar se já existe similar para evitar duplicatas, se desejar
        $insert->execute([':conteudo' => $t]);
        $countInserted++;
    }

    echo "Banco e tabela criados com sucesso. Textos inseridos: $countInserted";

} catch (PDOException $e) {
    // mostra erro legível (em ambiente de produção prefira logar)
    echo "Erro: " . $e->getMessage();
    exit;
}
