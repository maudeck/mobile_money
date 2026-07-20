<?php

/** @var array $clients */
$titre = 'Transfert — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Transfert</h4>
</div>

<div class="card">
    <div class="card-body">
        <form id="transfert-form"
            data-frais-url="<?= site_url('client/frais/transfert') ?>"
            data-submit-url="<?= site_url('client/transfert') ?>"
            data-csrf-name="<?= csrf_token() ?>"
            data-csrf-hash="<?= csrf_hash() ?>">

            <div class="field">
                <label for="beneficiaire" class="field-label">Beneficiaire</label>
                <select class="control" id="beneficiaire" name="beneficiaire" required>
                    <option value="">-- Selectionner un beneficiaire --</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= esc($client->telephone) ?>"><?= esc($client->telephone) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="montant" class="field-label">Montant a transferer</label>
                <div class="control-amount">
                    <input type="number" class="control" id="montant" name="montant" placeholder="0" required>
                    <span class="suffix">Ar</span>
                </div>
            </div>

            <div class="field">
                <div class="fee-display" id="frais-display">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="13" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Frais : —
                </div>
            </div>

            <button type="submit" class="btn btn-accent btn-block">
                Valider le transfert
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M17 1l4 4-4 4" />
                    <path d="M3 11V9a4 4 0 014-4h14" />
                    <path d="M7 23l-4-4 4-4" />
                    <path d="M21 13v2a4 4 0 01-4 4H3" />
                </svg>
            </button>
        </form>
    </div>
</div>

<?= $this->include('client/template/footer') ?>