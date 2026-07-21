<?php $titre = 'Accueil — Mobile Money'; ?>
<?= $this->include('client/template/header') ?>

<h2 style="margin-bottom:.2rem;">Bonjour</h2>
<p>Bienvenue sur votre espace client.</p>
<span class="badge badge-navy" style="margin-bottom:1.4rem;display:inline-flex;">
    <svg class="icon icon-sm" viewBox="0 0 24 24">
        <path d="M20 6L9 17l-5-5" />
    </svg>
    Compte client
</span>

<div class="section-title" style="margin-top:.2rem;">
    <h4>Actions rapides</h4>
</div>
<div class="quick-actions">
    <a href="<?= site_url('client/solde') ?>" class="quick-action">
        <span class="quick-icon"><svg class="icon" viewBox="0 0 24 24">
                <rect x="2" y="6" width="20" height="13" rx="3" />
                <path d="M2 10h20" />
            </svg></span>
        Mon solde
    </a>
    <a href="<?= site_url('client/epargne') ?>" class="quick-action">
        <span class="quick-icon"><svg class="icon" viewBox="0 0 24 24">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
            </svg></span>
        Mon épargne
    </a>
    <?php foreach (get_operation_types() as $type): ?>
        <a href="<?= site_url('client/' . strtolower($type->libelle)) ?>" class="quick-action">
            <span class="quick-icon"><svg class="icon" viewBox="0 0 24 24">
                    <path d="M17 1l4 4-4 4" />
                    <path d="M3 11V9a4 4 0 014-4h14" />
                    <path d="M7 23l-4-4 4-4" />
                    <path d="M21 13v2a4 4 0 01-4 4H3" />
                </svg></span>
            <?= esc($type->libelle) ?>
        </a>
    <?php endforeach; ?>
    <a href="<?= site_url('client/historique') ?>" class="quick-action">
        <span class="quick-icon"><svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l3 3" />
            </svg></span>
        Historique
    </a>
</div>

<?= $this->include('client/template/footer') ?>