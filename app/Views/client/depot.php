<?php

/** @var object $type */
$titre = ucfirst($type->libelle) . ' — Mobile Money';
?>
<?= $this->include('client/template/header') ?>

<h2 style="margin-bottom:.2rem;"><?= esc($type->libelle) ?></h2>
<p>Veuillez remplir les informations ci-dessous pour effectuer l'opération.</p>

<div class="card shadow" style="margin-top: 1.4rem;">
    <div class="card-body">
        <form id="operation-form"
            data-frais-url="<?= site_url('client/frais/' . strtolower($type->libelle)) ?>"
            data-submit-url="<?= site_url('client/' . strtolower($type->libelle)) ?>"
            data-csrf-name="<?= csrf_token() ?>"
            data-csrf-hash="<?= csrf_hash() ?>">

            <?php if (strtolower($type->libelle) === 'transfert'): ?>
                <div class="field">
                    <label class="field-label" for="beneficiaire">Bénéficiaire</label>
                    <select class="control" id="beneficiaire" name="beneficiaire" required>
                        <option value="">-- Sélectionner un bénéficiaire --</option>
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

<!-- Inclusion du script spécifique à la gestion des formulaires d'opération -->
<script src="<?= base_url('js/script.js') ?>"></script>

<?= $this->include('client/template/footer') ?>