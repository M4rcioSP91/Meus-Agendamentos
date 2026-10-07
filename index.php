<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus agendamentos</title>
    <!--CSS-->
    <!--Resetar CSS-->
    <link rel="stylesheet" href="css/reset.css">
    <!--CSS BootStrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!--Icones BootStrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!--Meu CSS-->
    <link rel="stylesheet" href="css/style.css">
    <!--Scripts-->
    <script src="js/script.js" defer></script>
    <script src="js/scriptTema.js" defer></script>
    <!--Scripts BootStrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>

</head>
<body>
    
        <div id="wrapper">

        <!-- Menu lateral -->
        <nav id="nav">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Logo do site -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center carregar-pagina" href="pages/home.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="bi bi-calendar-date"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Meus agendamentos</div>
            </a>

            <!-- Divisão -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Galeria -->
            <li class="nav-item active">
                <a class="nav-link carregar-pagina" href="pages/home.php">
                    <i class="bi bi-house"></i>
                    <span>Home</span></a>
            </li>

            <!-- Divisão -->
            <hr class="sidebar-divider">


            <!-- Nav Item - Agendar -->
            <li class="nav-item">
                <a class="nav-link carregar-pagina" href="pages/agendamentos.php">
                    <i class="bi bi-calendar3-week"></i>
                    <span>Agendar</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <?php if (!empty($_SESSION['usuario_logado'])): ?>

                <li class="nav-item">
                    <a
                        class="nav-link carregar-pagina"
                        href="pages/meus_agendamentos.php"
                    >
                        <i class="bi bi-calendar-check"></i>
                        <span>Meus Agendamentos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link carregar-pagina"
                        href="pages/clientes.php"
                    >
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Clientes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link carregar-pagina"
                        href="pages/galeria.php"
                    >
                        <i class="bi bi-file-earmark-image"></i>
                        <span>Galeria</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link carregar-pagina"
                        href="pages/historico.php"
                    >
                        <i class="bi bi-clock-history"></i>
                        <span>Histórico</span>
                    </a>
                </li>

            <?php endif; ?>
            

            <!-- Divisão -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Alternador da barra lateral -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        </nav>
        <!-- Fin do menu lateral -->

        <!-- Container de conteudo-->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Menu superior -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Menu superior pesquisa 
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary-1" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>-->

                    <!-- Menu superior navegação -->

                    <?php if (!empty($_SESSION['usuario_logado'])): ?>

                        <ul class="navbar-nav ms-auto sidebarUser" id="accordionUser">

                            <!-- Divisão -->
                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Usuário -->
                            <li class="nav-item dropdown">

                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="userDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >

                                    <span class="me-2 d-none d-lg-inline text-gray-600 small">
                                        <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                                    </span>

                                    <img
                                        class="img-profile rounded-circle"
                                        src="img/undraw_profile.svg"
                                        alt="Usuário"
                                    >

                                </a>

                                <!-- Menu do usuário -->
                                <div
                                    class="dropdown-menu dropdown-menu-end shadow"
                                    aria-labelledby="userDropdown"
                                >

                                    <!-- Perfil -->
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        <i class="bi bi-person me-2"></i>
                                        Perfil
                                    </a>

                                    <!-- Configurações -->
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        <i class="bi bi-gear me-2"></i>
                                        Configurações
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <!-- Sair -->
                                    <a
                                        class="dropdown-item"
                                        href="controllers/LogoutController.php"
                                    >
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Sair
                                    </a>

                                </div>

                            </li>

                        </ul>

                    <?php else: ?>

                        <!-- Usuário não autenticado -->
                        <ul class="navbar-nav ms-auto">

                            <li class="nav-item">

                                <a
                                    class="nav-link"
                                    href="pages/login.php"
                                >
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Login
                                </a>

                            </li>

                        </ul>

                    <?php endif; ?>

                    <!-- troca de tema-->
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <div class="">
                        <button id="btnTema" class="btn">
                            <i class="bi bi-brilliance rotate-color"></i>
                        </button>
                        </div>

                    

                </nav>
                <!-- Fin do menu superior -->

                <!-- Início do conteúdo da página -->
                <div class="container-fluid" id="conteudo">
                    conteudo da pagina aqui!
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 2026</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>


</body>
</html>