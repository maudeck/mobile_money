<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Tranche - <?= esc($type->libelle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Ajouter une Tranche - <?= esc($type->libelle) ?></h4>
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

                        <form action="<?= site_url('type-operation/add-tranche/' . $type->id) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="montant_min" class="form-label">Montant Minimum (Ar)</label>
                                <input type="number" class="form-control" id="montant_min" name="montant_min" value="<?= old('montant_min') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="montant_max" class="form-label">Montant Maximum (Ar)</label>
                                <input type="number" class="form-control" id="montant_max" name="montant_max" value="<?= old('montant_max') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="frais" class="form-label">Frais (Ar)</label>
                                <input type="number" class="form-control" id="frais" name="frais" value="<?= old('frais') ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="<?= site_url('type-operation/tranches/' . $type->id) ?>" class="btn btn-secondary">Retour</a>
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
