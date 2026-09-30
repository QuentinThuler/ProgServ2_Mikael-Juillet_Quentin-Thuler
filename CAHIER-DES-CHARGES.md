# Projet Libre - Cahier des charges

## Mikael Juillet, Quentin Thüler, M54-1 - ProgServ2

Gestionnaire d’événements pour des associations, où la liste des événements de
l’année est affichée. Les visiteurs peuvent s’inscrire en renseignant uniquement
leur email.

L'objectif est de permettre un moyen simple aux associations de déterminer, le
nombre d'inscrit pour un événement (pour faciliter l'organisation), mais aussi
pour centraliser le point de conversion des inscrits (qui se ferait par
plusieurs canaux) et finalement pouvoir analyser le fonctionnement des campagnes
ou l'engouement pour chaque événement pour en tirer des tendances.

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

Privée :

- Inscription (user et admin)
- Connexion
- Ajout d'un event (admin)
- Modification d'un event (admin)
- Détail du compte (user et admin)
- Modification du compte (user et admin)
