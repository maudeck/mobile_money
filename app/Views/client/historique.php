<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php /** @var array $historique */ ?>
<?= $this->include('client/template/header') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title mb-4">Historique des operations</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Emetteur</th>
                                <th>Destinataire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($historique)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune operation trouvee</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($historique as $op): ?>
                                    <tr>
                                        <td><?= esc($op->date_operation) ?></td>
                                        <td><?= esc($op->type_operation) ?></td>
                                        <td><?= number_format($op->montant, 0, ',', ' ') ?> Ar</td>
                                        <td><?= number_format($op->frais_applique, 0, ',', ' ') ?> Ar</td>
                                        <td><?= esc($op->emetteur_telephone) ?></td>
                                        <td><?= $op->destinataire_telephone ? esc($op->destinataire_telephone) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('client/template/footer') ?>
</body>
</html>
