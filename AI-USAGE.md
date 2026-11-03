# Population-test.sql generation

Après la génération du MPD, nous avons utilisé une première fois le 15 octobre
ChatGPT dans le but d'automatiser la population de la base de données. Une
seconde génération a été effectuée le 22 octobre suite à des changements
significatifs dans le MPD. Étant donné que ce type de tâche est très monotone et
n'est qu'utile en tests lors du développement de l'app, la génération de donnée
par une IA est une méthode rapide et efficace pour générer ces données. Ainsi,
nous lui avons transmis le MPD et nous lui avons demandé : "Créer un fichier
d'insertion/population de donnée en rapport au MPD suivant, prend note des
points suivants :

- données réaliste pour, par exemple, un club photo
- un participant a un numéro de token de six chars qui doit être encripter"

Les réponses ont été copiées et les lignes, telles que "ON DUPLICATE KEY UPDATE
email = VALUES(email);" ont été supprimées, car, pour des données de tests cela
n'est pas nécessaire, d'autres corrections ont été effectuées.
