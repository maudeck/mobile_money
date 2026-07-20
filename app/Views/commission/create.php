<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Commission</title>
    <link href="<?= base_url('assets/bootstrap-5.3.2-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-custom.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Ajouter une Commission</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($validation)): ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors() ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('commission/create') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Opérateur Source</label>
                                <input type="text" class="form-control" value="<?= esc($source->operateur_nom) ?> (<?= esc($source->code_prefixe) ?>)" readonly disabled>
                                <input type="hidden" name="id_prefixe_source" value="<?= esc($source->id) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="id_prefixe_dest" class="form-label">Opérateur Destination</label>
                                <select class="form-control" id="id_prefixe_dest" name="id_prefixe_dest" required>
                                    <option value="">Sélectionner...</option>
                                    <?php foreach ($operateurs as $op): ?>
                                        <?php if ((int) $op->id !== (int) $source->id): ?>
                                            <option value="<?= esc($op->id) ?>"><?= esc($op->operateur_nom) ?> (<?= esc($op->code_prefixe) ?>)</option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="commission_pct" class="form-label">Commission (%)</label>
                                <input type="number" class="form-control" id="commission_pct" name="commission_pct" value="<?= old('commission_pct') ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="<?= site_url('commission') ?>" class="btn btn-secondary">Retour</a>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
