// =========================================================
// BOTÃO EXPANDIR / RECOLHER MENU LATERAL
// =========================================================

const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('accordionSidebar');

if (sidebarToggle && sidebar) {

    sidebarToggle.addEventListener('click', function () {

        sidebar.classList.toggle('collapsed');

    });

}


// =========================================================
// FUNÇÃO PARA CARREGAR PÁGINAS
// =========================================================

function carregarPagina(pagina) {

    const conteudo = document.getElementById("conteudo");

    if (!conteudo) {
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

}

// =========================================================
// LINKS QUE DEVEM SER CARREGADOS COM FETCH
// =========================================================

document.addEventListener(
    "click",
    function (event) {

        const link =
            event.target.closest(".carregar-pagina");

        if (!link) {
            return;
        }


        event.preventDefault();


        const pagina =
            link.getAttribute("href");


        if (!pagina) {
            return;
        }


        carregarPagina(pagina);

    }
);

// =========================================================
// RECARREGA A PAGINA AGENDAR AO CLICAR EM CONFIRMAR
// =========================================================

document.addEventListener("submit", function (event) {

    const formulario = event.target.closest("#formAgendamento");

    if (!formulario) {
        return;
    }

    event.preventDefault();

    const dados = new FormData(formulario);

    fetch(formulario.action, {
        method: "POST",
        body: dados
    })
    .then(function (resposta) {

        if (!resposta.ok) {
            throw new Error("Erro ao realizar o agendamento.");
        }

        return resposta.text();
    })
    .then(function () {

        // Recarrega a própria página de agendamento
        carregarPagina("pages/agendamentos.php");

    })
    .catch(function (erro) {

        console.error(erro);

    });

});

// =========================================================
// CARREGAR HOME AO ABRIR O SITE
// =========================================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        carregarPagina(
            "pages/home.php"
        );

    }
);




// =========================================================
// AGENDAMENTO - SELECIONAR HORÁRIO
// =========================================================

document.addEventListener('click', function (event) {

    const botao = event.target.closest('#listaHorarios button');


    // Se não clicou em um botão de horário

    if (!botao) {
        return;
    }


    // Não permite selecionar horário desabilitado

    if (botao.disabled) {
        return;
    }


    // Pega o horário

    const hora = botao.dataset.hora;


    // Coloca o horário no input hidden

    const campoHora = document.getElementById('hora');

    if (campoHora) {

        campoHora.value = hora;

    }


    // Remove a seleção dos outros botões

    document.querySelectorAll('#listaHorarios button').forEach(function (btn) {

        if (!btn.disabled) {

            btn.classList.remove('btn-primary');

            btn.classList.add('btn-outline-primary');

        }

    });


    // Ativa o botão clicado

    botao.classList.remove('btn-outline-primary');

    botao.classList.add('btn-primary');

});


// =========================================================
// AGENDAMENTO - ALTERAR DATA
// =========================================================

document.addEventListener('change', function (event) {

    // Verifica se o campo alterado é a data

    if (event.target.id !== 'data') {
        return;
    }


    const data = event.target.value;


    const listaHorarios =
        document.getElementById('listaHorarios');


    const campoHora =
        document.getElementById('hora');


    // Verifica se os elementos existem

    if (!listaHorarios || !campoHora) {
        return;
    }


    // Limpa horário selecionado

    campoHora.value = '';


    // Mensagem enquanto carrega

    listaHorarios.innerHTML = `
        <div class="col-12">

            <div class="alert alert-info">
                Carregando horários...
            </div>

        </div>
    `;


    // =====================================================
    // BUSCA HORÁRIOS DISPONÍVEIS
    // =====================================================

    fetch(
        'controllers/AgendamentoController.php?buscar_horarios=1&data='
        + encodeURIComponent(data)
    )

    .then(function (response) {

        if (!response.ok) {

            throw new Error('Erro ao buscar horários.');

        }

        return response.json();

    })

    .then(function (dados) {

        listaHorarios.innerHTML = '';


        // Nenhum horário disponível

        if (dados.horarios.length === 0) {

            listaHorarios.innerHTML = `
                <div class="col-12">

                    <div class="alert alert-warning">
                        Não existem horários disponíveis para esta data.
                    </div>

                </div>
            `;

            return;
        }


        // Cria os botões

        dados.horarios.forEach(function (hora) {

            const coluna = document.createElement('div');

            coluna.className =
                'col-6 col-md-3 col-lg-2';


            const botao = document.createElement('button');

            botao.type = 'button';

            botao.className =
                'btn btn-outline-primary w-100';

            botao.dataset.hora = hora;

            botao.textContent = hora;


            coluna.appendChild(botao);

            listaHorarios.appendChild(coluna);

        });

    })

    .catch(function (erro) {

        console.error(erro);

        listaHorarios.innerHTML = `
            <div class="col-12">

                <div class="alert alert-danger">
                    Erro ao carregar os horários.
                </div>

            </div>
        `;

    });

});



