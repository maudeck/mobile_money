<?php
$titre = 'Tableau de bord — Mobile Money';
$topbarTitle = 'Tableau de bord';
$activeNav = 'admin/dashboard';
?>
<?= $this->extend('admin/template/admin_template') ?>
<?php $this->section('content'); ?>
<p style="max-width:640px;">Bienvenue sur votre espace d'administration. Gerez les operateurs, les types d'operations et consultez les statistiques du reseau.</p>

<div class="stat-grid">
    <a href="<?= site_url('operateur') ?>" class="stat-card">
        <div class="stat-icon"><svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.7 1.7 0 00.34 1.87 2 2 0 11-2.83 2.83 1.7 1.7 0 00-1.87-.34 1.7 1.7 0 00-1.04 1.56V21a2 2 0 11-4 0v-.09a1.7 1.7 0 00-1.04-1.56 1.7 1.7 0 00-1.87.34 2 2 0 11-2.83-2.83 1.7 1.7 0 00.34-1.87 1.7 1.7 0 00-1.56-1.04H3a2 2 0 110-4h.09a1.7 1.7 0 001.56-1.04 1.7 1.7 0 00-.34-1.87 2 2 0 112.83-2.83 1.7 1.7 0 001.87.34H9a1.7 1.7 0 001.04-1.56V3a2 2 0 114 0v.09a1.7 1.7 0 001.04 1.56 1.7 1.7 0 001.87-.34 2 2 0 112.83 2.83 1.7 1.7 0 00-.34 1.87V9a1.7 1.7 0 001.56 1.04H21a2 2 0 110 4h-.09a1.7 1.7 0 00-1.56 1.04z" />
            </svg></div>
        <h3>Gestion des operateurs</h3>
        <p>Configurer les prefixes et les operateurs de telephonie.</p>
    </a>

     <a href="<?= site_url('type-operation') ?>" class="stat-card accent">
         <div class="stat-icon"><svg class="icon" viewBox="0 0 24 24">
                 <path d="M20.6 12L12 20.6a2 2 0 01-2.8 0L3.4 14.8a2 2 0 010-2.8L12 3.4H20a1 1 0 011 1v8z" />
                 <circle cx="16.5" cy="7.5" r="1.2" fill="currentColor" stroke="none" />
             </svg></div>
         <h3>Types d'operations</h3>
         <p>Gerer les types d'operations et les tranches de frais.</p>
     </a>

     <a href="<?= site_url('commission') ?>" class="stat-card accent">
         <div class="stat-icon"><svg class="icon" viewBox="0 0 24 24">
                 <line x1="18" y1="20" x2="18" y2="10" />
                 <line x1="12" y1="20" x2="12" y2="4" />
                 <line x1="6" y1="20" x2="6" y2="14" />
             </svg></div>
         <h3>Commissions</h3>
         <p>Configurer les commissions par operateur pour les transferts.</p>
     </a>

     <a href="<?= site_url('admin/gains') ?>" class="stat-card">
        <div class="stat-icon"><svg class="icon" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10" />
                <line x1="12" y1="20" x2="12" y2="4" />
                <line x1="6" y1="20" x2="6" y2="14" />
            </svg></div>
        <h3>Gains par frais</h3>
        <p>Consulter les gains generes par les frais de retrait et transfert.</p>
    </a>

    <a href="<?= site_url('admin/clients') ?>" class="stat-card accent">
        <div class="stat-icon"><svg class="icon" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 00-3-3.87" />
                <path d="M16 3.13a4 4 0 010 7.75" />
            </svg></div>
        <h3>Comptes clients</h3>
        <p>Voir la situation de tous les comptes clients.</p>
    </a>
</div>
<?php $this->endSection(); ?>
