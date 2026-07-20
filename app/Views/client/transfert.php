<?php

/** @var array $clients */
$titre = 'Transfert — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<div class="section-title" style="margin-top:0;">
    <h4>Transfert</h4>
</div>

<div id="transfert-simple-section" class="card">
    <div class="card-body">
        <form id="transfert-form"
            data-frais-url="<?= site_url('client/frais/transfert') ?>"
            data-submit-url="<?= site_url('client/transfert') ?>"
            data-csrf-name="<?= csrf_token() ?>"
            data-csrf-hash="<?= csrf_hash() ?>">

            <div class="field">
                <label for="beneficiaire" class="field-label">Beneficiaire (Numero Telma 034)</label>
                <input type="text" class="control" id="beneficiaire" name="beneficiaire" placeholder="034 00 000 00" required>
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

<div id="multi-transfert-section" style="display: none;">
    <div class="card">
        <div class="card-body">
            <div class="field">
                <label for="montant-total" class="field-label">Montant total a transferer</label>
                <div class="control-amount">
                    <input type="number" class="control" id="montant-total" placeholder="0">
                    <span class="suffix">Ar</span>
                </div>
            </div>

            <div class="field">
                <div class="fee-display" id="frais-total-display">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="13" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Frais : —
                </div>
            </div>

            <div class="field">
                <label class="field-label">Beneficiaires (Telma 034 uniquement)</label>
                <div id="transfert-container" data-clients="<?= htmlspecialchars(json_encode(array_column($clients, 'telephone')), ENT_QUOTES, 'UTF-8') ?>" data-frais-url="<?= site_url('client/frais/transfert') ?>" data-submit-url="<?= site_url('client/transfert-multiple') ?>" data-csrf-name="<?= csrf_token() ?>" data-csrf-hash="<?= csrf_hash() ?>">
                    <div class="card transfert-card" data-index="0">
                        <div class="card-body">
                            <button type="button" class="btn btn-danger btn-sm remove-transfert" style="float: right;" title="Supprimer ce beneficiaire">
                                <svg class="icon icon-sm" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                </svg>
                            </button>
                            <div class="field">
                                <label for="beneficiaire_0" class="field-label">Beneficiaire (Numero Telma 034)</label>
                                <input type="text" class="control beneficiaire-input" id="beneficiaire_0" placeholder="034 00 000 00" required>
                            </div>
                            <div class="field">
                                <label class="field-label">Montant par beneficiaire</label>
                                <div class="control-amount">
                                    <input type="text" class="control montant-part" id="montant-part_0" readonly>
                                    <span class="suffix">Ar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary btn-block" id="add-transfert" style="margin-top: 1rem;">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Ajouter un beneficiaire
            </button>

            <button type="button" class="btn btn-accent btn-block" id="submit-all-transferts" style="margin-top: 1rem;">
                Valider tous les transferts
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>

            <div id="transfert-errors" style="margin-top: 1rem;"></div>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 1rem;">
    <button type="button" class="btn btn-secondary btn-block" id="toggle-mode-transfert">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M17 1l4 4-4 4" />
            <path d="M3 11V9a4 4 0 014-4h14" />
            <path d="M7 23l-4-4 4-4" />
            <path d="M21 13v2a4 4 0 01-4 4H3" />
        </svg>
        Faire un transfert multiple
    </button>
</div>

<?= $this->include('client/template/footer') ?>
