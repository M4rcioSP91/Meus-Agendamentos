<?php

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../controllers/GaleriaController.php";

$conexao = new conexao();

$pdo = $conexao->conectar();

$galeria = new GaleriaController($pdo);

$imagens = $galeria->listar();

?>


<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="h3">
                <i class="bi bi-images"></i>
                Galeria
            </h1>

        </div>


        <div>

            <input
                type="file"
                id="inputFotos"
                name="imagens[]"
                accept="image/jpeg,image/png,image/webp"
                multiple
                hidden
            >

            <button
                type="button"
                class="btn btn-primary-1"
                id="btnAdicionarFoto">

                <i class="bi bi-plus-lg"></i>
                Adicionar foto

            </button>


            <button
                type="button"
                class="btn btn-success ms-2"
                id="btnEnviarFotos"
                style="display: none;">

                <i class="bi bi-cloud-upload"></i>
                Enviar fotos

            </button>

        </div>

    </div>

<!-- ÁREA DA GALERIA COM SCROLL -->

    <div class="galeria-scroll">

        <!-- Pré-visualização -->
        <div id="previewFotos" class="row mb-4">
        </div>


        <!-- Imagens já salvas -->

        <div class="row">

            <?php foreach ($imagens as $imagem): ?>

                <div class="col-md-4 mb-4">

                    <div class="card">

                        <img
                            src="uploads/galeria/<?= htmlspecialchars($imagem['imagem']) ?>"
                            class="card-img-top"
                            style="height: 250px; object-fit: cover;"
                        >

                        <div class="card-body text-end">

                            <button
                                type="button"
                                class="btn btn-danger btnExcluirFoto"
                                data-id="<?= $imagem['id'] ?>">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>