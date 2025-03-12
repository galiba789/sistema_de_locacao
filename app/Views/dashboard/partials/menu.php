<style>
    .dark-mode {
        background-color: #121212 !important;
        color: #ffffff !important;
    }

    .dark-mode .navbar {
        background-color: #1c1c1c !important;
    }

    .dark-mode .navbar .nav-link {
        color: #ffffff !important;
    }

    .nav-link {
        color: #ffffff !important;
    }

    .dark-mode .btn-secondary {
        background-color: #333 !important;
        border-color: #444 !important;
    }
    .dark-mode .search{
        color: #ffffff !important;
    }
    .dark-mode .table {
        color: #ffffff !important;
    }

    .dark-mode .date {
        color: #121212 !important;
    }

    .dark-mode .card {
        background-color: #121212 !important;
    }

    .dark-footer {
        background-color: #1c1c1c !important;
        color: #fff !important;
    }

    .dark-footer a {
        color: #ddd !important;
        
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('/home') ?>">
            <img src="<?= PL_BASE_DIST . "/images/logo-b.png" ?>" style="height: 50px;" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/home') ?>">
                        <i class="fas fa-poll"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/locacoes') ?>">
                        <i class="fa-solid fa-clipboard-list"></i> Locações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/calendario') ?>">
                        <i class="fa-solid fa-calendar-days"></i> Calendário
                    </a>
                </li>

                <!-- Dropdown Cadastros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="cadastrosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-folder"></i> Cadastros
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="cadastrosDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('/clientes') ?>"><i class="fa-solid fa-user"></i> Clientes</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/categorias') ?>"><i class="fa-solid fa-shop"></i> Categorias</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/produtos') ?>"><i class="fa-solid fa-cart-shopping"></i> Produtos</a></li>
                    </ul>
                </li>
            </ul>
        </div>


        <div class="d-flex gap-2">
            <span id="toggleDarkMode" class="btn btn-secondary">
                <i class="fa-solid fa-moon"></i>
            </span>

            <a href="<?= base_url('login/logout') ?>" class="btn btn-danger">
                <i class="fa-solid fa-sign-out-alt"></i>
            </a>
        </div>

    </div>

</nav>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleDarkModeBtn = document.getElementById("toggleDarkMode");
    const icon = toggleDarkModeBtn.querySelector("i");
    const body = document.body;
    const footer = document.querySelector("footer");
    const footerDiv = footer.querySelector(".p-3");

    // Verifica se o tema escuro está ativado no localStorage
    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
        footer.classList.add("dark-footer");
        footerDiv.classList.remove("bg-primary");
        footerDiv.classList.add("dark-mode");
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");
    }

    toggleDarkModeBtn.addEventListener("click", function () {
        body.classList.toggle("dark-mode");
        footer.classList.toggle("dark-footer");
        footerDiv.classList.toggle("dark-mode");
        footerDiv.classList.toggle("bg-primary");

        if (body.classList.contains("dark-mode")) {
            localStorage.setItem("darkMode", "enabled");
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        } else {
            localStorage.setItem("darkMode", "disabled");
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        }
    });
});
</script>
