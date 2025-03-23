# Projet Docker - Gestion de la liste des étudiants

## Auteurs
**Nom :** Haitam Lahlaouti

---

## 📌 Objectif du projet
Ce mini-projet a pour but de créer une API simple en Python qui expose une liste d'étudiants et leurs âges, de la conteneuriser avec Docker, de la déployer avec Docker Compose, et de pousser l'image vers un registre Docker privé visualisable via une interface graphique.

---

## 🧱 Prérequis
- Docker installé et actif
- Docker Compose installé
- Système basé sur Linux (Ubuntu recommandé)

---

## 🔧 Étapes du projet

### 1. Démarrage du service Docker
```bash
sudo systemctl start docker
sudo systemctl enable docker
```
![Démarrage Docker](./captures/1.png)

---

### 2. Création de l'image Docker pour l'API
Voici le `Dockerfile` utilisé :
```Dockerfile
FROM python:3
WORKDIR /usr/src/app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY . .
CMD ["python", "./student_age.py"]
```
![Image Docker créée](captures/2.png)
![Image Docker créée](captures/3.png)

---

### 3. Vérification de l'image construite
```bash
docker images
```
![Vérification image Docker](captures/4.png)

---

### 4. Lancement du conteneur avec Docker
```bash
docker run -d -p 5000:5000 -v $(pwd)/student_age.json:/data/student_age.json student_list_api
```
![Conteneur démarré](captures/5.png)

---

### 5. Vérification de l'état du conteneur
```bash
docker ps
```
![Conteneur en cours d'exécution](captures/5.png)

---

### 6. Consultation des logs du conteneur
```bash
docker logs <CONTAINER_ID>
```
![Logs du serveur Flask](captures/6.png)

---

### 7. Test de l'API avec `curl`
```bash
curl -u root:root -X GET http://localhost:5000/supmit/api/v1.0/get_student_ages
```
![Test avec curl](captures/7.png)

---

### 8. Utilisation de Docker Compose pour lancer plusieurs services
```bash
docker-compose up -d
```
![Docker Compose](captures/8.png)
![Docker Compose](captures/9.png)


---

### 9. Affichage des conteneurs lancés
```bash
docker-compose ps
```
![Conteneurs actifs](captures/10.png)

---

### 10. Interface Web PHP (client)
![Interface web initiale](captures/11.png)
![Résultat affiché](captures/12.png)

---

### 11. Mise en place du registre privé avec UI
```bash
docker-compose -f docker-compose-registry.yml up -d
```
![Démarrage du registre](captures/13.png)

---

### 12. Accès à l’interface UI du registre
- Lien : http://localhost:8083
![UI du registre](captures/14.png)
![Détail image poussée](captures/15.png)

---

### 13. Push de l’image dans le registre
```bash
docker tag student_list_api localhost:5000/student_list_api

docker push localhost:5000/student_list_api
```

---

## 📁 Fichiers du projet

- `Dockerfile` : Construction de l'image Python
- `docker-compose.yml` : Déploiement de l’API + client web
- `docker-compose-registry.yml` : Déploiement du registre Docker privé avec UI

---

## ✅ Résultat final
Une API Flask Dockerisée avec une interface web fonctionnelle + une interface de registre privé consultable via navigateur.

---

## 🧠 Conclusion

Ce projet nous a permis de comprendre et de mettre en pratique les concepts essentiels de Docker et Docker Compose, tout en découvrant la gestion d’un registre privé et son interface UI. Une mise en situation réaliste et complète, idéale pour consolider nos compétences DevOps.

---

## 📷 Captures d’écran intégrées
Toutes les captures d'écran sont intégrées directement dans ce fichier README.md.

---

📎 [Lien vers le dépôt GitHub du projet](https://github.com/iBOY011/mini-projet-docker)


