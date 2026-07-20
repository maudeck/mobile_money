<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solde Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php /** @var float|int $solde */ ?>
<?= $this->include('client/template/header') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title mb-4">Mon Solde</h2>
                    <p class="display-4 text-primary"><?= number_format($solde, 0, ',', ' ') ?> Ar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('client/template/footer') ?>
</body>
</html>
