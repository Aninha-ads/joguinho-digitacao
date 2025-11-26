
//textos do jogo
function carregarTexto() {
    console.log("js funcionando perfeitamente");

   fetch("http://localhost/dashboard/joguinho-digitacao/projeto-joguinho/bd.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById("texto").innerText = data.texto;
    })
    .catch(error => {
        console.error("Erro ao carregar texto:", error);
    });
}

carregarTexto();

//menu flutuante

// velocidades de dificuldade
const velocidades = {
    1: 0.1,   // muito fácil
    2: 0.3,   // fácil
    3: 0.5,     // normal
    4: 0.9,   // difícil
    5: 1.2   // hardcore
};

let velocidadeTexto = 1; // padrão

document.getElementById("btn-jogar").addEventListener("click", () => {

    let selecionada = document.querySelector("input[name='dificuldade']:checked").value;

    velocidadeTexto = velocidades[selecionada];

    console.log("Dificuldade escolhida:", selecionada);
    console.log("Velocidade:", velocidadeTexto);

    // Fecha o menu
    document.getElementById("menu-dificuldade").style.display = "none";

    // Inicia o jogo
    iniciarMovimentoTexto();
});

function iniciarMovimentoTexto() {
    const box = document.getElementById("texto");
    let posicao = 460; 

    box.style.top = posicao + "px";

    const intervalo = setInterval(() => {
        posicao -= velocidadeTexto;  
        box.style.top = posicao + "px";

        // Se tocar a parte superior -> game over
        if (posicao <= 0) {
            clearInterval(intervalo);
            alert("O texto atingiu o topo! Você perdeu.");
        }

    }, 16); // ~60 FPS
}
