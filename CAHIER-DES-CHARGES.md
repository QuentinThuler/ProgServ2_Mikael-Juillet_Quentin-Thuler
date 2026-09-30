# Projet Libre - Cahier des charges

## Mikael Juillet, Quentin Thüler, M54-1 - ProgServ2

Gestionnaire d’événements pour des associations, où la liste des événements de
l’année est affichée. Les visiteurs peuvent s’inscrire en renseignant uniquement
leur email.

L'objectif est de permettre un moyen simple aux associations de déterminer le
nombre d'inscrits à un événement, afin de faciliter son organisation. Également, 
le but est qu'après avoir fait de la promotion de l'évènement  sur plusieurs 
canaux de communication, on puisse centraliser le point de conversion des inscrits 
sur une plateforme unique. Finalement, l'objectif est de pouvoir analyser et 
mesurer l'efficacité des campagnes ainsi que l'engouement pour chaque événement,
dans une optique de reporting.

## Fonctionnalités de bases

- Création d'événements (admin)
  - nom, date, lieux, descriptions, NB inscrit

- Création de comptes/login
- Connexion au compte

- Inscription à un événement par courriel : pas besoin de se connecter
- Réception de mail automatique de confirmation d'inscription
- Voir la liste des événements et le détail
- Rappel par email

## Liste des pages

Public :

- Page d'accueil (liste des évents)
- Détail d'un évent + inscription
  - Voir la liste des inscrits (admin)
- Inscription (user et admin)
- Connexion (user et admin)

Privée :

- Ajout d'un event (admin)
- Modification d'un event (admin)
- Détail du compte (user et admin)
- Modification du compte (user et admin)
