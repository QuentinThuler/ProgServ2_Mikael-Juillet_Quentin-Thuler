# Projet Libre - Cahier des charges

## Mikael Juillet, Quentin Thüler, M54-1 - ProgServ2

Gestionnaire d’événements pour des associations, où la liste des événements de
l’année est affichée. Les visiteurs peuvent s’inscrire en renseignant uniquement
leur email.

L'objectif est d'offrir un moyen simple aux associations de déterminer le
nombre d'inscrits à un évènement, afin de faciliter son organisation. De plus, 
le but est qu'après avoir fait de la promotion de l'évènement sur plusieurs 
canaux de communication, on puisse centraliser le point de conversion des prospects 
sur une plateforme unique. Finalement, l'objectif est de pouvoir analyser et 
mesurer l'efficacité des campagnes ainsi que l'engouement pour chaque évènement,
dans une optique de reporting.

## Fonctionnalités de bases

- Création, mise à jour, lecture (CRU) d'évènements (admin)
  - Définir par : titre, description, phrase d'accroche, adresse et ville, salle, date et heure, une image, date de création, créateur et nombre d'inscrit.

- Création de comptes (définition des comptes plus bas)
- CRUD du compte
- Connexion au compte

- Voir la liste des évènements
- Voir les détails d'un évènement

- Inscription à un évènement par courriel : pas besoin de se connecter
- Réception de mail automatique pour confirmation d'inscription

- Rappel automatique envoyé par email à un utilisateur qui s'est inscrit à un évènement,
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

# Les comptes
On trouve trois catégories de profils \: ##les administrateurs, qui ont tous les droits de modification et de suppression, et l’accès à toutes les fonctionnalités ; mais le rôle « Administrateur » ne peut être appliqué depuis l’application\. Il doit être effectué manuellement à partir de la base de données\. Ils ont le rôle d’administrateur\.

## les participants
Ce ne sont que des visiteurs n'ayant aucun compte, mais s'étant inscrits à un évènement. À l’inscription, ils ont dû valider leur email par mail\. Il est donc stocké dans la base de données\. S’il souhaite à l'avenir créer un compte, il passera de participant à utilisateur. 
Un participant n'a aucune autre information que son email et la confirmation de sa validation. Il doit cependant valider à chaque inscription son email, dans le but d'éviter d'inscrire n'importe qui et d'éviter les spams.

## un utilisateur 
Ce sont des participants qui ont décidé de créer un compte, ils doivent inscrire un nom et un mot de passe de plus ils ont un rôle (utilisateur) attribué. Ils peuvent optionnellement enregistrer une description, une photo de profil, un lien LinkedIn et un numéro de téléphone\.
En plus des accès de base du participant, l'utilisateur peut voir, modifier son profil et accéder à la liste des évènements à laquelle il est inscrit. De plus, il n'a désormais plus besoin de confirmer son email pour s'inscrire dès lors qu'il est connecté.

## Événements
Quand un utilisateur d'inscrit à un évènement l'on souhaite capturer la source de sa provenance via un UTM dans l'URL, l'on souhaite aussi avoir l'information de la date d'inscription et si le mail de rappel a bien été envoyé.
