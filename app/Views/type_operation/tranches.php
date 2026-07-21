<?php
$titre = 'Tranches de frais — Mobile Money';
$topbarTitle = 'Tranches de frais — ' . esc($type->libelle);
$activeNav = 'type-operation';
$topbarAction = '<div style="display:flex;gap:.6rem;">
    <a href="' . site_url('type-operation') . '" class="btn btn-secondary btn-sm">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <path d="M19 12H5" />
            <path d="M12 19l-7-7 7-7" />
        </svg>
        Retour
    </a>
    <a href="' . site_url('type-operation/add-tranche/' . $type->id) . '" class="btn btn-accent btn-sm">
        <svg class="icon icon-sm" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Ajouter une tranche
    </a>
</div>';
?>
<?= $this->extend('admin/template/admin_template') ?>
<?php $this->section('content'); ?>
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

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th class="number">Montant min (Ar)</th>
                    <th class="number">Montant max (Ar)</th>
                    <th class="number">Frais (Ar)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tranches as $tranche): ?>
                    <tr>
                        <td class="number"><?= number_format($tranche->montant_min, 0, ',', ' ') ?></td>
                        <td class="number"><?= number_format($tranche->montant_max, 0, ',', ' ') ?></td>
                        <td class="number"><?= number_format($tranche->frais, 0, ',', ' ') ?></td>
                        <td>
                            <form action="<?= site_url('type-operation/delete-tranche/' . $type->id . '/' . $tranche->id) ?>" method="post" data-confirm="Etes-vous sur de vouloir supprimer cette tranche ?">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tranches)): ?>
                    <tr>
                        <td colspan="4" class="empty-row text-center">Aucune tranche definie</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection(); ?>
