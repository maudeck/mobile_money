<?php
$titre = 'Modifier un operateur — Mobile Money';
$topbarTitle = 'Modifier un operateur';
$activeNav = 'operateur';
?>
<?= $this->extend('admin/template/admin_template') ?>
<?php $this->section('content'); ?>
<div style="max-width:480px;margin:0 auto;padding:2.5rem 1.5rem;">
    <a href="<?= site_url('operateur') ?>" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--navy);font-weight:600;font-size:.88rem;margin-bottom:1.4rem;">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M19 12H5" />
            <path d="M12 19l-7-7 7-7" />
        </svg>
        Retour aux operateurs
    </a>

    <div class="card">
        <div class="card-header">
            <h4>Modifier un operateur</h4>
            <span class="badge badge-amber">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.7 1.7 0 00.34 1.87 2 2 0 11-2.83 2.83 1.7 1.7 0 00-1.87-.34 1.7 1.7 0 00-1.04 1.56V21a2 2 0 11-4 0v-.09a1.7 1.7 0 00-1.04-1.56 1.7 1.7 0 00-1.87.34l-.06.06a2 2 0 112.83-2.83l.06-.06a1.7 1.7 0 001.56-1.04H3a2 2 0 110-4h.09a1.7 1.7 0 00-1.56-1.04 1.7 1.7 0 00-.34-1.87l-.06-.06a2 2 0 112.83-2.83l.06.06a1.7 1.7 0 001.87.34H9a1.7 1.7 0 001.04-1.56V3a2 2 0 114 0v.09a1.7 1.7 0 001.04 1.56 1.7 1.7 0 001.87-.34l-.06-.06a2 2 0 112.83 2.83l.06.06a1.7 1.7 0 00-.34 1.87V9a1.7 1.7 0 001.56 1.04H21a2 2 0 110 4h-.09a1.7 1.7 0 00-1.56 1.04z" />
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

            <form action="<?= site_url('operateur/update/' . $operateur->id) ?>" method="post">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="code_prefixe" class="field-label">Code prefixe</label>
                    <input type="text" class="control" id="code_prefixe" name="code_prefixe" value="<?= esc($operateur->code_prefixe) ?>" required>
                </div>
                <div class="field">
                    <label for="operateur_nom" class="field-label">Nom operateur</label>
                    <input type="text" class="control" id="operateur_nom" name="operateur_nom" value="<?= esc($operateur->operateur_nom) ?>" required>
                </div>
                <div style="display:flex;justify-content:space-between;gap:.8rem;margin-top:1.6rem;">
                    <a href="<?= site_url('operateur') ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning">
                        Modifier
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
