
# Cv Portfolio

Site Web permettant la création de portfolio en ligne ainsi que CV personnalisée.




## Features

- Télécharge les CV de n'importe quel utilsateur
- Modifie les en temps réels ainsi que ton profile
- Vois les profiles de chaque utilisateurs


## Installation de Composer et de FPDF
### Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

PHP (version 7.2 ou supérieure avec php --version)

### Étape 1 : Installation de Composer Sous Windows
Téléchargez l'installateur de Composer depuis getcomposer.org.

Exécutez le fichier téléchargé et suivez les étapes de l'installation.

Une fois installé, ouvrez une fenêtre de terminal (CMD ou PowerShell) et tapez la commande suivante pour vérifier que Composer est bien installé :

```bash
composer --version
```

Si Composer est installé correctement, vous verrez la version de Composer affichée.

### Sous macOS ou Linux
Ouvrez votre terminal et exécutez la commande suivante pour télécharger le script d'installation de Composer :

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```
Vérifiez l'intégrité du script téléchargé en exécutant cette commande :

```bash
php -r "if (hash_file('sha384', 'composer-setup.php') === 'HASH_DU_SCRIPT') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```
Remplacez HASH_DU_SCRIPT par la dernière version du hash SHA-384 disponible sur la page officielle de Composer.

Si le script est valide, exécutez la commande suivante pour installer Composer localement dans le répertoire courant :

```bash
php composer-setup.php
```
Supprimez le script d'installation :

```bash
php -r "unlink('composer-setup.php');"
```
Vérifiez que Composer est bien installé :

```bash
composer --version
```
Si tout s'est bien passé, vous verrez la version de Composer affichée.

### Étape 3 : Installer la dépendance FPDF
Ajoutez FPDF à votre projet en utilisant la commande suivante :

```bash
composer require setasign/fpdf
```
Cela téléchargera et installera la dernière version de FPDF dans votre projet.

Vous verrez un répertoire vendor créé, où Composer stocke les bibliothèques installées. Le fichier composer.json sera mis à jour pour inclure FPDF comme dépendance du projet.
Veuillez déplacé le fichier dans un Fichier nommé "PhP-Files" situé dans Includes (app/includes/PhP-Files) avec vendor ainsi que les composers.

## VERSION DU PROJET
### DISCLAIMER

Ce n'est pas la version FINALE du projet. Il y aura des updates dans les prochains jours.