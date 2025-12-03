# joguinho-digitação




# Nome do Sistema

# --- TapType ---

## || Descrição

Este sistema é um jogo de digitação, com objetivo de desenvolver a habilidade de digitação do usuário rapidamente.

Fora utilizadas as linguagens javascript e majoritariamente php. Além das linguagens de marcação (html e css);

Breve descrição do sistema, explicando seu objetivo e funcionamento geral.


## || Funcionalidades

- ... Cadastro dos usuários
- ... Login e autenticação
- ... Logout
- ... Lista dos usuários
- ... Criação de tabelas no banco de dados

- ... Tela do jogo com pontuação
- ...  Carregamento de textos automático
- ... Seleção de dificuldade
- ... Salvamento das partidas
- ... Partidas anteriores

- ... Perfil do usuário
- ... Editar o perfil
- ... Alterar a senha
- ... Ranking de pontuações

##Processo de desenvolvimento 

 Processo do jogo
  1. Desenvolvimento inicial
   A primeira etapa do projeto foi criar a interface visual básica do jogo usando:
    HTML → estrutura do jogo
    CSS → estilização
   Foram criados:
    container para o texto do desafio
    placares de pontos e palavras
    layout responsivo
    tema visual neon/cyber (depois aprimorado)
   A estrutura base incluía:
    <div id="texto"></div>
    <div id="digitado"></div>
   Esses dois elementos seriam usados mais tarde para comparação de texto original com texto digitado pelo   usuário.

2. Implementação da lógica inicial com JavaScript
  Depois do HTML/CSS, começou a implementação da lógica em JavaScript:
   2.1. Captura do texto digitado
   A primeira versão apenas pegava eventos do teclado:
    document.addEventListener("keydown", handler);
   A entrada era adicionada manualmente dentro da variável usada para mostrar o texto digitado
   2.2. Busca no banco de dados
   Inicialmente o jogo não tinha PHP, então o texto ficava dentro do próprio JavaScript. Com o tempo o projeto evoluiu e foi criada uma tabela “textos” no MySQL. Crei um arquivo carregar_textos.php para retornar um texto aleatório e o frontend começou a buscar via fetch("carregar_textos.php"). Isso introduziu a primeira integração JS + PHP.
   
3. Animação das palavras
  A etapa seguinte foi criar a animação das palavras caindo ou se movendo (dependendo da versão). Foi implementado:
 movimentoIntervalId;
 deslocamento incremental do texto;
 controle de velocidade baseado na dificuldade;
 E houveram vários testes para evitar bug de movimentação.

4. Sistema de comparação do texto digitado
 Esta foi uma das partes mais importantes e complexas do projeto.
4.1. Primeira versão (texto sobreposto).
 A lógica inicial comparava caractere por caractere:
  if (digitado[i] == texto[i]) → cor roxa
  else → cor vermelha
 E exibia os textos sobrepostos.
 Mas isso causava um problema, o texto digitado ficava vermelho e, como estava em cima do original, ficava visualmente muito feio, pois as letras se misturavam e dificultava enxergar. Por isso foi tomada a decisão de mudar completamente o sistema visual.

5. Segunda versão da comparação — Solução final
 Passamos para uma abordagem mais elegante: O texto digitado fica invisível quando há erro e o texto  original muda para vermelho para indicar a posição errada. Funcionamento: o JS compara caractere    por caractere, quando encontra um erro:
   #texto recebe classe vermelha
   #digitado recebe opacity: 0
 Isso eliminou sobreposição feia.

6.Problemas com caracteres especiais (Enter, acentos, etc.)
 O ENTER não funcionava porque no código existia uma lógica para bloquear teclas especiais, como: F1, Shift, Alt, Ctrl, Enter, CapsLock. Isso estava dentro da função que tratava keydown, como if (teclaEspecial) return;
   Dessa forma, o Enter era ignorado, o que quebrou o jogo quando o texto aleatório continha quebras de linha
   Esse problemas foram resolvidos com a alteração da função keydown e ajustes feitos com ajuda da IA para corrigir a função de captura.

7. Integração com Login e Sessão
  Antes de salvar partidas, era necessário impedir que jogadores não logados jogassem, para isso o jogador precisava ser direcionado para login.php. Então, adicionei verificação no topo de game.php:
    session_start();
    if (!isset($_SESSION["user_id"])) {
       header("Location: ../php/login/login.php");
       exit;
    }
Dessa forma, o jogo passou a só iniciar se o usuário estiver logado, carregando corretamente nome e ID

8. Salvamento das partidas (JavaScript → PHP → MySQL)
 8.1. Envio dos dados
 Quando o jogo termina, o JS envia:
   {
     "resultado": "vitoria",
     "pontos": 51,
     "palavras": 6,
     "dificuldade": "3"
   }
via fetch(): fetch("salvar_partida.php", { method: "POST", body: JSON.stringify(...) })

8.2. Erros encontrados
 Foram vários:
  Erro 1 — JSON inválido
   PHP enviava HTML por causa de errors/warnings:
   Unexpected token '<'
   is not valid JSON
  Causado por require apontando para caminho errado
  warnings de PHP sendo impressos antes do JSON
  tabela com nome errado
Todos foram corrigidos.
  Erro 2 — Caminho errado
   require 'credentials.php' estava errado.
  A estrutura correta era:
game/
└── salvar_partida.php
php/
└── login/
    └── credentials.php
Solução:
  require __DIR__ . "/../php/login/credentials.php";
 Erro 4 — Partidas não eram salvas
  Motivos:
  INSERT tinha número errado de colunas
  JSON não chegava ao PHP
  tipo de dificuldade era number e não string
Tudo corrigido.

10. Estado final do salvamento
 Agora salvar_partida.php valida sessão, recebe JSON corretamente, salva em partidas e retorna:
{"status":"ok","id":###}

E o front-end loga a resposta no console.

## || Tecnologias Utilizadas
- PHP, JavaScript, HTML, CSS
- MySQL


## || Como Executar 
1. Clone este repositório  
2. Importe o banco executando o arquivo 'bdTables.php'  
3. Configure `credentials.php`  
4. Inicie o servidor local  
5. Acesse no navegador: `http://localhost/...`
6. execute o `index.php`
7. vá em login e clique em criar conta
8. após isso, volte ao menu e clique em jogar
9. Aproveite o jogo

## || Estrutura do Banco de Dados (fazer)
<img width="1838" height="574" alt="image" src="https://github.com/user-attachments/assets/657d45d5-7d86-436e-a61b-d6d3205cf078" />


## || Autores

- Ana Clara Jarbas Cotta Dos Santos:
-- ... pasta login: credentials, autheticate, dbTables, functions, logout, user_register, user_list. 
-- ... pasta de usuário: change_pass, edit_perfil, menu, perfil 

- Ana Clara Rozin:
- ... Mostrar ranking geral e semanal de todos os jogadores.
- ... Menu principal das ligas.
- ... criar uma liga com nome e palavra-chave.
- ... entrar em uma liga existente via ID + palavra-chave.
- ... Lista todas as ligas que o usuário participa.


- Mariana Zaleski E..
-- ... pasta game: carregar_textos, game, gerenciar_textos, partidas_anteriores, partidas, salvar_partida, script.js,
-- ... style.css, style_ligas.css.
  
