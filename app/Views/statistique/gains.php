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

<div style="display:flex;gap:.6rem;margin-bottom:1.2rem;flex-wrap:wrap;">
    <a href="<?= site_url('admin/gains?type=Retrait') ?>" class="btn btn-primary" style="background:var(--navy);color:#fff;">
        Retrait
    </a>
    <a href="<?= site_url('admin/gains?type=Transfert') ?>" class="btn btn-primary" style="background:var(--navy);color:#fff;">
        Transfert
    </a>
</div>

<?php if (($type ?? '') === '' || ($type ?? '') === 'Retrait'): ?>
<div class="card mb-4" data-section="retrait">
    <div class="card-header">
        <h5 class="mb-0">Gains par type d'opération</h5>
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
<?php endif; ?>

<?php if (($type ?? '') === '' || ($type ?? '') === 'Transfert'): ?>
<div class="card mb-4" data-section="transfert">
    <div class="card-header">
        <h5 class="mb-0">Gains par opérateur source (Transferts)</h5>
    </div>
    <div class="card-body">
        <form method="get" action="<?= site_url('admin/gains') ?>" style="display:flex;gap:.7rem;flex-wrap:wrap;align-items:flex-end;flex:1;min-width:0;">
            <input type="hidden" name="type" value="<?= esc($type ?? '') ?>">
            <div class="field" style="flex:1 1 220px;margin-bottom:0;min-width:180px;">
                <label for="operateur_source" class="field-label">Opérateur source</label>
                <select class="control" id="operateur_source" name="operateur_source">
                    <option value="">Tous les opérateurs</option>
                    <?php foreach ($operateurs ?? [] as $op): ?>
                        <option value="<?= esc($op->code_prefixe) ?>" <?= ($operateurSource ?? '') === $op->code_prefixe ? 'selected' : '' ?>>
                            <?= esc($op->operateur_nom) ?> (<?= esc($op->code_prefixe) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="<?= site_url('admin/gains?type=Transfert') ?>" class="btn btn-secondary">Reinitialiser</a>
        </form>
        <div class="table-responsive mt-3">
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

<div class="card mb-4" data-section="transfert">
    <div class="card-header">
        <h5 class="mb-0">Montants à envoyer aux opérateurs (Commissions)</h5>
    </div>
    <div class="card-body">
        <form method="get" action="<?= site_url('admin/gains') ?>" style="display:flex;gap:.7rem;flex-wrap:wrap;align-items:flex-end;flex:1;min-width:0;">
            <input type="hidden" name="type" value="<?= esc($type ?? '') ?>">
            <div class="field" style="flex:1 1 220px;margin-bottom:0;min-width:180px;">
                <label for="operateur_dest" class="field-label">Opérateur destinataire</label>
                <select class="control" id="operateur_dest" name="operateur_dest">
                    <option value="">Tous les opérateurs</option>
                    <?php foreach ($operateurs ?? [] as $op): ?>
                        <option value="<?= esc($op->code_prefixe) ?>" <?= ($operateurDest ?? '') === $op->code_prefixe ? 'selected' : '' ?>>
                            <?= esc($op->operateur_nom) ?> (<?= esc($op->code_prefixe) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="<?= site_url('admin/gains?type=Transfert') ?>" class="btn btn-secondary">Reinitialiser</a>
        </form>
        <div class="table-responsive mt-3">
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
<?php endif; ?>
<?php $this->endSection(); ?>
