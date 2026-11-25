# Khotwa – Plateforme de prévention des addictions

> **Ose le premier pas.**

## Overview
Projet réalisé dans le cadre de **Projet Technologies Web 2A** à **Esprit School of Engineering – Tunisia** (année universitaire 2026-2027).
Khotwa aide les jeunes à **prévenir**, **s'orienter** et **être accompagnés** face aux addictions (alcool, drogues), avec anonymat/pseudonymat et sans jugement.
Les informations et résultats sont **indicatifs** et ne remplacent pas un avis médical.

## Features
- **Module User** (fait) : inscription / connexion / sessions sécurisées, rôles (`user`, `professional`, `admin`), CRUD des utilisateurs, profil, validation des professionnels, journal d'activité.
- M1 Smart Orientation · M2 Medical Network · M3 Recovery Journey · M4 Emotional & Social Support · M5 Prevention & Education · M6 Awareness & Community (à compléter par chaque membre).

## Tech Stack
- **Frontend** : HTML5, CSS3, JavaScript (vanilla)
- **Backend** : PHP 8+ (architecture MVC), PDO
- **Base de données** : MySQL / MariaDB (XAMPP)

## Architecture (MVC)
```
khotwa/
├── index.php            Point d'entrée + routeur (?action=...)
├── config/              database.php (PDO Singleton), config.php, helpers.php
├── models/              1 Model par entité (attributs privés + getters/setters)
├── controllers/         1 Controller par entité (logique métier + SQL préparé)
├── views/
│   ├── frontoffice/     includes/ (head, spinner, navbar, footer, scripts) + pages
│   └── backoffice/      includes/ (head, sidebar, footer, scripts) + pages
├── css/  js/  img/  lib/
├── database/khotwa.sql  Structure + données de démonstration
└── storage/             mails.log (mails simulés en local)
```
Règle : **Model** = données seulement · **Controller** = toute la logique · **View** = affichage seulement (`e()` / `htmlspecialchars` sur chaque sortie).

## Getting Started
1. Installer **XAMPP** (PHP ≥ 8.0) et démarrer **Apache** + **MySQL**.
2. Cloner le dépôt dans `C:\xampp\htdocs\` :
   `git clone <url-du-depot> khotwa-final`
3. Ouvrir **phpMyAdmin** → onglet **SQL** → coller le contenu de `database/khotwa.sql` → **Exécuter**.
4. Vérifier `config/database.php` (hôte, utilisateur, mot de passe MySQL) et `BASE_URL` dans `config/config.php`.
5. Ouvrir <http://localhost/khotwa-final/index.php>

**Comptes de démonstration** (mot de passe : `Khotwa@2026`) :
| Rôle | E-mail |
|---|---|
| Administrateur | admin@khotwa.tn |
| Utilisateur | sami@khotwa.tn |
| Professionnel validé | sonia@khotwa.tn |
| Professionnel en attente | karim@khotwa.tn |

> Mot de passe oublié en local : aucun mail n'est envoyé, le lien s'affiche à l'écran (`APP_DEBUG = true`) et est copié dans `storage/mails.log`.

## Module User – fonctionnalités métier
**Basic** : recherche · filtre par rôle et par état · tri · pagination · blocage/déblocage · changement de rôle · validation/refus des professionnels · profil · changement de mot de passe · messages clairs · redirection selon le rôle.
**Avancées** : mot de passe oublié (jeton à usage unique, 1 h) · protection brute force (5 échecs → 15 min) · historique des connexions + journal d'activité · statistiques (inscriptions, connexions) · photo de profil sécurisée · export CSV · suppression de son propre compte (RGPD) · anti-robot · déconnexion immédiate d'un compte bloqué.

## Sécurité
| Risque | Protection |
|---|---|
| Injection SQL | Requêtes préparées PDO (`EMULATE_PREPARES = false`), listes blanches pour le tri |
| Mots de passe | `password_hash()` / `password_verify()` |
| XSS | `e()` en sortie, validation en entrée, Content-Security-Policy |
| CSRF | Jeton par session sur **tous** les formulaires POST |
| Fixation / vol de session | `session_regenerate_id`, cookie HttpOnly + SameSite, expiration 30 min |
| Élévation de privilèges | Rôle jamais choisi par le formulaire public ; rôle relu en base à chaque page ; `requireRole()` |
| Brute force | Verrouillage temporaire du compte |
| Upload | Type MIME réel, taille ≤ 2 Mo, nom aléatoire, exécution PHP interdite dans `img/uploads/` |

## Ajouter un module (pour les autres membres)
1. Branche : `git checkout dev && git pull && git checkout -b feature/module-xxx`
2. Compléter `models/XxxModel.php` et `controllers/XxxController.php` (fichiers vides fournis).
3. Ajouter vos tables à la fin de `database/khotwa.sql`.
4. Ajouter vos pages dans `views/frontoffice/<module>/` et `views/backoffice/<module>/` **en utilisant les includes** (voir `views/frontoffice/user/login.php` comme modèle).
5. Ajouter `require_once` + `case 'mon_action':` dans `index.php` ; protéger avec `UserController::requireAuth()` / `requireRole('admin')`.
6. Commits courts et fréquents, puis Pull Request vers `dev`.

## Contributors
- … – Module User
- … – M1 · … – M2 · … – M3 · … – M4 · … – M5 · … – M6

## Academic Context
Developed at **Esprit School of Engineering – Tunisia** · Projet Technologies Web 2A · 2026-2027

## Acknowledgments
Merci à nos tuteurs pour leur accompagnement.

## Version intégrée
Base isolée : `khotwa_final`. Importer le SQL fourni dans phpMyAdmin. Les modules hors User restent des emplacements à développer. Le design de la foundation a été adapté au routeur MVC original.
