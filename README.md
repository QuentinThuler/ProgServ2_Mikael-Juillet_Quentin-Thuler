# HEIG-VD ProgServ2 - Project Gatherly

Ce projet a pour base
[_"Programmation Serveur 2 (ProgServ2)"_](https://github.com/heig-vd-progserv-course/heig-vd-progserv2-course)
à la [HEIG-VD](https://heig-vd.ch).

## Contexte du projet

Gatherly est une application de gestion d'événements destinée aux associations.
Elle permet de présenter les événements de l'année et aux visiteurs de s'y
inscrire en renseignant leur adresse e-mail.

L'objectif est de fournir aux associations un moyen simple d'estimer le nombre
de participants à un événement afin de faciliter son organisation.

L'application vise également à centraliser les inscriptions générées par
différents canaux de communication. Elle doit permettre de mesurer l'efficacité
des campagnes promotionnelles et l'intérêt suscité par chaque événement,
notamment à des fins de reporting.

Pour plus de détails, consultez le [cahier des charges](CAHIER-DES-CHARGES.md).

## Prérequis

### Développement

Les outils suivants sont nécessaires au développement du projet :

- [Visual Studio Code](https://code.visualstudio.com/), version 1.1 ou
  ultérieure.
- [Docker](https://www.docker.com/), version 29 ou ultérieure, avec Docker
  Compose.
- [Git](https://git-scm.com/) pour récupérer le dépôt et gérer les
  modifications.

Les versions indiquées constituent les versions minimales prévues pour le
projet. Une version plus récente peut être nécessaire selon les fonctionnalités
utilisées.

#### Extensions Visual Studio Code recommandées

- [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
  : analyse du code PHP et autocomplétion.
- [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
  : formatage du code pris en charge.
- [SQLite Viewer](https://marketplace.visualstudio.com/items?itemName=qwtel.sqlite-viewer)
  : consultation de bases SQLite, si nécessaire.
- [Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
  : ouverture du projet dans un conteneur de développement.

### Production

Dépendances et versions nécessaires pour ce projet :

- Un serveur [Apache 2.4 ou plus tard](https://httpd.apache.org/) avec
  [PHP 8.5 ou plus tard](https://www.php.net/) et les extensions nécessaires
  pour se connecter à une base de données [MariaDB](https://mariadb.org/).
- Une base de données [MariaDB 10.6 ou plus tard](https://mariadb.org/)
- **Envoi d'e-mails** : un service SMTP accessible (par exemple Infomaniak),
  avec une adresse e-mail valide et ses identifiants, est nécessaire si
  l'application doit envoyer des e-mails en production.

## Structure du projet

- `.devcontainer/`: Contient la configuration pour le développement dans un
  conteneur Debian (Trixie) avec Visual Studio Code.
- `.vscode/`: Contient les paramètres recommandés pour Visual Studio Code.
- `.gitignore`: Fichier de configuration pour Git, spécifiant les fichiers et
  dossiers à ignorer.
- `compose.yaml`: Fichier de configuration pour Docker Compose, définissant les
  services nécessaires pour le projet, avec :
  - [Apache](https://httpd.apache.org/) : Serveur web pour exécuter les fichiers
    PHP.
  - [MariaDB](https://mariadb.org/) : Base de données relationnelle.
  - [phpMyAdmin](https://www.phpmyadmin.net/) : Interface web pour gérer la base
    de données MariaDB.
  - [Mailpit](https://mailpit.axllent.org/) : Serveur SMTP pour tester l'envoi
    d'e-mails.

- `index.php`: Fichier PHP principal du projet.
- `script.sql`: Fichier SQL pour créer la table `my_table` dans la base de
  données.
- `README.md`: Ce fichier de documentation.

```shell

D:\
├── .devcontainer/  #Contient la configuration pour le développement dans un conteneur Debian (Trixie) avec Visual Studio Code.
├── compose.yaml  #Fichier de configuration pour Docker Compose
├── .vscode/ #Contient les paramètres recommandés pour Visual Studio Code.
├── diagrams/
├── mockups/
├── .github/  # Automatisation Github
│   └── workflows/
│       └── deploy.yml
├── .gitignore
├── .env.example
└── project/
    ├── public/                 # Seule racine accessible au public
    │   ├── index.php
    │   ├── .htaccess
    │   └── assets/
    │       ├── css/
    │       ├── js/
    │       └── images/
    ├── components/             # Composants PHP internes
    ├── src/                    # Logique métier
    │   ├── db/
    │   │   └── Database.php
    │   ├── Controllers/
    │   ├── Services/
    │   └── Middleware/
    ├── database/               # Migrations et scripts SQL
    ├── tests/
    └── vendor/                 # Si Composer est utilisé

```

# Développement

## Ouvrir le projet localement

> [!NOTE]
>
> Il est important d'ouvrir le dossier du projet directement et non un dossier
> parent.
>
> Pensez à bien ouvrir le dossier du projet lui-même (celui qui contient ce
> fichier README.md) et non un dossier parent. Ouvrir un dossier parent peut
> entraîner des problèmes de configuration et de fonctionnement de Visual Studio
> Code.

1. Clonez ce dépôt sur votre machine locale.
2. Ouvrez ce dossier dans Visual Studio Code.

## Ouvrir le projet dans un conteneur de développement

Si vous avez l'extension
[Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
installée, Visual Studio Code vous proposera d'ouvrir le projet dans un
conteneur Docker.

Acceptez cette proposition pour bénéficier d'un environnement de développement
préconfiguré.

Si aucune notification n'apparaît, vous pouvez ouvrir manuellement le conteneur
en utilisant la commande _"Dev Containers: Reopen in Container"_ dans la palette
de commandes (<kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>P</kbd> sur Windows/Linux ou
<kbd>Cmd</kbd>+<kbd>Shift</kbd>+<kbd>P</kbd> sur macOS).

## Démarrer les services Docker

Pour démarrer les services Docker nécessaires à développer des applications PHP,
ouvrez un terminal intégré dans Visual Studio Code et exécutez la commande
suivante :

```bash
# Démarre tous les services définis dans le fichier compose.yaml
docker compose up
```

Ceci va démarrer les services suivants dans des conteneurs Docker :

- Apache (port 8080) : Serveur web pour exécuter les fichiers PHP.
- MariaDB (port 3306) : Base de données relationnelle.
- phpMyAdmin (port 9090) : Interface web pour gérer la base de données MariaDB.
- Mailpit (ports 1025 et 8025) : Serveur SMTP pour tester l'envoi d'e-mails.

## Se connecter à la base de données MariaDB avec phpMyAdmin

Pour accéder à phpMyAdmin, ouvrez votre navigateur et rendez-vous à l'adresse
suivante : <http://localhost:9090>.

Vous pourrez alors vous connecter à la base de données en utilisant les
identifiants décrits dans le fichier `compose.yaml` (par défaut, l'utilisateur
est `username` et le mot de passe est `password`).

## Exécuter des requêtes SQL sur la base de données MariaDB

Une fois connecté à la base de données MariaDB via phpMyAdmin, vous pouvez
exécuter des requêtes SQL pour créer des tables, insérer des données ou
effectuer d'autres opérations sur la base de données.

1. Ouvrez/créez un fichier SQL dans Visual Studio Code (par exemple,
   `script.sql`).
2. Écrivez votre requête SQL dans ce fichier (par exemple, une requête pour
   créer une table ou insérer des données).
3. Dans l'onglet "SQL" de phpMyAdmin, vous pouvez copier et coller votre requête
   SQL depuis le fichier `script.sql` et l'exécuter pour appliquer les
   modifications à la base de données.

À noter que des fichiers de population automatique de la base de données sont
automatiquement importés lors de la création du conteneur.

## Exécuter vos scripts PHP

Le serveur web sera accessible à l'adresse <http://localhost:8080> dans votre
navigateur.

## Visualiser les e-mails envoyés par votre application PHP

Le serveur SMTP Mailpit est inclus dans la configuration Docker Compose pour
vous permettre de tester l'envoi d'e-mails depuis votre application PHP.

Pour accéder à l'interface web de Mailpit, ouvrez votre navigateur et
rendez-vous à l'adresse suivante : <http://localhost:8025>.

Vous y verrez la liste des e-mails envoyés par votre application PHP, ainsi que
leur contenu et les détails de l'envoi.

Si vous fermez/redémarrez Mailpit, les e-mails précédemment envoyés seront
perdus, car ils ne sont pas stockés de manière persistante.

## Arrêter les services Docker

Si vous avez exécuté la commande `docker compose up` pour démarrer les services
Docker, vous pouvez les arrêter avec le raccourci clavier
<kbd>Ctrl</kbd>+<kbd>C</kbd> dans le terminal où la commande a été exécutée.
Cela arrêtera les services et libérera les ressources utilisées par les
conteneurs Docker.

Sinon, vous pouvez également arrêter les services Docker en ouvrant un terminal
intégré dans Visual Studio Code et en exécutant la commande suivante :

```bash
# Arrête tous les services définis dans le fichier compose.yaml
docker compose down
```

Lorsque vous fermez Visual Studio Code, les conteneurs Docker seront
automatiquement arrêtés. Dès que vous rouvrez Visual Studio Code et que vous
exécutez à nouveau la commande `docker compose up`, les services Docker seront
redémarrés.

# Production

À compléter

## 1. Préparer les fichiers à déployer

Exemple d'organisation côté serveur :

```text
application/
├── private/
│   ├── src/
│   ├── components/
│   ├── database/
│   └── vendor/
└── public/
    ├── index.php
    ├── .htaccess
    └── assets/
```

## Configurer les variables d'environnement

Variables possibles :

```dotenv
APP_ENV=production
APP_DEBUG=false

DB_HOST=
DB_NAME=
DB_USER=
DB_PASSWORD=

SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASSWORD=
SMTP_FROM=
```

Les valeurs ci-dessus sont des exemples de noms de variables. Les valeurs
réelles doivent être obtenues auprès de l'hébergeur ou du fournisseur SMTP.

# GitHub Actions

To complete

# Contact

Si vous avez des questions, vous pouvez nous contacter en créant une Issue.

# Licence

Ce matériel est mis à disposition sous licence
[Creative Commons Attribution 4.0 International (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/).
