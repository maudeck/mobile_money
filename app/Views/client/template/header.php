<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre ?? 'Mobile Money') ?></title>
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>

<body>
    <div class="client-shell">
        <header class="app-header">
            <div class="brand">
                <span class="mark"><svg class="icon icon-sm" viewBox="0 0 24 24" stroke="var(--navy-700)">
                        <rect x="2" y="6" width="20" height="13" rx="3" />
                        <path d="M2 10h20" />
                    </svg></span>
                Mobile Money
            </div>
            <a href="<?= site_url('logout') ?>" class="icon-btn" aria-label="Deconnexion" title="Deconnexion">
                <svg class="icon icon-sm" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                    <path d="M16 17l5-5-5-5" />
                    <path d="M21 12H9" />
                </svg>
            </a>
        </header>

        <main class="app-main">