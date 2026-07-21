<?php
$titre = 'Types d\'operations — Mobile Money';
$topbarTitle = 'Types d\'operations';
$activeNav = 'type-operation';
$topbarAction = '<a href="' . site_url('type-operation/create') . '" class="btn btn-accent btn-sm">
    <svg class="icon icon-sm" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
    </svg>
    Ajouter un type
</a>';
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
                    <th>ID</th>
                    <th>Libelle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                    <tr>
                        <td><?= esc($type->id) ?></td>
                        <td><?= esc($type->libelle) ?></td>
                        <td class="action-buttons">
                            <a href="<?= site_url('type-operation/tranches/' . $type->id) ?>" class="btn btn-secondary btn-sm">
                                <svg class="icon icon-sm" viewBox="0 0 24 24">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                </svg>
                                Tranches
                            </a>
                            <a href="<?= site_url('type-operation/edit/' . $type->id) ?>" class="btn btn-warning btn-sm">
                                <svg class="icon icon-sm" viewBox="0 0 24 24">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z" />
                                </svg>
                                Modifier
                            </a>
                            <form action="<?= site_url('type-operation/delete/' . $type->id) ?>" method="post" data-confirm="Etes-vous sur de vouloir supprimer ce type d'operation ?">
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
                <?php if (empty($types)): ?>
                    <tr>
                        <td colspan="3" class="empty-row text-center">Aucun type d'operation trouve</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection(); ?>
