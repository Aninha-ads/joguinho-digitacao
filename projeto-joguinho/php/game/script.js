/* ---------------------------------------------------
   Geral
--------------------------------------------------- */

const boxOriginal = document.getElementById("texto");
const containerTexto = document.querySelector(".container-texto");
const btnJogar = document.getElementById("btn-jogar");
const menuDificuldade = document.getElementById("menu-dificuldade");

// variáveis globais
let textoOriginal = "";
let textoDigitado = "";
let movimentoIntervalId = null;
let velocidadeTexto = 1;
let gameEnded = false; // para evitar múltiplos alerts

// combos e placar
let comboAtual = 0;
const comboStep = 10;
let comboMultiplicador = 1;
let totalPontos = 0;
let totalPalavras = 0;
let difficultyMultiplicador = 1.0;
let dificuldadeSelecionada = "3";

// Mapa de multiplicador por dificuldade (tabela C)
const difficultyMap = {
    "1": 0.3, // Muito fácil
    "2": 0.6, // Fácil
    "3": 1.0, // Normal
    "4": 1.5, // Difícil
    "5": 2.0  // Hardcore
};

// anti-duplica palavras: guardamos índice final (fim) das palavras já contabilizadas
const palavrasRegistradas = new Set();

// ---------------- utils ----------------
function escapeHTML(s){
    return s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
}
function charParaHTML(c){
    if (c === "\n") return "<br>";
    if (c === " ") return "&nbsp;";
    return escapeHTML(c);
}

// --------------- HUD de combo ---------------
function atualizarComboHUD(pulse = true) {
    const hud = document.getElementById("combo-hud");
    const multDiv = document.getElementById("combo-mult");
    if (!hud || !multDiv) return;

    comboMultiplicador = 1 + Math.floor(comboAtual / comboStep);
    if (comboMultiplicador > 5) comboMultiplicador = 5;

    hud.style.opacity = comboAtual > 0 ? "1" : "0";
    multDiv.textContent = "x" + comboMultiplicador;

    if (pulse) {
        multDiv.classList.add("combo-pulse");
        setTimeout(() => multDiv.classList.remove("combo-pulse"), 220);
    }
}

// --------------- HUD Pontos/Palavras ---------------
function atualizarHUD() {
    // tenta localizar os spans dentro da HUD direita (se existir)
    const spans = document.querySelectorAll(".hud .placar span");
    if (spans.length >= 2) {
        spans[0].textContent = totalPontos;
        spans[1].textContent = totalPalavras;
    }
}

// --------------- Carregar texto do BD ---------------
function carregarTexto(){
    fetch("carregar_textos.php")
        .then(r => r.json())
        .then(data => {
            textoOriginal = (data.texto || "").replace(/\r\n/g, "\n").replace(/\r/g, "\n");
            textoDigitado = "";
            totalPontos = 0;
            totalPalavras = 0;
            comboAtual = 0;
            gameEnded = false;
            palavrasRegistradas.clear();
            atualizarComboHUD(false);
            atualizarHUD();
            atualizarRenderOriginal();
            console.log("Texto carregado. comprimento:", textoOriginal.length);
        })
        .catch(err => console.error("Erro ao carregar texto:", err));
}
carregarTexto();

// --------------- Renderizar texto original (span por span) ---------------
function atualizarRenderOriginal(){
    const max = textoOriginal.length;
    const parts = [];

    for (let i = 0; i < max; i++){
        const o = textoOriginal[i];
        const d = textoDigitado[i];

        if (o === "\n") {
            parts.push(`<span class="newline" data-i="${i}"><br></span>`);
            continue;
        }

        let classe = "";
        if (d != null) {
            classe = (d === o) ? "correto" : "errado";
        }

        parts.push(`<span data-i="${i}" class="${classe}">${charParaHTML(o)}</span>`);
    }

    boxOriginal.innerHTML = parts.join("");
}

// --------------- Combo functions ---------------
function registrarAcerto() {
    comboAtual++;
    atualizarComboHUD(true);
}
function registrarErro() {
    comboAtual = 0;
    atualizarComboHUD(false);
}
function recomputarCombo() {
    // recalcula o combo como o número de acertos consecutivos no final do textoDigitado
    let count = 0;
    for (let i = textoDigitado.length - 1; i >= 0; i--) {
        if (textoDigitado[i] === textoOriginal[i]) count++;
        else break;
    }
    comboAtual = count;
    atualizarComboHUD(false);
}

