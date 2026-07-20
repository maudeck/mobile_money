<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types d'Opérations</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Types d'Opérations</h2>
            <div>
                <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary me-2">Retour</a>
                <a href="<?= site_url('type-operation/create') ?>" class="btn btn-primary">Ajouter un type</a>
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
                            <th>Libellé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($types as $type): ?>
                            <tr>
                                <td><?= esc($type->id) ?></td>
                                <td><?= esc($type->libelle) ?></td>
                                <td class="action-buttons">
                                    <a href="<?= site_url('type-operation/tranches/' . $type->id) ?>" class="btn btn-sm btn-info">Tranches</a>
                                    <a href="<?= site_url('type-operation/edit/' . $type->id) ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="<?= site_url('type-operation/delete/' . $type->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?');">
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
