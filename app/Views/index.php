<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Playcom</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= PL_BASE_DIST ?>/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= PL_BASE_DIST ?>/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= PL_BASE_DIST ?>/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <?php if (session()->getFlashdata('error')) { ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php } ?>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a class="h1">Painel</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Faça o login para entrar no painel</p>

                <form action="<?= base_url('login/') ?>" method="post">
                    <div class="input-group mb-3 content_normal_user">
                        <input type="text" name="email" id="email" name="email" placeholder="Email" class="form-control" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="input-group mb-3 content_admin" style="display: none;">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div> -->

                    <div class="input-group" style="width:100%; height:5px;">
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text" onclick="showPass()">
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- /.col -->
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= PL_BASE_DIST ?>/js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= PL_BASE_DIST ?>/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= PL_BASE_DIST ?>/js/adminlte.min.js"></script>

    <script>

        function showPass() {

            const passInput = document.querySelector('#password');

            if (passInput.type === "password") {
                passInput.type = 'text';
            } else {
                passInput.type = 'password';
            }
        }
    </script>
</body>

</html>