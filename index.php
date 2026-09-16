<!DOCTYPE html>
<html lang="en">
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

</head>
<body>
    
        <div id="wrapper">

        <!-- Menu lateral -->
        <nav>
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
            <li class="nav-item">
                <a class="nav-link carregar-pagina" href="pages/meus_agendamentos.php">
                    <i class="bi bi-card-checklist"></i>
                    <span>Meus agendamentos</span></a>
            </li>

            <!-- Divisão -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link carregar-pagina" href="pages/clientes.php">
                    <i class="bi bi-person"></i>
                    <span>Clientes</span></a>
            </li>

            <!-- Divisão -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link carregar-pagina" href="pages/galeria.php">
                    <i class="bi bi-image"></i>
                    <span>Galeria</span></a>
            </li>

             <!-- Divisão -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link carregar-pagina" href="pages/historico.php">
                    <i class="bi bi-clock-history"></i>
                    <span>Histórico</span></a>
            </li>
            

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

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Usuario
                                </span>

                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Menu suspenso - Informações do usuário -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configurações
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>

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