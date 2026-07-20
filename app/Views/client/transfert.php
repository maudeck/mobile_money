<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php /** @var array $clients */ ?>
<?= $this->include('client/template/header') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title mb-4">Transfert</h2>
                    <form id="transfert-form">
                        <div class="mb-3">
                            <label for="beneficiaire" class="form-label">Beneficiaire :</label>
                            <select class="form-select" id="beneficiaire" name="beneficiaire" required>
                                <option value="">-- Selectionner un beneficiaire --</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= esc($client->telephone) ?>">
                                        <?= esc($client->telephone) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant :</label>
                            <input type="number" class="form-control" id="montant" name="montant" required>
                        </div>
                        <div class="mb-3">
                            <p class="form-text" id="frais-display">Frais : -</p>
                        </div>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('client/template/footer') ?>
<script>
let currentFrais = null;

document.getElementById('montant').addEventListener('input', function() {
    const montant = this.value;
    const fraisDisplay = document.getElementById('frais-display');

    if (!montant) {
        fraisDisplay.textContent = 'Frais : -';
        fraisDisplay.classList.remove('text-danger');
        currentFrais = null;
        return;
    }

    fetch('<?= site_url('client/frais/transfert') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'montant=' + encodeURIComponent(montant) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            fraisDisplay.textContent = data.error;
            fraisDisplay.classList.add('text-danger');
            currentFrais = null;
        } else {
            fraisDisplay.textContent = 'Frais : ' + data.frais + ' Ar';
            fraisDisplay.classList.remove('text-danger');
            currentFrais = data.frais;
        }
    })
    .catch(() => {
        fraisDisplay.textContent = 'Erreur lors du calcul des frais';
        fraisDisplay.classList.add('text-danger');
        currentFrais = null;
    });
});

document.getElementById('transfert-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const montant = document.getElementById('montant').value;
    const beneficiaire = document.getElementById('beneficiaire').value;

    if (!montant || currentFrais === null || !beneficiaire) {
        alert('Veuillez remplir tous les champs et attendre le calcul des frais.');
        return;
    }

    fetch('<?= site_url('client/transfert') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'montant=' + encodeURIComponent(montant) + '&frais_applique=' + encodeURIComponent(currentFrais) + '&beneficiaire=' + encodeURIComponent(beneficiaire) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                throw new Error('Réponse invalide du serveur');
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            alert('Transfert effectué avec succès! Nouveau solde: ' + data.nouveau_solde + ' Ar');
            document.getElementById('transfert-form').reset();
            document.getElementById('frais-display').textContent = 'Frais : -';
            currentFrais = null;
        } else {
            alert('Erreur: ' + (data.error || 'Erreur inconnue'));
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('Erreur lors de l\'enregistrement du transfert: ' + err.message);
    });
});
</script>
</body>
</html>