// =========================================================
// MÁSCARA DE TELEFONE
// =========================================================

document.addEventListener("input", function (event) {

    // Verifica se é o campo telefone

    if (event.target.id !== "telefone") {
        return;
    }


    // Remove tudo que não for número

    let telefone = event.target.value.replace(/\D/g, "");


    // Limita a quantidade de números

    telefone = telefone.substring(0, 11);


    // Telefone com 11 números
    // (11) 99999-9999

    if (telefone.length > 10) {

        telefone = telefone.replace(
            /^(\d{2})(\d{5})(\d{4}).*/,
            "($1) $2-$3"
        );

    }

    // Telefone com 10 números
    // (11) 9999-9999

    else if (telefone.length > 6) {

        telefone = telefone.replace(
            /^(\d{2})(\d{4})(\d{0,4}).*/,
            "($1) $2-$3"
        );

    }

    // Apenas DDD + início do telefone

    else if (telefone.length > 2) {

        telefone = telefone.replace(
            /^(\d{2})(\d{0,5})/,
            "($1) $2"
        );

    }

    // Apenas DDD

    else if (telefone.length > 0) {

        telefone = telefone.replace(
            /^(\d{0,2})/,
            "($1"
        );

    }


    event.target.value = telefone;

});


//********************************** Galeria*********************************


// =========================================================
// BOTÃO ADICIONAR FOTO
// =========================================================

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

    const preview =
        document.getElementById("previewFotos");

    const btnEnviar =
        document.getElementById("btnEnviarFotos");


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

            const coluna =
                document.createElement("div");

            coluna.className =
                "col-md-4 mb-4";


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


// =========================================================
// BOTÃO ENVIAR FOTOS
// =========================================================

document.addEventListener("click", function (event) {

    const botao =
        event.target.closest("#btnEnviarFotos");


    if (!botao) {
        return;
    }


    const inputFotos =
        document.getElementById("inputFotos");


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

            alert(
                dados.mensagem ||
                "Erro ao enviar as fotos."
            );

            return;
        }


        alert("Fotos enviadas com sucesso!");


        // Limpa o input

        inputFotos.value = "";


        // Esconde o botão enviar

        botao.style.display = "none";


        // Limpa pré-visualização

        const preview =
            document.getElementById("previewFotos");


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

    const conteudo =
        document.getElementById("conteudo");


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

                throw new Error(
                    "Erro ao carregar galeria."
                );

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

    const botao =
        event.target.closest(".btnExcluirFoto");


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

            alert(
                dados.mensagem ||
                "Não foi possível excluir a imagem."
            );

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


//************************************************** Meus agendamentos************/

// =========================================================
// MEUS AGENDAMENTOS - NAVEGAR ENTRE OS DIAS
// =========================================================

document.addEventListener("click", function (event) {

    const botaoAnterior =
        event.target.closest("#btnDiaAnterior");

    const botaoProximo =
        event.target.closest("#btnProximoDia");


    // Se não clicou em nenhum dos botões
    if (!botaoAnterior && !botaoProximo) {
        return;
    }


    // Localiza o elemento que possui a data atual
    const agendaDia =
        document.getElementById("agendaDia");

    if (!agendaDia) {
        return;
    }


    // Pega a data atualmente exibida
    const dataAtual =
        agendaDia.dataset.data;

    if (!dataAtual) {
        return;
    }


    // Converte a data para objeto Date
    const data =
        new Date(dataAtual + "T00:00:00");


    // Define se vai avançar ou voltar
    if (botaoProximo) {

        data.setDate(
            data.getDate() + 1
        );

    }

    if (botaoAnterior) {

        data.setDate(
            data.getDate() - 1
        );

    }


    // Monta novamente a data YYYY-MM-DD

    const ano =
        data.getFullYear();

    const mes =
        String(data.getMonth() + 1)
            .padStart(2, "0");

    const dia =
        String(data.getDate())
            .padStart(2, "0");


    const novaData =
        `${ano}-${mes}-${dia}`;


    // Data de hoje

    const hoje =
        new Date();

    const anoHoje =
        hoje.getFullYear();

    const mesHoje =
        String(hoje.getMonth() + 1)
            .padStart(2, "0");

    const diaHoje =
        String(hoje.getDate())
            .padStart(2, "0");


    const dataHoje =
        `${anoHoje}-${mesHoje}-${diaHoje}`;


    // Impede voltar para uma data passada

    if (novaData < dataHoje) {
        return;
    }


    // Carrega novamente a página
    carregarPagina(
        "pages/meus_agendamentos.php?data="
        + encodeURIComponent(novaData)
    );

});