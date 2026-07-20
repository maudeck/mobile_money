<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mobile Money</a>
            <div class="ms-auto">
                <a href="<?= site_url('logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Bonjour choisis ton operateur</h2>
                        <p class="text-muted">Bienvenue sur le tableau de bord administrateur.</p>
                        <div class="mt-4">
                            <span class="badge bg-success">Rôle : Admin</span>
                        </div>
                        <div class="mt-3">
                            <a href="<?= site_url('operateur') ?>" class="btn btn-primary">Gérer les opérateurs</a>
                            <a href="<?= site_url('type-operation') ?>" class="btn btn-success ms-2">Types d'opérations</a>
                            <a href="<?= site_url('admin/gains') ?>" class="btn btn-info ms-2">Gains par frais</a>
                            <a href="<?= site_url('admin/clients') ?>" class="btn btn-warning ms-2">Comptes clients</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
