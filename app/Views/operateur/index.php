<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operateurs — Mobile Money</title>
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
</head>

<body>
    <div class="app-shell">
        <div class="sidebar-backdrop" data-sidebar-backdrop></div>
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="mark"><svg class="icon icon-sm" viewBox="0 0 24 24" stroke="var(--navy-700)">
                        <rect x="2" y="6" width="20" height="13" rx="3" />
                        <path d="M2 10h20" />
                    </svg></span>
                Mobile Money
            </div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-nav-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 11l9-7 9 7" />
                        <path d="M5 10v10h14V10" />
                    </svg>
                    Tableau de bord
                </a>
                <a href="<?= site_url('operateur') ?>" class="sidebar-nav-item active">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.7 1.7 0 00.34 1.87 2 2 0 11-2.83 2.83 1.7 1.7 0 00-1.87-.34 1.7 1.7 0 00-1.04 1.56V21a2 2 0 11-4 0v-.09a1.7 1.7 0 00-1.04-1.56 1.7 1.7 0 00-1.87.34 2 2 0 11-2.83-2.83 1.7 1.7 0 00.34-1.87 1.7 1.7 0 00-1.56-1.04H3a2 2 0 110-4h.09a1.7 1.7 0 001.56-1.04 1.7 1.7 0 00-.34-1.87 2 2 0 112.83-2.83 1.7 1.7 0 001.87.34H9a1.7 1.7 0 001.04-1.56V3a2 2 0 114 0v.09a1.7 1.7 0 001.04 1.56 1.7 1.7 0 001.87-.34 2 2 0 112.83 2.83 1.7 1.7 0 00-.34 1.87V9a1.7 1.7 0 001.56 1.04H21a2 2 0 110 4h-.09a1.7 1.7 0 00-1.56 1.04z" />
                    </svg>
                    Operateurs
                </a>
                <a href="<?= site_url('type-operation') ?>" class="sidebar-nav-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M20.6 12L12 20.6a2 2 0 01-2.8 0L3.4 14.8a2 2 0 010-2.8L12 3.4H20a1 1 0 011 1v8z" />
                        <circle cx="16.5" cy="7.5" r="1.2" fill="currentColor" stroke="none" />
                    </svg>
                    Types d'operations
                </a>
                <a href="<?= site_url('admin/gains') ?>" class="sidebar-nav-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    Gains par frais
                </a>
                <a href="<?= site_url('admin/clients') ?>" class="sidebar-nav-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87" />
                        <path d="M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    Comptes clients
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= site_url('logout') ?>" class="btn btn-outline-light">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                        <path d="M16 17l5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                    Deconnexion
                </a>
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:.9rem;">
                    <button class="sidebar-toggle" data-sidebar-toggle aria-label="Ouvrir le menu">
                        <svg class="icon" viewBox="0 0 24 24">
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <div class="topbar-title">
                        <span>Administration</span>
                        <h2>Gestion des operateurs</h2>
                    </div>
                </div>
                <a href="<?= site_url('operateur/create') ?>" class="btn btn-accent btn-sm">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Ajouter un operateur
                </a>
            </header>

            <main class="main-content">
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

                <div class="card">
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code prefixe</th>
                                    <th>Nom operateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($operateurs as $operateur): ?>
                                    <tr>
                                        <td><?= esc($operateur->id) ?></td>
                                        <td><span class="badge badge-mist"><?= esc($operateur->code_prefixe) ?></span></td>
                                        <td><?= esc($operateur->operateur_nom) ?></td>
                                        <td class="action-buttons">
                                            <a href="<?= site_url('operateur/edit/' . $operateur->id) ?>" class="btn btn-warning btn-sm">
                                                <svg class="icon icon-sm" viewBox="0 0 24 24">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z" />
                                                </svg>
                                                Modifier
                                            </a>
                                            <form action="<?= site_url('operateur/delete/' . $operateur->id) ?>" method="post" data-confirm="Etes-vous sur de vouloir supprimer cet operateur ?">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                                                        <polyline points="3 6 5 6 21 6" />
                                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                                        <path d="M10 11v6" />
                                                        <path d="M14 11v6" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($operateurs)): ?>
                                    <tr>
                                        <td colspan="4" class="empty-row text-center">Aucun operateur trouve</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="<?= base_url('js/script.js') ?>"></script>
</body>

</html>