<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranches de Frais - <?= esc($type->libelle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tranches de Frais - <?= esc($type->libelle) ?></h2>
            <div>
                <a href="<?= site_url('type-operation') ?>" class="btn btn-secondary me-2">Retour</a>
                <a href="<?= site_url('type-operation/add-tranche/' . $type->id) ?>" class="btn btn-primary">Ajouter une tranche</a>
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
                            <th>Montant Min (Ar)</th>
                            <th>Montant Max (Ar)</th>
                            <th>Frais (Ar)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tranches as $tranche): ?>
                            <tr>
                                <td><?= number_format($tranche->montant_min, 0, ',', ' ') ?></td>
                                <td><?= number_format($tranche->montant_max, 0, ',', ' ') ?></td>
                                <td><?= number_format($tranche->frais, 0, ',', ' ') ?></td>
                                <td>
                                    <form action="<?= site_url('type-operation/delete-tranche/' . $type->id . '/' . $tranche->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tranches)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucune tranche définie</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
