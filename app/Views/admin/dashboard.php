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
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