// --------------- Funções de palavra / pontuação ---------------
function indiceInicioPalavraPorFim(fimIdx) {
    // fimIdx é índice do último caractere da palavra (não boundary)
    for (let i = fimIdx; i >= 0; i--) {
        if (textoOriginal[i] === " " || textoOriginal[i] === "\n") return i + 1;
    }
    return 0;
}

function adicionarPontuacao(tamanho) {
    // garante que comboMultiplicador esteja atualizado
    comboMultiplicador = 1 + Math.floor(comboAtual / comboStep);
    if (comboMultiplicador > 5) comboMultiplicador = 5;

    const pontosGanhos = Math.round(tamanho * comboMultiplicador * difficultyMultiplicador);
    totalPontos += pontosGanhos;
    atualizarHUD();
}

function incrementarPalavras() {
    totalPalavras++;
    atualizarHUD();
}

// processa palavra quando o jogador alcança um limite (space, newline ou fim do texto)
// pos = textoDigitado.length (posição atual de escrita - next index)
function processarPalavraSeNecessario() {
    const pos = textoDigitado.length;
    if (pos === 0) return;
    if (pos > textoOriginal.length) return;

    const nextChar = textoOriginal[pos]; // pode ser ' ', '\n' ou undefined (fim)
    const isBoundary = (nextChar === ' ') || (nextChar === '\n') || (pos === textoOriginal.length);

    if (!isBoundary) return;

    // fim é o caractere anterior ao boundary
    const fim = pos - 1;
    const inicio = indiceInicioPalavraPorFim(fim);
    if (fim < inicio) return; // palavra vazia

    // anti-duplicação por índice final
    if (palavrasRegistradas.has(fim)) return;

    // verificar se todos os caracteres da palavra estavam corretos
    let todosCorretos = true;
    for (let i = inicio; i <= fim; i++) {
        if (textoDigitado[i] !== textoOriginal[i]) {
            todosCorretos = false;
            break;
        }
    }

    if (!todosCorretos) return;

    // registrar e pontuar
    palavrasRegistradas.add(fim);
    const tamanho = (fim - inicio + 1);
    adicionarPontuacao(tamanho);
    incrementarPalavras();
}

// --------------- Atualização única após cada modificação de texto ---------------
function atualizarAposDigitacao() {
    if (gameEnded) return; // não processar se jogo já terminou
    atualizarRenderOriginal();
    processarPalavraSeNecessario();
    checarGameOver();
    checarVitoria();
}

// --------------- Keydown handler (limpo) ---------------
document.addEventListener("keydown", (ev) => {
    if (!textoOriginal || gameEnded) return;

    const idxAtual = textoDigitado.length;

    // BACKSPACE
    if (ev.key === "Backspace") {
        ev.preventDefault();
        if (textoDigitado.length > 0) {
            textoDigitado = textoDigitado.slice(0, -1);
            recomputarCombo();
        }
        atualizarAposDigitacao();
        return;
    }

    // ENTER
    if (ev.key === "Enter") {
        ev.preventDefault();
        if (idxAtual < textoOriginal.length && textoOriginal[idxAtual] === "\n") {
            textoDigitado += "\n";
            registrarAcerto();
        }
        atualizarAposDigitacao();
        return;
    }

    // ignorar teclas especiais (Shift, Alt, Arrow, etc.)
    if (ev.key.length > 1) return;

    // bloquear digitação normal se original tem \n aqui (obriga Enter)
    if (textoOriginal[idxAtual] === "\n") return;

    // não aceitar além do original
    if (idxAtual >= textoOriginal.length) return;

    // caractere normal
    const tecla = ev.key;
    textoDigitado += tecla;

    if (tecla === textoOriginal[idxAtual]) registrarAcerto();
    else registrarErro();

    atualizarAposDigitacao();
});

// --------------- Game over / Vitória ---------------
function indiceInicioDaLinha(idx) {
    for (let i = idx; i >= 0; i--) {
        if (textoOriginal[i] === "\n") return i + 1;
    }
    return 0;
}
function indiceFimDaLinha(inicioIdx) {
    for (let i = inicioIdx; i < textoOriginal.length; i++) {
        if (textoOriginal[i] === "\n") return i - 1;
    }
    return textoOriginal.length - 1;
}

function checarGameOver() {
    if (gameEnded) return;

    const ultimoIdx = textoDigitado.length - 1;
    const inicioLinha = indiceInicioDaLinha(ultimoIdx);
    const fimLinha = indiceFimDaLinha(inicioLinha);
    const idxParaMedir = (fimLinha >= inicioLinha) ? fimLinha : inicioLinha;

    const spanMedir = boxOriginal.querySelector(`span[data-i="${idxParaMedir}"]`);
    if (!spanMedir) return;

    const rectSpan = spanMedir.getBoundingClientRect();
    const rectContainer = containerTexto.getBoundingClientRect();

    const limiteSuperior = rectContainer.top + 2;
    if (rectSpan.top <= limiteSuperior) {
        // game over
        gameEnded = true;
        if (movimentoIntervalId) { clearInterval(movimentoIntervalId); movimentoIntervalId = null; }
        setTimeout(() => mostrarGameOver("derrota"), 50);
    }
}

