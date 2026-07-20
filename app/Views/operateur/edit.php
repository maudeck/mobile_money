<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Opérateur</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Modifier un Opérateur</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($validation)): ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors() ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('operateur/update/' . $operateur->id) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="code_prefixe" class="form-label">Code Préfixe</label>
                                <input type="text" class="form-control" id="code_prefixe" name="code_prefixe" value="<?= esc($operateur->code_prefixe) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="operateur_nom" class="form-label">Nom Opérateur</label>
                                <input type="text" class="form-control" id="operateur_nom" name="operateur_nom" value="<?= esc($operateur->operateur_nom) ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="<?= site_url('operateur') ?>" class="btn btn-secondary">Retour</a>
                                <button type="submit" class="btn btn-warning">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
