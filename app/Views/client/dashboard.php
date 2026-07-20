<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?= $this->include('client/template/header') ?>

<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Bonjour client</h2>
                        <p class="text-muted">Bienvenue sur votre espace client.</p>
                        <div class="mt-4">
                            <span class="badge bg-primary">Rôle : Client</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<?= $this->include('client/template/footer') ?>
</body>
</html>
