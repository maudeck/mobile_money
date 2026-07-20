<?php

/** @var array $historique */
$titre = 'Historique — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Historique des operations</h4>
</div>

<div class="list-card">
    <?php if (empty($historique)): ?>
        <div class="empty-state">
            <svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l3 3" />
            </svg>
            <p style="margin:0;">Aucune operation trouvee.</p>
        </div>
    <?php else: ?>
        <?php foreach ($historique as $op):
            $type = strtolower($op->type_operation);
            $sens = (strpos($type, 'depot') !== false) ? 'in' : 'out';
        ?>
            <div class="op-row">
                <div class="op-icon <?= $sens ?>">
                    <?php if ($sens === 'in'): ?>
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M12 5v14" />
                            <path d="M6 13l6 6 6-6" />
                        </svg>
                    <?php else: ?>
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M12 19V5" />
                            <path d="M6 11l6-6 6 6" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="op-main">
                    <div class="op-type"><?= esc($op->type_operation) ?></div>
                    <div class="op-meta">
                        <?= esc($op->date_operation) ?> ·
                        <?= esc($op->emetteur_telephone) ?><?= $op->destinataire_telephone ? ' → ' . esc($op->destinataire_telephone) : '' ?>
                    </div>
                </div>
                <div class="op-amount tabular">
                    <?= number_format($op->montant, 0, ',', ' ') ?> Ar
                    <span class="op-fee">frais <?= number_format($op->frais_applique, 0, ',', ' ') ?> Ar</span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->include('client/template/footer') ?>