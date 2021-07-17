# Projet-oauth
Projet d'authentification réalisé par Margaux HEBERT et Loudovic Rex-HARRISON en troisième année de Bachelor Ingénierie du Web à l'ESGI 

Possibilité de se connecter avec Facebook, Google et Discord

Pour utiliser le projet :
- Lancer la commande docker-compose up -d dans le répertoire Projet-oauth
- Aller sur l'url de connexion : https://localhost/login

Ne pas oublier de renseigner les variables d'environnement dans les fichiers .env qu'il faut placer dans le dossier oauth-client

.env :  
ENV=dev

.env.dev :  
STATE=  
CLIENT_ID=  
CLIENT_SECRET=  
CLIENT_FBSECRET=  
CLIENT_FBID=  
CLIENT_GOOGLEID=  
CLIENT_GOOGLESECRET=  
CLIENT_DDID=  
CLIENT_DDSECRET=  
