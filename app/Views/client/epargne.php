<?php

/** @var float $pourcentage */
/** @var float $montant_total */
$titre = 'Mon épargne — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Mon épargne</h4>
</div>

<?php if (session()->get('success')): ?>
    <div class="alert alert-success" data-autodismiss>
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M20 6L9 17l-5-5" />
        </svg>
        <span><?= session()->get('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->get('error')): ?>
    <div class="alert alert-danger" data-autodismiss>
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <line x1="12" y1="8" x2="12" y2="13" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        <span><?= session()->get('error') ?></span>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Résumé de mon épargne</h5>
    </div>
    <div class="card-body">
        <div class="info-list">
            <div class="info-item">
                <span class="info-label">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                    </svg>
                    Pourcentage d'épargne
                </span>
                <span class="info-value"><?= number_format($pourcentage, 0, ',', ' ') ?>%</span>
            </div>
            <div class="info-item">
                <span class="info-label">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;">
                        <rect x="2" y="6" width="20" height="13" rx="3" />
                        <path d="M2 10h20" />
                    </svg>
                    Montant total épargné
                </span>
                <span class="info-value"><?= number_format($montant_total, 0, ',', ' ') ?> Ar</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Modifier mon pourcentage d'épargne</h5>
    </div>
    <div class="card-body">
        <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1.2rem;">
            Lorsque vous recevez de l'argent (dépôt ou transfert), le pourcentage défini ci-dessous sera automatiquement mis de côté dans votre épargne. Le reste sera ajouté à votre solde disponible.
        </p>
        <form action="<?= site_url('client/epargne') ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="pourcentage" class="field-label">Pourcentage d'épargne (%)</label>
                <input type="number" class="control" id="pourcentage" name="pourcentage" value="<?= esc($pourcentage) ?>" min="0" max="100" step="1" required>
                <span class="field-hint">Entre 0 et 100. Ex : 20 pour mettre 20% de chaque entrée en épargne.</span>
            </div>
            <button type="submit" class="btn btn-accent btn-block">
                Enregistrer
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5" />
                </svg>
            </button>
        </form>
    </div>
</div>

<?= $this->include('client/template/footer') ?>
