# Projet ECF – Application Vite & Gourmand

Titre professionnel : Développeur Web et Web Mobile

## Présentation

Vite & Gourmand est une application web développée dans le cadre de l’ECF.

Elle permet :

- Consultation des menus
- Filtrage dynamique
- Création de compte
- Connexion sécurisée
- Passage de commande avec calcul automatique des règles métier
- Calcul détaillé du prix avant validation
- Suivi des commandes utilisateur
- Gestion du stock
- Historique des statuts

---

## Stack technique

Front-end :
- HTML5
- CSS3
- JavaScript

Back-end :
- PHP 8 (architecture MVC simplifiée)
- PDO (requêtes préparées sécurisées)

Base de données relationnelle :
- MySQL (phpMyAdmin)

Base de données NoSQL :
- MongoDB (prévue pour statistiques administrateur)

Serveur local :
- XAMPP

---

## Installation en local

1. Cloner le dépôt :

```bash
git clone https://github.com/Armando-dev78/Projet-ECF-Application-Vite-et-Gourmand.git

2. Copier le projet dans le dossier :

```text
xampp/htdocs/
```

3. Démarrer Apache et MySQL depuis XAMPP.

4. Ouvrir phpMyAdmin.

5. Créer une base de données :

```sql
CREATE DATABASE vite_gourmand;
```

6. Importer les fichiers :

```text
sql/create_tables.sql
sql/insert_data.sql
```

7. Vérifier les paramètres de connexion dans :

```text
config/Database.php
```

8. Accéder à l'application :

```text
http://localhost/vite_gourmand/public
```

---

## Comptes de démonstration

Administrateur :

Email : jose@vitegourmand.fr

Mot de passe : AdminTest123!

Employé :

Email : julie@vitegourmand.fr

Mot de passe : AdminTest1234!

Utilisateur :

Email : client@test.com

Mot de passe : AdminTest12345!

---

## Fonctionnalités implémentées

### Visiteur

- Consultation des menus
- Filtrage dynamique AJAX
- Création de compte
- Connexion

### Utilisateur

- Commande d'un menu
- Calcul automatique du prix
- Réduction automatique
- Gestion de la livraison
- Historique des commandes
- Suivi du statut

### Employé

- Consultation des commandes
- Modification du statut des commandes
- Consultation de l'historique

### Administrateur

- Tableau de bord
- Statistiques des commandes
- Chiffre d'affaires
- Menu le plus commandé

---

## Sécurité

- Hachage des mots de passe avec password_hash()
- Vérification avec password_verify()
- Requêtes préparées PDO
- Protection CSRF
- Gestion des sessions
- Contrôle des rôles

---

## Auteur

Projet réalisé dans le cadre de l'ECF du titre professionnel Développeur Web et Web Mobile.