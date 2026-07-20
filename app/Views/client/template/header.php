<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Mobile Money</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link active" href="<?= site_url('client/dashboard') ?>">Home </a>
            <a class="nav-item nav-link" href="<?= site_url('client/solde') ?>">Solde</a>
            <?php foreach (get_operation_types() as $type): ?>
                <a class="nav-item nav-link" href="<?= site_url('client/' . strtolower($type->libelle)) ?>">
                    <?= esc($type->libelle) ?>
                </a>
            <?php endforeach; ?>
            <a class="nav-item nav-link" href="<?= site_url('client/historique') ?>">Historique</a>
        </div>
        <div class="ms-auto">
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
    </div>
</nav>