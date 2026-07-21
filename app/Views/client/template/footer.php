</main>

<nav class="bottom-nav">
    <a href="<?= site_url('client/dashboard') ?>" class="bottom-nav-item<?= url_is('client/dashboard') ? ' active' : '' ?>">
        <svg class="icon" viewBox="0 0 24 24">
            <path d="M3 11l9-7 9 7" />
            <path d="M5 10v10h14V10" />
        </svg>
        Accueil
    </a>
    <a href="<?= site_url('client/solde') ?>" class="bottom-nav-item<?= url_is('client/solde') ? ' active' : '' ?>">
        <svg class="icon" viewBox="0 0 24 24">
            <rect x="2" y="6" width="20" height="13" rx="3" />
            <path d="M2 10h20" />
        </svg>
        Solde
    </a>
    <a href="<?= site_url('client/epargne') ?>" class="bottom-nav-item<?= url_is('client/epargne') ? ' active' : '' ?>">
        <svg class="icon" viewBox="0 0 24 24">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
        </svg>
        Epargne
    </a>
    <?php foreach (get_operation_types() as $type):
        $slug = strtolower($type->libelle);
    ?>
        <a href="<?= site_url('client/' . $slug) ?>" class="bottom-nav-item<?= url_is('client/' . $slug) ? ' active' : '' ?>">
            <svg class="icon" viewBox="0 0 24 24">
                <path d="M17 1l4 4-4 4" />
                <path d="M3 11V9a4 4 0 014-4h14" />
                <path d="M7 23l-4-4 4-4" />
                <path d="M21 13v2a4 4 0 01-4 4H3" />
            </svg>
            <?= esc($type->libelle) ?>
        </a>
    <?php endforeach; ?>
    <a href="<?= site_url('client/historique') ?>" class="bottom-nav-item<?= url_is('client/historique') ? ' active' : '' ?>">
        <svg class="icon" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 7v5l3 3" />
        </svg>
        Historique
    </a>
</nav>
</div>

  <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>