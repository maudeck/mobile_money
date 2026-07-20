<?php

/** @var array $historique */
$titre = 'Historique des operations — Mobile Money';
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
        <div class="section-title">
            <h4>Historique des operations</h4>
        </div>

        <div class="list-card">
            <?php if (empty($historique)): ?>
                <div class="empty-state">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                    <p>Aucune operation trouvee</p>
                </div>
            <?php else: ?>
                <?php foreach ($historique as $op): ?>
                    <div class="op-row">
                        <div class="op-icon <?= strtolower($op->type_operation) === 'depot' ? 'in' : (strtolower($op->type_operation) === 'transfert' ? 'in' : 'out') ?>">
                            <svg class="icon" viewBox="0 0 24 24">
                                <?php if (strtolower($op->type_operation) === 'depot'): ?>
                                    <path d="M12 19V5M5 12l7-7 7 7" />
                                <?php elseif (strtolower($op->type_operation) === 'retrait'): ?>
                                    <path d="M12 5v14M5 12l7 7 7-7" />
                                <?php else: ?>
                                    <path d="M17 1l4 4-4 4" />
                                    <path d="M3 11V9a4 4 0 014-4h14" />
                                    <path d="M7 23l-4-4 4-4" />
                                    <path d="M21 13v2a4 4 0 01-4 4H3" />
                                <?php endif; ?>
                            </svg>
                        </div>
                        <div class="op-main">
                            <div class="op-type"><?= esc($op->type_operation) ?></div>
                            <div class="op-meta"><?= esc($op->date_operation) ?></div>
                        </div>
                        <div class="op-amount">
                            <?= number_format($op->montant, 0, ',', ' ') ?> Ar
                            <span class="op-fee">Frais : <?= number_format($op->frais_applique, 0, ',', ' ') ?> Ar</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
