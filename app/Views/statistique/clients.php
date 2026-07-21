<?php
$titre = 'Comptes clients — Mobile Money';
$topbarTitle = 'Situation des comptes clients';
$activeNav = 'admin/clients';
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

<div class="row-header">
    <form method="get" action="<?= site_url('admin/clients') ?>" style="display:flex;gap:.7rem;flex-wrap:wrap;align-items:flex-end;flex:1;min-width:0;">
        <div class="field" style="flex:1 1 220px;margin-bottom:0;min-width:180px;">
            <label for="telephone" class="field-label">Telephone</label>
            <input type="text" class="control" id="telephone" name="telephone" placeholder="Rechercher par numero..." value="<?= esc($telephone ?? '') ?>">
        </div>
        <div class="field" style="flex:1 1 220px;margin-bottom:0;min-width:180px;">
            <label for="prefixe" class="field-label">Operateur</label>
            <select class="control" id="prefixe" name="prefixe">
                <option value="034" <?= ($prefixe ?? '') === '034' ? 'selected' : '' ?>>Telma (034)</option>
                <?php foreach ($operateurs ?? [] as $op): ?>
                    <?php if ($op->code_prefixe !== '034'): ?>
                        <option value="<?= esc($op->code_prefixe) ?>" <?= ($prefixe ?? '') === $op->code_prefixe ? 'selected' : '' ?>>
                            <?= esc($op->operateur_nom) ?> (<?= esc($op->code_prefixe) ?>)
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="<?= site_url('admin/clients') ?>" class="btn btn-secondary">Reinitialiser</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Telephone</th>
                    <th>Operateur</th>
                    <th>Prefixe</th>
                    <th class="number">Solde (Ar)</th>
                    <th>Date creation</th>
                    <th class="number">Nb operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= esc($client->id) ?></td>
                        <td><?= esc($client->telephone) ?></td>
                        <td><?= esc($client->operateur_nom ?? 'N/A') ?></td>
                        <td><span class="badge badge-mist"><?= esc($client->code_prefixe ?? 'N/A') ?></span></td>
                        <td class="number"><?= number_format($client->solde, 0, ',', ' ') ?></td>
                        <td><?= esc($client->date_creation) ?></td>
                        <td class="number"><?= number_format($client->nombre_operations, 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="7" class="empty-row text-center">Aucun client trouve</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection(); ?>
