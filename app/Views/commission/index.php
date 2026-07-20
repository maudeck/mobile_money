<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions par Opérateurs</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Commissions Telma (034) vers autres opérateurs</h2>
            <div>
                <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary me-2">Retour</a>
                <a href="<?= site_url('commission/create') ?>" class="btn btn-primary">Ajouter une commission</a>
            </div>
        </div>

        <?php if (session()->get('success')): ?>
            <div class="alert alert-success">
                <?= session()->get('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="alert alert-danger">
                <?= session()->get('error') ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="<?= site_url('commission') ?>" class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label for="destination" class="form-label">Opérateur destination</label>
                        <select class="form-select" id="destination" name="destination" onchange="this.form.submit()">
                            <option value="">Toutes les destinations</option>
                            <?php foreach ($operateurs as $op): ?>
                                <?php if ((int) $op->id !== (int) ($source->id ?? 0)): ?>
                                    <option value="<?= esc($op->id) ?>" <?= ($selectedDest && (int) $selectedDest->id === (int) $op->id) ? 'selected' : '' ?>>
                                        <?= esc($op->operateur_nom) ?> (<?= esc($op->code_prefixe) ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <noscript>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </noscript>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($commissions)): ?>
            <div class="card">
                <div class="card-body empty-state">
                    <div class="empty-state-icon">—</div>
                    <p>Aucune commission configurée.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($commissions as $destName => $tranches): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Telma (034) → <?= esc($destName) ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Destination</th>
                                    <th class="number">Commission (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tranches as $tranche): ?>
                                    <tr>
                                        <td><?= esc($destName) ?></td>
                                        <td class="number"><?= number_format($tranche->commission_pct, 0, ',', ' ') ?></td>
                                        <td>
                                            <form action="<?= site_url('commission/delete/' . $tranche->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($tranches)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Aucune commission définie</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
