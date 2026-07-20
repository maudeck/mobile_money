<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des Comptes Clients</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Situation des Comptes Clients</h2>
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Téléphone</th>
                            <th>Opérateur</th>
                            <th>Préfixe</th>
                            <th>Solde (Ar)</th>
                            <th>Date Création</th>
                            <th>Nb Opérations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= esc($client->id) ?></td>
                                <td><?= esc($client->telephone) ?></td>
                                <td><?= esc($client->operateur_nom ?? 'N/A') ?></td>
                                <td><?= esc($client->code_prefixe ?? 'N/A') ?></td>
                                <td class="number"><?= number_format($client->solde, 0, ',', ' ') ?></td>
                                <td><?= esc($client->date_creation) ?></td>
                                <td class="number"><?= number_format($client->nombre_operations, 0, ',', ' ') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun client trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
