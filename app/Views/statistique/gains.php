<?php
$titre = 'Gains par frais — Mobile Money';
$topbarTitle = 'Gains par frais';
$activeNav = 'admin/gains';
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

<div class="card mb-4">

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Gains globaux par type d'opération</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type d'opération</th>
                        <th class="number">Nombre d'opérations</th>
                        <th class="number">Total frais (Ar)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gains as $gain): ?>
                        <tr>
                            <td><?= esc($gain->libelle) ?></td>
                            <td class="number"><?= number_format($gain->nombre_operations, 0, ',', ' ') ?></td>
                            <td class="number"><?= number_format($gain->total_frais, 0, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($gains)): ?>
                        <tr>
                            <td colspan="3" class="empty-row text-center">Aucune donnée disponible</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Gains par opérateur source (Transferts)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Préfixe</th>
                        <th class="number">Nombre d'opérations</th>
                        <th class="number">Total frais (Ar)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gains_operateurs as $op): ?>
                        <tr>
                            <td><?= esc($op->operateur_nom) ?></td>
                            <td><?= esc($op->code_prefixe) ?></td>
                            <td class="number"><?= number_format($op->nombre_operations, 0, ',', ' ') ?></td>
                            <td class="number"><?= number_format($op->total_frais, 0, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($gains_operateurs)): ?>
                        <tr>
                            <td colspan="4" class="empty-row text-center">Aucune donnée disponible</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Montants à envoyer aux opérateurs (Commissions)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Préfixe</th>
                        <th class="number">Total commissions (Ar)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commissions_operateurs as $comm): ?>
                        <tr>
                            <td><?= esc($comm->operateur_nom) ?></td>
                            <td><?= esc($comm->code_prefixe) ?></td>
                            <td class="number"><?= number_format($comm->total_commission, 0, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($commissions_operateurs)): ?>
                        <tr>
                            <td colspan="3" class="empty-row text-center">Aucune commission à payer</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
