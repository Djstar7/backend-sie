# SIE - Système de Gestion de Demandes de Visa

> Une application de gestion de visa complète développée avec Laravel(API)
> ![Logo SIE](public/logo.png) > [![Laravel](https://img.shields.io/badge/Laravel-12.x-orange)](https://laravel.com) > [![PHP](https://img.shields.io/badge/PHP-8.2%2B-8892BF.svg)](https://php.net) > [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT) > [![Version](https://img.shields.io/github/v/release/votre-organisation/votre-repo?include_prereleases)](https://github.com/votre-organisation/votre-repo/releases)

## 📋 Table des matières

- [À propos du projet](#-à-propos-du-projet)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [API](#-api)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Sécurité](#-sécurité)
- [Contribution](#-contribution)
- [Documentation](#-documentation)
- [Roadmap](#-roadmap)
- [FAQ](#-faq)
- [Support](#-support)
- [Licence](#-licence)
- [Auteur](#-auteur)

## 🚀 À propos du projet

SIE (Service d'Immigration de Éstuaire) est une application web de gestion de visas qui simplifie et centralise le processus de demande, de traitement et de suivi des visas. Le système permet aux utilisateurs de soumettre des demandes de visas, de télécharger les documents requis, de suivre l'état de leurs demandes, et permet aux agents de traiter efficacement les demandes et de gérer les communications.

Le projet vise à moderniser les processus de gestion des visas en offrant une solution entièrement numérique et sécurisée, améliorant ainsi l'expérience utilisateur et l'efficacité opérationnelle.

## ✨ Fonctionnalités

### Gestion des utilisateurs

- [x] Inscription et authentification sécurisées
- [x] Système de rôles (Administrateur, Agent, Client/Custom)
- [x] Gestion de profils personnels complets
- [x] Réinitialisation de mot de passe sécurisée
- [x] Validation par e-mail

### Gestion des visas

- [x] Catalogue de pays et de types de visas
- [x] Création et suivi des demandes de visas
- [x] Association des documents requis
- [x] Gestion des rendez-vous
- [x] Calcul des frais de visa selon les catégories

### Paiements

- [x] Système de gestion des paiements
- [x] Tracking des transactions en temps réel
- [x] Génération de reçus
- [x] Intégration avec passerelles de paiement
- [x] Historique des paiements

### Communication

- [x] Système de messagerie interne
- [x] Notifications en temps réel
- [x] Base de connaissances (FAQ)
- [x] Centre d'aide

### Autres fonctionnalités

- [x] Journalisation des actions
- [x] Gestion des documents
- [x] Interface API RESTful
- [x] Export de données
- [x] Interface responsive

## 🛠 Technologies utilisées

### Backend

- [Laravel](https://laravel.com) 12.x - Framework PHP moderne
- [PHP](https://php.net) 8.2+ - Langage de programmation principal
- [MySQL](https://mysql.com) ou [PostgreSQL](https://postgresql.org) - Système de gestion de base de données
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - Authentification API sécurisée
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Gestion fine des rôles et permissions

### Dépendances principales

- `guzzlehttp/guzzle` - Client HTTP pour les requêtes externes
- `laravel/sanctum` - Authentification par jetons API
- `spatie/laravel-permission` - Gestion avancée des rôles et permissions
- `fakerphp/faker` - Génération de données factices pour tests
- `laravel/tinker` - Shell interactif pour le développement
- `laravel/pint` - Outil de formatage de code
- `laravel/sail` - Environnement de développement Docker

## 📋 Prérequis

Avant d'installer le projet, assurez-vous d'avoir installé :

- PHP >= 8.2
- Composer (gestionnaire de dépendances PHP)
- MySQL, PostgreSQL ou SQLite
- Node.js et NPM (optionnel si gestion des assets frontend)
- Git
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Djstar7/backend-sie.git
cd votre-repo
```

### 2. Installer les dépendances

```bash
# Installer les dépendances PHP via Composer
composer install

# Installer les dépendances Node.js (optionnel)
npm install
```

### 3. Configurer l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer la base de données

Modifier le fichier `.env` avec vos paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votre_base_de_donnees
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### 5. Exécuter les migrations et seeds

```bash
# Exécuter les migrations de la base de données
php artisan migrate --seed
```

### 6. Lancer l'application

```bash
# Lancer le serveur de développement
php artisan serve

# L'application sera accessible à l'adresse: http://127.0.0.1:8000
```

## ⚙ Configuration

### Variables d'environnement principales

| Variable         | Description                            | Exemple                |
| ---------------- | -------------------------------------- | ---------------------- |
| `APP_NAME`       | Nom de l'application                   | SIE Visa               |
| `APP_ENV`        | Environnement (local, production)      | local                  |
| `APP_KEY`        | Clé de chiffrement de l'application    | auto-généré            |
| `APP_URL`        | URL de l'application                   | http://localhost       |
| `DB_*`           | Configuration de la base de données    | voir ci-dessus         |
| `MAIL_*`         | Configuration du serveur de messagerie | SMTP                   |
| `SANCTUM_*`      | Configuration de Sanctum               | voir documentation     |
| `CACHE_DRIVER`   | Driver de cache                        | redis, memcached, file |
| `SESSION_DRIVER` | Driver de session                      | file, cookie, database |

### Configuration des services externes

#### Mail

Configurer les variables `MAIL_*` dans le fichier `.env` pour activer les notifications par e-mail.

#### Services de paiement

Configurer les variables de votre passerelle de paiement dans le fichier `.env`.

## 🎯 Utilisation

### Commandes utiles

```bash
# Lancer l'application
php artisan serve

# Lancer les tests
composer test

# Lancer les tests avec couverture
php artisan test --coverage

# Migrer la base de données
php artisan migrate

# Effectuer une migration avec rollback
php artisan migrate:rollback

# Générer des données factices
php artisan db:seed

# Effacer et regénérer la base de données
php artisan migrate:refresh --seed

# Compiler les assets
npm run build

# Compiler les assets en mode développement
npm run dev

# Formater le code
./vendor/bin/pint

# Voir l'état des migrations
php artisan migrate:status
```

### Accéder à l'interface

L'application sera accessible à l'adresse: `http://127.0.0.1:8000`

### Accéder à l'interface d'administration

L'interface d'administration est accessible via l'authentification avec un compte administrateur.

## 📁 Structure du projet

```
backend/
├── app/                    # Code source principal
│   ├── Events/             # Événements du système
│   ├── Http/               # Contrôleurs, middleware, etc.
│   │   ├── Controllers/    # Contrôleurs API
│   │   ├── Requests/       # Requêtes de validation
│   │   └── Resources/      # Ressources API
│   ├── Listeners/          # Écouteurs d'événements
│   ├── Models/             # Modèles Eloquent
│   ├── Notifications/      # Notifications système
│   ├── Providers/          # Providers du service
│   └── Services/           # Services métier
├── bootstrap/             # Fichiers de démarrage
├── config/                # Fichiers de configuration
├── database/              # Migrations, seeds, factories
│   ├── factories/         # Factories pour les tests
│   ├── migrations/        # Fichiers de migration
│   └── seeders/           # Fichiers de seeds
├── public/                # Fichiers accessibles publiquement (logo.png ici)
├── resources/             # Ressources non PHP
├── routes/                # Fichiers de routes
├── storage/               # Fichiers stockés dynamiquement
├── tests/                 # Fichiers de tests
├── vendor/                # Dépendances via Composer
├── .env                   # Fichier d'environnement
├── artisan                # CLI Laravel
├── composer.json          # Dépendances PHP
└── README.md              # Ce fichier
```

## 🌐 API

L'application expose une API RESTful pour l'interaction avec les données.

### Authentification

Toutes les routes API protégées nécessitent un jeton d'authentification Sanctum dans l'en-tête `Authorization: Bearer {token}`.

### Endpoints principaux

#### Authentification

| Méthode | Chemin               | Description                  |
| ------- | -------------------- | ---------------------------- |
| `POST`  | `/api/auth/register` | Inscription d'un utilisateur |
| `POST`  | `/api/auth/login`    | Connexion d'un utilisateur   |
| `POST`  | `/api/auth/logout`   | Déconnexion d'un utilisateur |

#### Gestion des utilisateurs

| Méthode  | Chemin                  | Description                    |
| -------- | ----------------------- | ------------------------------ |
| `GET`    | `/api/user`             | Liste des utilisateurs (admin) |
| `GET`    | `/api/user/show/{id}`   | Détails d'un utilisateur       |
| `PUT`    | `/api/user/update/{id}` | Mise à jour d'un utilisateur   |
| `DELETE` | `/api/user/delete/{id}` | Suppression d'un utilisateur   |

#### Gestion des visas

| Méthode  | Chemin                  | Description                                  |
| -------- | ----------------------- | -------------------------------------------- |
| `GET`    | `/api/visa`             | Liste des combinaisons pays/types de visas   |
| `POST`   | `/api/visa/store`       | Création d'une combinaison pays/type de visa |
| `PUT`    | `/api/visa/update/{id}` | Mise à jour d'une combinaison                |
| `DELETE` | `/api/visa/delete/{id}` | Suppression d'une combinaison                |

#### Gestion des demandes de visas

| Méthode | Chemin                         | Description                |
| ------- | ------------------------------ | -------------------------- |
| `GET`   | `/api/visarequest`             | Liste des demandes (admin) |
| `POST`  | `/api/visarequest/store`       | Création d'une demande     |
| `GET`   | `/api/visarequest/show/{id}`   | Détails d'une demande      |
| `PUT`   | `/api/visarequest/update/{id}` | Mise à jour du statut      |

#### Gestion des documents

| Méthode  | Chemin                      | Description               |
| -------- | --------------------------- | ------------------------- |
| `GET`    | `/api/document`             | Liste des documents       |
| `POST`   | `/api/document/store`       | Upload d'un document      |
| `PUT`    | `/api/document/update/{id}` | Mise à jour d'un document |
| `DELETE` | `/api/document/delete/{id}` | Suppression d'un document |

#### Gestion des paiements

| Méthode | Chemin                   | Description            |
| ------- | ------------------------ | ---------------------- |
| `GET`   | `/api/payment`           | Liste des paiements    |
| `POST`  | `/api/payment/store`     | Création d'un paiement |
| `GET`   | `/api/payment/show/{id}` | Détails d'un paiement  |

#### Gestion des notifications

| Méthode | Chemin                          | Description                    |
| ------- | ------------------------------- | ------------------------------ |
| `GET`   | `/api/notification`             | Liste des notifications        |
| `POST`  | `/api/notification/store`       | Création d'une notification    |
| `PUT`   | `/api/notification/update/{id}` | Mise à jour d'une notification |

La documentation complète de l'API est disponible à `/api/documentation`.

## 🧪 Tests

L'application utilise PHPUnit pour les tests automatisés.

### Types de tests

- **Tests unitaires** : Vérifient le bon fonctionnement des composants individuels
- **Tests fonctionnels** : Vérifient le bon fonctionnement des fonctionnalités
- **Tests d'API** : Vérifient les endpoints API
- **Tests de sécurité** : Vérifient les mécanismes de sécurité

### Exécuter les tests

```bash
# Exécuter tous les tests
composer test

# Exécuter les tests avec couverture de code
composer test -- --coverage

# Exécuter seulement les tests unitaires
php artisan test --testsuite=Unit

# Exécuter seulement les tests fonctionnels
php artisan test --testsuite=Feature

# Exécuter un test spécifique
php artisan test tests/Feature/ExampleTest.php
```

### Couverture de code

La couverture de code est mesurée pour s'assurer que la plupart des fonctionnalités sont testées.

## 🚀 Déploiement

### Environnement de production

1. Mettre à jour les variables d'environnement pour la production
2. Désactiver le mode debug
3. Optimiser l'application

```bash
# Optimiser l'application pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimiser l'autoloader
composer install --optimize-autoloader --no-dev
```

### Configuration serveur

- PHP 8.2+ avec extensions requises
- Web server (Apache, Nginx)
- Base de données MySQL/PostgreSQL
- Configuration SSL pour HTTPS
- Configuration des répertoires pour les uploads

## 🔒 Sécurité

### Mesures de sécurité implémentées

- **Authentification sécurisée** : Utilisation de jetons API Sanctum avec expiration
- **Contrôle d'accès basé sur les rôles** : Gestion fine des permissions avec Spatie
- **Validation des entrées** : Validation stricte des données via Form Requests
- **Protection CSRF** : Mécanismes Laravel de protection
- **Hachage des mots de passe** : Bcrypt avec coûts adaptés
- **Journalisation des actions critiques** : Auditabilité des opérations sensibles
- **Protection contre les injections SQL** : Utilisation de requêtes préparées
- **Validation des fichiers uploadés** : Vérification des types, tailles

### Signaler une vulnérabilité

Si vous découvrez une vulnérabilité de sécurité, veuillez nous contacter directement à [votre-email-de-sécurité@entreprise.com] au lieu d'ouvrir un problème public.

## 🤝 Contribution

Les contributions sont les bienvenues! Pour contribuer:

### 1. Forker le projet

Forker le dépôt sur GitHub.

### 2. Créer une branche pour votre fonctionnalité

```bash
git checkout -b feature/NouvelleFonctionnalite
```

### 3. Committer vos changements

Suivre les conventions de commit conventionnels.

### 4. Pousser vers la branche

```bash
git push origin feature/NouvelleFonctionnalite
```

### 5. Ouvrir une Pull Request

Expliquer clairement les changements apportés.

### Normes de codage

- Respecter les standards PSR-12
- Écrire des tests pour toute nouvelle fonctionnalité
- Mettre à jour la documentation si nécessaire
- Commentaires en anglais
- Code clair et lisible

### Processus de review

Tous les PR sont revus par au moins un autre contributeur avant fusion.

## 📚 Documentation

### Documentation interne

- [Guide d'installation](docs/installation.md)
- [Guide d'utilisation](docs/utilisation.md)
- [API](docs/api.md)
- [Conventions de codage](docs/codage.md)
- [Architecture](docs/architecture.md)

### Documentation externe

- [Documentation Laravel](https://laravel.com/docs)
- [Documentation Laravel Sanctum](https://laravel.com/docs/sanctum)

## 🗺 Roadmap

### Version 1.0.0 - Terminée

- [x] Système d'authentification complet
- [x] Gestion des utilisateurs
- [x] Gestion des visas et types de visas
- [x] Système de demande de visas
- [x] Gestion des documents
- [x] Système de paiements

### Version 1.1.0 - En développement

- [ ] Amélioration de l'interface administrateur
- [ ] Notifications push
- [ ] Intégration avec services tiers
- [ ] Amélioration de la sécurité

### Version 1.2.0 - Planifiée

- [ ] Application mobile
- [ ] Chatbot d'assistance
- [ ] Analyse statistique
- [ ] Traduction multilingue

## ❓ FAQ

### Questions fréquentes

**Q: Comment réinitialiser mon mot de passe ?**
R: Utilisez la fonction "Mot de passe oublié" sur la page de connexion.

**Q: Quels sont les rôles disponibles dans l'application ?**
R: Trois rôles principaux sont disponibles : Admin, Agent et Client (Custom).

**Q: Comment ajouter un nouveau type de visa ?**
R: Les administrateurs peuvent ajouter de nouveaux types de visas via l'interface d'administration.

**Q: Les données sont-elles sauvegardées ?**
R: Oui, des sauvegardes automatiques sont effectuées régulièrement.

## 🆘 Support

### Besoin d'aide ?

- 📧 **Email de support**: [support@votre-entreprise.com](mailto:support@votre-entreprise.com)
- 📞 **Téléphone**: +[numéro de téléphone]
- 💬 **Chat en direct**: Disponible sur le site
- 📝 **Documentation**: Voir le répertoire `docs/`
- 🐛 **Issues**: Pour signaler des bugs, utilisez le [système de suivi des issues GitHub](https://github.com/votre-organisation/votre-repo/issues)

### Horaires de support

- 🗓 **Lundi à Vendredi**: 9h00 - 18h00 (heure locale)
- ⏰ **Support d'urgence**: 24/7 pour les problèmes critiques de sécurité

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

```
MIT License

Copyright (c) 2025 Votre Organisation

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 👤 Auteur

- 📧 Email: infodjstar7@gmail.com
- 🔗 GitHub: [https://github.com/Djstar7](https://github.com/votre-compte-github)
- 💼 LinkedIn: [https://linkedin.com/in/votre-profil](https://linkedin.com/in/votre-profil)

### Équipe de développement

- 👨‍💻 **Développeur principal**: DJUNE STAEL BLAIRIO - infodjstar7@gmail.com
- 👨‍💻 **Architecte**: DJUNE STAEL BLAIRIO - Dinfodjstar7@gmail.com
- 👩‍🎨 **Designer UI/UX**: DJUNE STAEL BLAIRIO - Dinfodjstar7@gmail.com
- 🧑‍🔧 **DevOps**: DJUNE STAEL BLAIRIO - Dinfodjstar7@gmail.com

## 🙏 Remerciements

- Laravel Framework pour sa puissance et sa flexibilité
- La communauté open-source pour les composants utilisés
- Les contributeurs qui améliorent constamment ce projet
- Les testeurs et utilisateurs qui fournissent des retours précieux

---

> 📌 **Note**: Ce projet fait partie de la suite SIE (Service d'Immigration de Estuaire) et est conçu pour être utilisé en complément avec l'application frontend correspondante.

> 💡 **Conseil**: Consultez régulièrement les [releases](https://github.com/votre-organisation/votre-repo/releases) pour les mises à jour et les nouvelles fonctionnalités.

> 🔗 **Liens utiles**:
>
> - [Documentation complète](https://votre-site.com/docs)
> - [Site officiel](https://votre-site.com)
> - [GitHub Repository](https://github.com/votre-organisation/votre-repo)
