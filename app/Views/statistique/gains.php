<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des Gains par Frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Situation des Gains par Frais</h2>
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Type d'Opération</th>
                            <th>Nombre d'Opérations</th>
                            <th>Total Frais (Ar)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gains as $gain): ?>
                            <tr>
                                <td><?= esc($gain->libelle) ?></td>
                                <td><?= number_format($gain->nombre_operations, 0, ',', ' ') ?></td>
                                <td><?= number_format($gain->total_frais, 0, ',', ' ') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
