<?php

/** @var float|int $solde */
$titre = 'Mon solde — Mobile Money';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre) ?></title>
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body>
<?= $this->include('client/template/header') ?>

<div class="client-shell">
    <main class="app-main">
        <div class="wallet-card">
            <div class="wallet-card-top">
                <span class="wallet-card-label">Solde disponible</span>
                <span class="wallet-card-chip"></span>
            </div>
            <div class="wallet-card-amount tabular">
                <?= number_format($solde, 0, ',', ' ') ?><small>Ar</small>
            </div>
            <div class="wallet-card-foot">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 7v5l3 3" />
                </svg>
                Mis a jour a l'instant
            </div>
        </div>

        <div class="section-title">
            <h4>Actions rapides</h4>
        </div>

        <div class="quick-actions">
            <?php foreach (get_operation_types() as $type): ?>
                <a href="<?= site_url('client/' . strtolower($type->libelle)) ?>" class="quick-action">
                    <span class="quick-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M17 1l4 4-4 4" />
                            <path d="M3 11V9a4 4 0 014-4h14" />
                            <path d="M7 23l-4-4 4-4" />
                            <path d="M21 13v2a4 4 0 01-4 4H3" />
                        </svg>
                    </span>
                    <?= esc($type->libelle) ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= site_url('client/historique') ?>" class="quick-action">
                <span class="quick-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                Historique
            </a>
        </div>
    </main>
</div>

<script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
