<?php

/** @var float|int $solde */
/** @var string|null $telephone */
/** @var string|null $operateur */
/** @var string|null $dateCreation */
/** @var int $totalOperations */
$titre = 'Mon solde — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Mon compte</h4>
</div>

<div class="wallet-card">
    <div class="wallet-card-top">
        <span class="wallet-card-label">Solde disponible</span>
        <span class="wallet-card-chip"></span>
    </div>
    <div class="wallet-card-amount tabular"><?= number_format($solde, 0, ',', ' ') ?><small>Ar</small></div>
    <div class="wallet-card-foot">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 7v5l3 3" />
        </svg>
        Mis a jour a l'instant
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Informations du compte</h5>
    </div>
    <div class="card-body">
        <div class="info-list">
            <div class="info-item">
                <span class="info-label">Telephone</span>
                <span class="info-value"><?= esc($telephone ?? '—') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Operateur</span>
                <span class="info-value"><?= esc($operateur ?? '—') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Membre depuis</span>
                <span class="info-value"><?= esc($dateCreation ? date('d/m/Y', strtotime($dateCreation)) : '—') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Operations effectuees</span>
                <span class="info-value"><?= number_format($totalOperations, 0, ',', ' ') ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Comment fonctionne mon solde ?</h5>
    </div>
    <div class="card-body">
        <div class="info-list">
            <div class="info-item">
                <span class="info-label">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                    </svg>
                    Depot
                </span>
                <span class="info-value">Credite votre solde en especes</span>
            </div>
            <div class="info-item">
                <span class="info-label">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;">
                        <path d="M17 1l4 4-4 4" /><path d="M3 11V9a4 4 0 014-4h14" /><path d="M7 23l-4-4 4-4" /><path d="M21 13v2a4 4 0 01-4 4H3" />
                    </svg>
                    Retrait
                </span>
                <span class="info-value">Debite le montant + frais fixes</span>
            </div>
            <div class="info-item">
                <span class="info-label">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.4rem;">
                        <path d="M17 1l4 4-4 4" /><path d="M3 11V9a4 4 0 014-4h14" /><path d="M7 23l-4-4 4-4" /><path d="M21 13v2a4 4 0 01-4 4H3" />
                    </svg>
                    Transfert
                </span>
                <span class="info-value">Debite montant + frais + commission %</span>
            </div>
        </div>
    </div>
</div>

<?= $this->include('client/template/footer') ?>

