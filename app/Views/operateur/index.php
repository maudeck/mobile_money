<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Opérateurs</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Opérateurs</h2>
            <div>
                <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary me-2">Retour</a>
                <a href="<?= site_url('operateur/create') ?>" class="btn btn-primary">Ajouter un opérateur</a>
            </div>
        </div>

        <?php if (session()->get('success')): ?>
            <div class="alert alert-success">
                <?= session()->get('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="alert alert-danger">
                <?= session()->get('error') ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code Préfixe</th>
                            <th>Nom Opérateur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operateurs as $operateur): ?>
                            <tr>
                                <td><?= esc($operateur->id) ?></td>
                                <td><?= esc($operateur->code_prefixe) ?></td>
                                <td><?= esc($operateur->operateur_nom) ?></td>
                                <td class="action-buttons">
                                    <a href="<?= site_url('operateur/edit/' . $operateur->id) ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="<?= site_url('operateur/delete/' . $operateur->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
