<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mobile Money</a>
            <div class="ms-auto">
                <a href="<?= site_url('logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Tableau de Bord</h2>
                    <span class="badge bg-success fs-6">Administrateur</span>
                </div>
                
                <p class="text-muted mb-4">Bienvenue sur votre espace d'administration. Gérez les opérateurs, les types d'opérations et consultez les statistiques.</p>

                <div class="dashboard-grid">
                    <a href="<?= site_url('operateur') ?>" class="dashboard-card primary">
                        <div class="card-icon">01</div>
                        <h3>Gestion des Opérateurs</h3>
                        <p>Configurer les préfixes et les opérateurs de téléphonie</p>
                    </a>

                    <a href="<?= site_url('type-operation') ?>" class="dashboard-card success">
                        <div class="card-icon">02</div>
                        <h3>Types d'Opérations</h3>
                        <p>Gérer les types d'opérations et les tranches de frais</p>
                    </a>

                    <a href="<?= site_url('admin/gains') ?>" class="dashboard-card info">
                        <div class="card-icon">03</div>
                        <h3>Gains par Frais</h3>
                        <p>Consulter les gains générés par les frais de retrait et transfert</p>
                    </a>

                    <a href="<?= site_url('admin/clients') ?>" class="dashboard-card warning">
                        <div class="card-icon">04</div>
                        <h3>Comptes Clients</h3>
                        <p>Voir la situation de tous les comptes clients</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
