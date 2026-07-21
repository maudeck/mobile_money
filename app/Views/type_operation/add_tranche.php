<?php
$titre = 'Ajouter une tranche — Mobile Money';
$topbarTitle = 'Ajouter une tranche — ' . esc($type->libelle);
$activeNav = 'type-operation';
$topbarAction = '<a href="' . site_url('type-operation/tranches/' . $type->id) . '" class="btn btn-secondary btn-sm">
    <svg class="icon icon-sm" viewBox="0 0 24 24">
        <path d="M19 12H5" />
        <path d="M12 19l-7-7 7-7" />
    </svg>
    Retour
</a>';
?>
<?= $this->extend('admin/template/admin_template') ?>
<?php $this->section('content'); ?>
<div style="max-width:480px;margin:0 auto;padding:2.5rem 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h4>Ajouter une tranche — <?= esc($type->libelle) ?></h4>
            <span class="badge badge-amber">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
            </span>
        </div>
        <div class="card-body">
            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger" data-autodismiss><span><?= session()->get('error') ?></span></div>
            <?php endif; ?>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><span><?= $validation->listErrors() ?></span></div>
            <?php endif; ?>

            <form action="<?= site_url('type-operation/add-tranche/' . $type->id) ?>" method="post">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="montant_min" class="field-label">Montant minimum (Ar)</label>
                    <input type="number" class="control" id="montant_min" name="montant_min" value="<?= old('montant_min') ?>" required>
                </div>
                <div class="field">
                    <label for="montant_max" class="field-label">Montant maximum (Ar)</label>
                    <input type="number" class="control" id="montant_max" name="montant_max" value="<?= old('montant_max') ?>" required>
                </div>
                <div class="field">
                    <label for="frais" class="field-label">Frais (Ar)</label>
                    <input type="number" class="control" id="frais" name="frais" value="<?= old('frais') ?>" required>
                </div>
                <div style="display:flex;justify-content:space-between;gap:.8rem;margin-top:1.6rem;">
                    <a href="<?= site_url('type-operation/tranches/' . $type->id) ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-accent">
                        Ajouter
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
