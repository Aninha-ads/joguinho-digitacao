# Relatório de Uso de Inteligência Artificial Generativa

Este documento registra todas as interações significativas com ferramentas de IA generativa (como Gemini, ChatGPT, Copilot, etc.) durante o desenvolvimento deste projeto. O objetivo é promover o uso ético e transparente da IA como ferramenta de apoio, e não como substituta para a compreensão dos conceitos fundamentais.

## Política de Uso
O uso de IA foi permitido para as seguintes finalidades:
- Geração de ideias e brainstorming de algoritmos.
- Explicação de conceitos complexos.
- Geração de código boilerplate (ex: estrutura de classes, leitura de arquivos).
- Sugestões de refatoração e otimização de código.
- Debugging e identificação de causas de erros.
- Geração de casos de teste.

É proibido submeter código gerado por IA sem compreendê-lo completamente e sem adaptá-lo ao projeto. Todo trecho de código influenciado pela IA deve ser referenciado neste log.

---

## Registro de Interações

*Copie e preencha o template abaixo para cada interação relevante.*

### Interação 1

- **Data:** 27/11/2025
- **Etapa do Projeto:** 1 - Arquivo de cadastro/login/
- **Ferramenta de IA Utilizada:** Gemini Advanced
- **Objetivo da Consulta:** ´Pedi para a IA verificar meu código de clogin, connection, db e cadastro que havia feito também estava com dificuldade em como validar a senha que o usuário colocava, para fazer a parte do login, assim pedi para a IA verificar o que havia feito de errado e formas de melhorar.

- **Prompt(s) Utilizado(s):**
  1. "Verifique o meu código que será enviado a seguir, veja se está correto e apresente exemplos de como verificar a senha que o usuário colocar e salvar no banco de dados?"
  2. ""

- **Resumo da Resposta da IA:**
  A IA apresentou alguns problemas graves no meu código, como mysqli_*, strings mal fechadas e também o uso do md5 que é inseguro. Então ela me apresentou algumas alternativas para arrumar os bugs e formas de verificar as senhas como:
  1) Usar Password_hash() e password_verify(), 2) Ussar password_verify() e verificação de rehash, e 3) Também apresentou a comparação de hash manual. Eu havia feito desta maneira anteriormente, contudo esse tipo é destualizado, pois usava o md5, são menos seguros. Então, optei pela primeira opção que parecia ser o método mais recomendado.

- **Análise e Aplicação:**
  A IA foi útil, pois me apresentou diversas formas de validar as senhas e pude alterar algumas partes do meu código. Além disso, o código que estava fazendo era mysql_*, que de acordo com a IA, ele estava extindo desde o PHP 7, também estava usando  omd5 para senha e estava faltando a proteção à SQL injection. Então a alternativa entregue pela IA foi de suma importância para deixar o código mais seguro e eficiente. 

- **Referência no Código:**
  A lógica inspirada por esta interação foi implementada no arquivo `login.php`, especificamente na parte em há a função `password_verify()` e `passwprd_hash()`, por volta da linha 85.

---

### Interação 2

- **Data:** 30/11/2025
- **Etapa do Projeto:** Jogo. 2 - implementação da lógica inicial com JavaScript
- **Ferramenta de IA Utilizada:** Chatgpt
- **Objetivo da Consulta:** Corrigir bug de captura de texto
- 
- **Prompt(s) Utilizado(s):
- Os caracteres especiais não funcionam, o enter não funciona e alguns caracteres digitados aparecem diferentes do texto original. Ajuste a lógica do keydown para capturar corretamente o que o jogador digita [parte do código com erro].
- 
- **Resumo da Resposta da IA:** A IA explicou que:
   ev.key.length > 1 estava bloqueando ENTER, acentos e caracteres especiais;
   o jogo comparava caracteres acentuados sem normalização Unicode;
   ENTER precisava ser aceito apenas quando o texto original tinha \n;
   teclas especiais eram tratadas de forma incorreta (Shift, Ctrl, Alt, arrows);
   Ela forneceu uma nova função de keydown completa, com suporte a Backspace, suporte a Enter corretamente, aceitação apenas de caracteres imprimíveis, comparação usando spans e prevenção de caracteres fora de posição.
  
- **Análise e Aplicação:**
-  A etapa de captura de teclado, a IA sugeriu uma reorganização completa do keydown para corrigir falhas no Enter, caracteres especiais e contagem de palavras. Porém, a solução inicial não encaixava totalmente no meu jogo. Por isso, eu mesma ajustei o código, reestruturando o tratamento de teclas, refinando a filtragem de caracteres especiais e corrigindo a interação entre digitação, comparação e renderização. Também adaptei a lógica para acertos, erros, novas linhas, pontuação e detecção de palavras. Com essas modificações, a captura de texto passou a funcionar corretamente e de forma integrada ao restante do sistema.
- **Referência no Código:** A lógica inspirada por esta interação foi implementada no arquivo `script.php`, especificamente na parte em há a função `keydown()` , por volta da linha 213.

---

### Interação 3

- **Data:** 30/11/2025
- **Etapa do Projeto:** Jogo. 2 - implementação da lógica inicial com JavaScript
- **Ferramenta de IA Utilizada:** Chatgpt
- **Objetivo da Consulta:** Corrigir bug de captura de texto
- 
- **Prompt(s) Utilizado(s):
- Os caracteres especiais não funcionam, o enter não funciona e alguns caracteres digitados aparecem diferentes do texto original. Ajuste a lógica do keydown para capturar corretamente o que o jogador digita [parte do código com erro].
- 
- **Resumo da Resposta da IA:** A IA explicou que:
   ev.key.length > 1 estava bloqueando ENTER, acentos e caracteres especiais;
   o jogo comparava caracteres acentuados sem normalização Unicode;
   ENTER precisava ser aceito apenas quando o texto original tinha \n;
   teclas especiais eram tratadas de forma incorreta (Shift, Ctrl, Alt, arrows);
   Ela forneceu uma nova função de keydown completa, com suporte a Backspace, suporte a Enter corretamente, aceitação apenas de caracteres imprimíveis, comparação usando spans e prevenção de caracteres fora de posição.
  
- **Análise e Aplicação:**
-  A etapa de captura de teclado, a IA sugeriu uma reorganização completa do keydown para corrigir falhas no Enter, caracteres especiais e contagem de palavras. Porém, a solução inicial não encaixava totalmente no meu jogo. Por isso, eu mesma ajustei o código, reestruturando o tratamento de teclas, refinando a filtragem de caracteres especiais e corrigindo a interação entre digitação, comparação e renderização. Também adaptei a lógica para acertos, erros, novas linhas, pontuação e detecção de palavras. Com essas modificações, a captura de texto passou a funcionar corretamente e de forma integrada ao restante do sistema.
- **Referência no Código:** A lógica inspirada por esta interação foi implementada no arquivo `script.php`, especificamente na parte em há a função `keydown()` , por volta da linha 213.

---
