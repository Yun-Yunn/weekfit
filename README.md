# WeekFit

WeekFit est une application web de suivi sportif développée avec **Laravel**.  
Elle permet aux utilisateurs de gérer leurs entraînements, suivre leurs statistiques et visualiser leurs progrès à travers un tableau de bord clair et moderne.

---

## Présentation

L’objectif de WeekFit est de proposer une expérience simple et motivante pour le suivi d’activités sportives.  
L’utilisateur peut se connecter, accéder à un **dashboard** dynamique et consulter :
- ses **exercices enregistrés**,
- les **muscles travaillés**,
- les **équipements utilisés**,
- et lancer une **séance d’entraînement** directement depuis l’interface.

---

## Fonctionnalités

- Authentification avec **Laravel Breeze**
- Interface sombre et moderne
- Visualisation des statistiques en temps réel
- Gestion des données utilisateurs via **MySQL**
- Responsive et utilisable sur mobile

---

Installe les dépendances PHP et JavaScript :

composer install
npm install
npm run dev


Copie le fichier d’environnement et configure-le :

cp .env.example .env


Génère la clé d’application :

php artisan key:generate


Configure ta base de données dans le fichier .env, par exemple :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=weekfit
DB_USERNAME=root
DB_PASSWORD=


Exécute les migrations :

php artisan migrate


Lance le serveur local :

php artisan serve


Ouvre ton navigateur et accède à :

http://127.0.0.1:8000



