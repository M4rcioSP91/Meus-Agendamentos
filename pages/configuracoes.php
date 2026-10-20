
<div class="container-fluid py-4">

    <h3 class="mb-4">Configurações</h3>

    <div class="accordion w-100" id="accordionConfiguracoes">

        <div class="accordion-item">

            <h2 class="accordion-header" id="tituloAlterarSenha">

                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseAlterarSenha"
                    aria-expanded="false"
                    aria-controls="collapseAlterarSenha">

                    <i class="bi bi-lock me-2"></i>
                    Alterar senha

                </button>

            </h2>

            <div
                id="collapseAlterarSenha"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionConfiguracoes">

                <div class="accordion-body">

                    <form id="formAlterarSenha">

                        <div id="mensagemAlterarSenha"></div>

                        <div class="mb-3">
                            <label for="senhaAtual" class="form-label">
                                Senha atual
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="senhaAtual"
                                name="senha_atual"
                                autocomplete="current-password"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="novaSenha" class="form-label">
                                Nova senha
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="novaSenha"
                                name="nova_senha"
                                autocomplete="new-password"
                                minlength="8"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="confirmarSenha" class="form-label">
                                Confirmar nova senha
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="confirmarSenha"
                                name="confirmar_senha"
                                autocomplete="new-password"
                                minlength="8"
                                required>
                        </div>

                        <div class="d-flex justify-content-end">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                Salvar alteração
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
