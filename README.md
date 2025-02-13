# TodoList App

## Description

Cette application permet à l'utilisateur de se connecter, de visualiser une todolist et de récupérer les langues depuis l'API Platform.

> **Note** : Il arrive que les composants React ne s'affichent pas toujours correctement.

## Prérequis

Avant de pouvoir lancer le projet, vous devez avoir installé les dépendances suivantes :

- **Node.js** pour exécuter les commandes NPM.
- **PHP** et **Composer** pour gérer les dépendances Symfony.

## Installation

### 1. Installez les dépendances NPM

Dans le dossier du projet, exécutez la commande suivante pour installer les dépendances Node.js (par exemple, React et Tailwind CSS) :

```bash
npm install
```

### 2. Installez les dépendances PHP

Ensuite, exécutez cette commande pour installer les dépendances PHP via Composer (Symfony, API Platform, etc.) :

```bash
composer install
```
### 3. Démarrez le serveur Symfony

Pour lancer l’application Symfony et accéder à votre backend, exécutez la commande suivante :

```bash
symfony serve
```

### 4. Compilez les assets front-end

Une fois que les dépendances sont installées, vous devez compiler les assets (par exemple, React et Tailwind CSS) avec la commande suivante :

```bash
npm run dev
```

## Utilisation
- Connexion : Connectez-vous à l’application avec vos identifiants.
- Todolist : Visualisez et gérez votre todolist.
- Langues : L’application récupère les langues disponibles via l’API Platform.

## Technologie
- Symfony : Framework PHP pour le backend.
- React : Bibliothèque JavaScript pour l’interface utilisateur.
- Tailwind CSS : Framework CSS utilitaire.
- API Platform : Permet de récupérer et gérer les données de l’application via une API.
