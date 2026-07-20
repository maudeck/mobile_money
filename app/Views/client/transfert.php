<?php

/** @var object $type */
/** @var array $clients */
$titre = ucfirst($type->libelle) . ' — Mobile Money';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre) ?></title>
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>
<body>
<?= $this->include('client/template/header') ?>

<div class="client-shell">
    <main class="app-main">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4"><?= esc($type->libelle) ?></h2>
                <form id="operation-form" data-frais-url="<?= site_url('client/frais/' . strtolower($type->libelle)) ?>" data-submit-url="<?= site_url('client/' . strtolower($type->libelle)) ?>" data-csrf-name="<?= csrf_token() ?>" data-csrf-hash="<?= csrf_hash() ?>">
                    <?php if (strtolower($type->libelle) === 'transfert'): ?>
                        <div class="field">
                            <label class="field-label" for="beneficiaire">Beneficiaire</label>
                            <select class="control" id="beneficiaire" name="beneficiaire" required>
                                <option value="">-- Selectionner un beneficiaire --</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= esc($client->telephone) ?>">
                                        <?= esc($client->telephone) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="field">
                        <label class="field-label" for="montant">Montant</label>
                        <div class="control-amount">
                            <input type="number" class="control" id="montant" name="montant" placeholder="0" required>
                            <span class="suffix">Ar</span>
                        </div>
                    </div>
                    <div class="field">
                        <div class="fee-display" id="frais-display">Frais : —</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
