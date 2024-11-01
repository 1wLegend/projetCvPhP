# CV Portfolio

Site Web permettant la création de portfolios en ligne ainsi que de CV personnalisés.

## Features

- Télécharge les CV de n'importe quel utilisateur
- Modifie les CV et profils en temps réel
- Consulte les profils de chaque utilisateur

## Git
### Cloner le repo

Pour utiliser ce projet, il vous faut le cloner avec la commande suivante :

```bash
git clone https://github.com/1wLegend/projetCvPhP.git
```

Ou bien vous pouvez simplement télécharger le fichier, mais il est recommandé de cloner le repo pour accéder plus rapidement aux mises à jour grâce à `git pull`, qui vous permet de récupérer les nouvelles fonctionnalités ajoutées au repo.

---

## Installation de Composer et de FPDF

### Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :
- **PHP** (version 7.2 ou supérieure). Vérifiez la version installée avec :
  ```bash
  php --version
  ```

### Étape 1 : Installation de Composer

#### Sous Windows

1. Téléchargez l'installateur de Composer depuis [getcomposer.org](https://getcomposer.org/).
2. Exécutez le fichier téléchargé et suivez les instructions.
3. Une fois installé, ouvrez un terminal (CMD ou PowerShell) et tapez la commande suivante pour vérifier que Composer est bien installé :
   ```bash
   composer --version
   ```

#### Sous macOS ou Linux

1. Ouvrez le terminal et exécutez la commande suivante pour télécharger le script d'installation de Composer :
   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   ```
2. Vérifiez l'intégrité du script téléchargé en exécutant cette commande :
   ```bash
   php -r "if (hash_file('sha384', 'composer-setup.php') === 'HASH_DU_SCRIPT') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
   ```
   > Remplacez `HASH_DU_SCRIPT` par le hash SHA-384 disponible sur la [page de téléchargement officielle de Composer](https://getcomposer.org/download/).
3. Installez Composer localement dans le répertoire courant :
   ```bash
   php composer-setup.php
   ```
4. Supprimez le script d'installation :
   ```bash
   php -r "unlink('composer-setup.php');"
   ```
5. Vérifiez que Composer est bien installé :
   ```bash
   composer --version
   ```

### Étape 2 : Installer la dépendance FPDF

1. Dans le dossier racine du projet, exécutez la commande suivante pour ajouter FPDF en tant que dépendance :
   ```bash
   composer require setasign/fpdf
   ```
   - Cela téléchargera et installera la dernière version de FPDF dans votre projet.
   - Un répertoire `vendor` sera créé pour stocker les bibliothèques installées. Le fichier `composer.json` sera également mis à jour.

2. Déplacez le fichier `vendor` dans un dossier nommé `PhP-Files`, situé dans `app/includes/PhP-Files`.

---

## Docker

### Installer Docker

1. **Téléchargez et installez Docker :**
   - Allez sur le site officiel de [Docker](https://www.docker.com/products/docker-desktop) et téléchargez Docker Desktop pour votre système d'exploitation (Windows, macOS ou Linux).
   - Suivez les instructions d'installation fournies par Docker.

2. **Vérifiez que Docker est installé correctement :**
   - Ouvrez un terminal et exécutez la commande suivante :
     ```bash
     docker --version
     ```
   - Vous devriez voir la version de Docker affichée, confirmant son installation.

### Installer Docker Compose

Docker Compose est généralement inclus avec Docker Desktop sur Windows et macOS. Si vous utilisez Linux ou si `docker-compose` n'est pas disponible, suivez ces étapes :

1. **Vérifiez si Docker Compose est déjà installé :**
   ```bash
   docker-compose --version
   ```

2. **Si nécessaire, installez Docker Compose sur Linux :**
   - Suivez les instructions officielles de Docker Compose pour Linux sur la [documentation de Docker](https://docs.docker.com/compose/install/).

### Utiliser Docker

1. Rendez-vous dans le dossier `Docker`, situé à `projetCvPhP/Docker/`, et lancez la commande suivante dans le terminal :
   ```bash
   docker compose up -d
   ```

2. Accédez au site en ouvrant `http://localhost/` dans votre navigateur.

3. **Arrêter les conteneurs Docker :**
   - Une fois tous vos tests réalisés, arrêtez Docker avec la commande suivante :
     ```bash
     docker compose down
     ```
   - Pour relancer le site, exécutez de nouveau `docker compose up -d`.

---

## Version du Projet

> **DISCLAIMER** : Ce projet est en cours de développement et n'est pas dans sa version finale. Des mises à jour sont prévues dans les prochains jours. Veuillez aussi noté que suivant la version utilisé, lorsque vous vous connecté, il faudra faire un f5 suite a une erreur non géré.