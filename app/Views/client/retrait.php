<?php $titre = 'Retrait — Mobile Money'; ?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Retrait</h4>
</div>

<div class="card">
    <div class="card-body">
        <form id="retrait-form"
            data-frais-url="<?= site_url('client/frais/retrait') ?>"
            data-submit-url="<?= site_url('client/retrait') ?>"
            data-csrf-name="<?= csrf_token() ?>"
            data-csrf-hash="<?= csrf_hash() ?>">

            <div class="field">
                <label for="montant" class="field-label">Montant a retirer</label>
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
                Valider le retrait
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M12 19V5" />
                    <path d="M6 11l6-6 6 6" />
                </svg>
            </button>
        </form>
    </div>
</div>

<?= $this->include('client/template/footer') ?>