function checarVitoria() {
    if (gameEnded) return;
    if (textoDigitado.length !== textoOriginal.length) return;

    // processa última palavra caso necessário (idempotente)
    processarPalavraSeNecessario();

    // sinaliza vitória
    gameEnded = true;
    if (movimentoIntervalId) { clearInterval(movimentoIntervalId); movimentoIntervalId = null; }
    setTimeout(() => mostrarGameOver("vitoria"), 50);
}


// -------------- interface flutuante - gameover --------------
function mostrarGameOver(tipo) {
    gameEnded = true;

    if (movimentoIntervalId) {
        clearInterval(movimentoIntervalId);
        movimentoIntervalId = null;
    }

    document.getElementById("go-pontos").textContent = totalPontos;
    document.getElementById("go-palavras").textContent = totalPalavras;

    // mostra overlay
    const overlay = document.getElementById("gameover-overlay");
    if (overlay) overlay.style.display = "flex";

    // salva no servidor (opcionalmente tratar resposta)
    salvarPartida(tipo);
}

function reiniciarJogo() {
    const overlay = document.getElementById("gameover-overlay");
    if (overlay) overlay.style.display = "none";

    resetJogo();              // limpa variáveis e UI
    iniciarMovimentoTexto();  // reinicia movimento
}

//  --------------- salvamento --------------
function salvarPartida(resultado) {

    // Primeiro o console.log FORA do objeto do fetch
    console.log("Enviando JSON:", {
        resultado: resultado,
        pontos: totalPontos,
        palavras: totalPalavras,
        dificuldade: dificuldadeSelecionada
    });

    // Agora sim o fetch correto
    fetch("salvar_partida.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            resultado: resultado,
            pontos: totalPontos,
            palavras: totalPalavras,
            dificuldade: dificuldadeSelecionada
        })
    })
    .then(r => r.json())
    .then(res => {
        console.log("Resposta do servidor:", res);
    })
    .catch(err => console.error("Erro na requisição:", err));
}

// --------------- Movimento do texto e menu ---------------
const velocidades = {1:0.1,2:0.3,3:0.5,4:0.9,5:1.2};

if (btnJogar) {
    btnJogar.addEventListener("click", () => {
        const sel = document.querySelector("input[name='dificuldade']:checked");
        const selecionada = sel ? sel.value : "3";
        velocidadeTexto = velocidades[selecionada] || 0.5;
        difficultyMultiplicador = difficultyMap[selecionada] || 1.0;

        // --- ADICIONAR:
        dificuldadeSelecionada = selecionada;

        gameEnded = false;
        palavrasRegistradas.clear();
        textoDigitado = "";
        comboAtual = 0;
        comboMultiplicador = 1;
        totalPontos = 0;
        totalPalavras = 0;
        atualizarComboHUD(false);
        atualizarHUD();
        atualizarRenderOriginal();
        if (menuDificuldade) menuDificuldade.style.display = "none";
        iniciarMovimentoTexto();
    });
}

function iniciarMovimentoTexto(){
    const box = document.getElementById("texto");
    let posicao = containerTexto.clientHeight; // começa logo abaixo
    box.style.top = posicao + "px";

    if (movimentoIntervalId) { clearInterval(movimentoIntervalId); movimentoIntervalId = null; }

    movimentoIntervalId = setInterval(() => {
        posicao -= velocidadeTexto;
        box.style.top = posicao + "px";

        // proteção
        if (posicao <= -4000) {
            clearInterval(movimentoIntervalId);
            movimentoIntervalId = null;
            if (!gameEnded) {
            setTimeout(() => mostrarGameOver("tempo"), 50);
            }
        }

        checarGameOver();
    }, 16);
}

// --------------- Reset visual ---------------
function resetJogo() {
    if (movimentoIntervalId) { clearInterval(movimentoIntervalId); movimentoIntervalId = null; }
    textoDigitado = "";
    comboAtual = 0;
    totalPontos = 0;
    totalPalavras = 0;
    palavrasRegistradas.clear();
    gameEnded = false;
    atualizarComboHUD(false);
    atualizarHUD();
    atualizarRenderOriginal();
}
