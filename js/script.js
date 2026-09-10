// BOTÃO EXPANDIR / RECOLHER MENU LATERAL

const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('accordionSidebar');

if (sidebarToggle && sidebar) {

    sidebarToggle.addEventListener('click', function () {

        sidebar.classList.toggle('collapsed');

    });

}

// CARREGAR CONTEÚDO DAS PÁGINAS

document.addEventListener("DOMContentLoaded", function () {

    const links = document.querySelectorAll(".carregar-pagina");
    const conteudo = document.getElementById("conteudo");


    links.forEach(function (link) {

        link.addEventListener("click", function (event) {

            // Impede o navegador de abrir outra página
            event.preventDefault();

            // Pega o endereço definido no href
            const pagina = this.getAttribute("href");

            // Verifica se existe uma página definida
            if (!pagina) {
                return;
            }

            // Mostra mensagem enquanto carrega
            conteudo.innerHTML = `
                <div class="text-center mt-5">

                    <div class="spinner-border" role="status">
                    </div>

                    <p class="mt-2">
                        Carregando...
                    </p>

                </div>
            `;


            // Carrega a página
            fetch(pagina)

                .then(function (resposta) {

                    if (!resposta.ok) {
                        throw new Error("Erro ao carregar página.");
                    }

                    return resposta.text();

                })

                .then(function (html) {

                    // Coloca o conteúdo dentro da div
                    conteudo.innerHTML = html;

                })

                .catch(function (erro) {

                    console.error(erro);

                    conteudo.innerHTML = `
                        <div class="alert alert-danger mt-3">
                            Erro ao carregar a página.
                        </div>
                    `;

                });

        });

    });

});

// BOTÃO ADICIONAR FOTO

document.addEventListener("click", function (event) {

    // Verifica se clicou no botão
    const botao = event.target.closest("#btnAdicionarFoto");

    if (!botao) {
        return;
    }

    // Procura o input de arquivos
    const inputFotos = document.getElementById("inputFotos");

    if (inputFotos) {

        // Abre a janela para selecionar arquivos
        inputFotos.click();

    }

});


// ======================================================
// PRÉ-VISUALIZAÇÃO DAS FOTOS
// ======================================================

document.addEventListener("change", function (event) {

    // Verifica se é o input das fotos
    if (event.target.id !== "inputFotos") {
        return;
    }

    const arquivos = event.target.files;

    const preview = document.getElementById("previewFotos");

    const btnEnviar = document.getElementById("btnEnviarFotos");


    // Verifica se foram selecionadas fotos

    if (arquivos.length === 0) {

        preview.innerHTML = "";

        btnEnviar.style.display = "none";

        return;
    }


    // Mostra o botão Enviar fotos

    btnEnviar.style.display = "inline-block";


    // Limpa a pré-visualização anterior

    preview.innerHTML = "";


    // Percorre os arquivos selecionados

    Array.from(arquivos).forEach(function (arquivo) {

        // Cria o leitor

        const leitor = new FileReader();


        leitor.onload = function (e) {

            // Cria a coluna

            const coluna = document.createElement("div");

            coluna.className = "col-md-4 mb-4";


            // Cria o card

            coluna.innerHTML = `

                <div class="card shadow">

                    <img
                        src="${e.target.result}"
                        class="card-img-top"
                        style="height: 250px; object-fit: cover;"
                    >

                </div>

            `;


            // Adiciona na tela

            preview.appendChild(coluna);

        };


        // Lê a imagem

        leitor.readAsDataURL(arquivo);

    });

});

// BOTÃO ENVIAR FOTOS

document.addEventListener("click", function (event) {

    const botao = event.target.closest("#btnEnviarFotos");

    if (!botao) {
        return;
    }

    const inputFotos = document.getElementById("inputFotos");

    if (!inputFotos || inputFotos.files.length === 0) {

        alert("Selecione pelo menos uma foto.");

        return;
    }


    // Cria o FormData

    const formData = new FormData();


    // Adiciona todas as fotos

    Array.from(inputFotos.files).forEach(function (arquivo) {

        formData.append("imagens[]", arquivo);

    });


    // Desabilita botão

    botao.disabled = true;

    botao.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Enviando...
    `;


    // Envia para o PHP

    fetch("pages/galeria_upload.php", {

        method: "POST",

        body: formData

    })

    .then(function (resposta) {

        if (!resposta.ok) {

            throw new Error("Erro no servidor.");

        }

        return resposta.json();

    })

    .then(function (dados) {

        console.log("Resposta:", dados);


        if (!dados.sucesso) {

            alert(dados.mensagem || "Erro ao enviar as fotos.");

            return;
        }


        alert("Fotos enviadas com sucesso!");


        // Limpa o input

        inputFotos.value = "";


        // Esconde o botão enviar

        botao.style.display = "none";


        // Limpa pré-visualização

        const preview = document.getElementById("previewFotos");

        if (preview) {

            preview.innerHTML = "";

        }


        // Recarrega a galeria

        carregarGaleria();

    })

    .catch(function (erro) {

        console.error(erro);

        alert("Erro ao enviar as fotos.");

    })

    .finally(function () {

        botao.disabled = false;

        botao.innerHTML = `
            <i class="bi bi-cloud-upload"></i>
            Enviar fotos
        `;

    });

});

// ======================================================
// RECARREGAR GALERIA
// ======================================================

function carregarGaleria() {

    const conteudo = document.getElementById("conteudo");

    conteudo.innerHTML = `
        <div class="text-center mt-5">

            <div class="spinner-border" role="status"></div>

            <p class="mt-2">
                Atualizando galeria...
            </p>

        </div>
    `;


    fetch("pages/galeria.php")

        .then(function (resposta) {

            if (!resposta.ok) {

                throw new Error("Erro ao carregar galeria.");

            }

            return resposta.text();

        })

        .then(function (html) {

            conteudo.innerHTML = html;

        })

        .catch(function (erro) {

            console.error(erro);

            conteudo.innerHTML = `
                <div class="alert alert-danger">
                    Erro ao atualizar a galeria.
                </div>
            `;

        });

}

// ======================================================
// EXCLUIR FOTO DA GALERIA
// ======================================================

document.addEventListener("click", function (event) {

    const botao = event.target.closest(".btnExcluirFoto");

    if (!botao) {
        return;
    }


    // Pega o ID da imagem

    const id = botao.dataset.id;


    // Confirma exclusão

    const confirmar = confirm(
        "Tem certeza que deseja excluir esta imagem?"
    );


    if (!confirmar) {
        return;
    }


    // Cria o FormData

    const formData = new FormData();

    formData.append("id", id);


    // Desabilita o botão

    botao.disabled = true;

    botao.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Excluindo...
    `;


    // Envia para o PHP

    fetch("pages/galeria_excluir.php", {

        method: "POST",

        body: formData

    })

    .then(function (resposta) {

        if (!resposta.ok) {

            throw new Error("Erro no servidor.");

        }

        return resposta.json();

    })

    .then(function (dados) {

        console.log("Resposta:", dados);


        if (!dados.sucesso) {

            alert(dados.mensagem || "Não foi possível excluir a imagem.");

            return;
        }


        alert("Imagem excluída com sucesso!");


        // Atualiza a galeria

        carregarGaleria();

    })

    .catch(function (erro) {

        console.error(erro);

        alert("Erro ao excluir a imagem.");

    })

    .finally(function () {

        botao.disabled = false;

        botao.innerHTML = `
            <i class="bi bi-trash"></i>
            Excluir
        `;

    });

});