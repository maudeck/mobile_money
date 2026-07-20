<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Mobile Money</title>
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>

<body class="auth-page">
    <div class="auth-card">
        <div class="auth-mark">
            <svg class="icon icon-lg" viewBox="0 0 24 24">
                <rect x="2" y="6" width="20" height="13" rx="3" />
                <path d="M2 10h20" />
                <circle cx="17" cy="14.5" r="1.4" fill="currentColor" stroke="none" />
            </svg>
        </div>
        <h3>Connexion</h3>
        <p class="auth-sub">Accedez a votre espace Mobile Money.</p>

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

        <?php if (session()->get('success')): ?>
            <div class="alert alert-success" data-autodismiss>
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5" />
                </svg>
                <span><?= session()->get('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login/auth') ?>" method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label for="telephone" class="field-label">Numero de telephone</label>
                <input type="text" class="control" id="telephone" name="telephone" placeholder="034 00 000 00" required autofocus>
            </div>
            <button type="submit" class="btn btn-accent btn-block">
                Se connecter
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>
    </div>

    <script src="<?= base_url('js/script.js') ?>"></script>
</body>

</html>