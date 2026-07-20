<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Connexion</h3>

                        <?php if (session()->get('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->get('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->get('success') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('login/auth') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Numéro de téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required autofocus>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
