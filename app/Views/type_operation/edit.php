<?php
$titre = 'Modifier un type d\'operation — Mobile Money';
$topbarTitle = 'Modifier un type d\'operation';
$activeNav = 'type-operation';
?>
<?= $this->extend('admin/template/admin_template') ?>
<?php $this->section('content'); ?>
<div style="max-width:480px;margin:0 auto;padding:2.5rem 1.5rem;">
    <a href="<?= site_url('type-operation') ?>" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--navy);font-weight:600;font-size:.88rem;margin-bottom:1.4rem;">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M19 12H5" />
            <path d="M12 19l-7-7 7-7" />
        </svg>
        Retour aux types d'operations
    </a>

    <div class="card">
        <div class="card-header">
            <h4>Modifier un type d'operation</h4>
            <span class="badge badge-amber">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M20.6 12L12 20.6a2 2 0 01-2.8 0L3.4 14.8a2 2 0 010-2.8L12 3.4H20a1 1 0 011 1v8z" />
                    <circle cx="16.5" cy="7.5" r="1.2" fill="currentColor" stroke="none" />
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

            <form action="<?= site_url('type-operation/update/' . $type->id) ?>" method="post">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="libelle" class="field-label">Libelle</label>
                    <input type="text" class="control" id="libelle" name="libelle" value="<?= esc($type->libelle) ?>" required>
                </div>
                <div style="display:flex;justify-content:space-between;gap:.8rem;margin-top:1.6rem;">
                    <a href="<?= site_url('type-operation') ?>" class="btn btn-secondary">Annuler</a>
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
