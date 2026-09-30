# Projet Libre - Cahier des charges

## Mikael Juillet, Quentin Thüler, M54-1 - ProgServ2

Gestionnaire d’événements pour des associations, où la liste des événements de
l’année est affichée. Les visiteurs peuvent s’inscrire en renseignant uniquement
leur email.

L'objectif est d'offrir un moyen simple aux associations de déterminer le
nombre d'inscrits à un événement, afin de faciliter son organisation. Également, 
le but est qu'après avoir fait de la promotion de l'évènement  sur plusieurs 
canaux de communication, on puisse centraliser le point de conversion des prospects 
sur une plateforme unique. Finalement, l'objectif est de pouvoir analyser et 
mesurer l'efficacité des campagnes ainsi que l'engouement pour chaque événement,
dans une optique de reporting.

## Fonctionnalités de bases

- Création d'événements (admin)
  - nom, date, lieu(x), description, nombre initial d'inscrits

- Création de compte
- Connexion au compte

- Voir la liste des événements
- Voir les détails d'un évènement

- Inscription à un événement par courriel : pas besoin de se connecter
- Réception de mail automatique pour confirmation d'inscription

- Rappel automatique envoyé par email à un user qui s'est inscrit à un évènement,
une semaine avant la date de cet évènement.

## Liste des pages

Public :
- Page d'accueil : liste des évènements + inscription
- Détail d'un évènement + inscription
- Inscription (user)
- Connexion (user et admin)

Privée (accès uniquement permis admin) :
- Voir la liste des inscrits avec leurs coordonnées (admin)
- Ajout d'un évènement
- Modification d'un évènement
- Détails du compte (admin)
- Modification du compte (admin)

Privée (accès uniquement une fois connecté au compte) :
- Liste de tous les éléments auxquels l'user est inscrit
- Détails du compte (user)
- Modification du compte (user)
