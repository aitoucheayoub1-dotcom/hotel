# Système de Réservation d'Hôtel

Application web dynamique de réservation d'hôtel développée en **PHP**, **MySQL (PDO)**, **HTML5** et **CSS3** dans le cadre d'un projet en binôme (Ayoub Aitouche & Fateh Mamri).

## Fonctionnalités principales

- Consultation des pages du site (`index.php`, `accueil.php`, `chambre.php`).
- Création et gestion des réservations (`reservation.php`) avec validation côté serveur.
- Authentification des utilisateurs (connexion / déconnexion avec `password_hash()` et gestion des sessions).
- Gestion des rôles (`admin` et `client`) et protection des pages sensibles via `includes/fonctions.php`.
- Opérations CRUD sur les réservations avec requêtes préparées PDO.

## Structure du projet

- `index.php`, `accueil.php`, `chambre.php`, `reservation.php` : pages principales de l'application.
- `login.php`, `logout.php` : gestion de l'authentification et de la session.
- `includes/db.php` : connexion à la base de données via PDO.
- `includes/fonctions.php` : fonctions utilitaires, authentification et contrôle d'accès par rôle.
- `includes/header.php`, `includes/footer.php` : en-tête et pied de page factorisés.
- `style.css` : feuilles de style de l'interface.
- `docs/ANALYSE.md` : documentation d'analyse du projet.

## Installation et exécution locale (XAMPP)

1. Cloner ou copier le dossier du projet dans `htdocs` (ex. `C:/xampp/htdocs/hotel`).
2. Démarrer **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.
3. Créer la base de données dans **phpMyAdmin** et importer le fichier SQL du projet (`reservation_hotel.sql`).
4. Vérifier les paramètres de connexion dans `includes/db.php`.
5. Accéder à l'application via le navigateur : `http://localhost/hotel/`.

## Rôles et accès

- **Client** : peut accéder aux pages publiques et effectuer des réservations une fois connecté.
- **Admin** : dispose des privilèges d'administration selon les règles définies dans `includes/fonctions.php`.

## Tests et versionning

- Tests manuels effectués sur les parcours de connexion, déconnexion, accès aux pages protégées et gestion des réservations.
- Version finale de l'étape taguée sous `v1.0.0`.

