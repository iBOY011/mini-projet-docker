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
![Démarrage Docker](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

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
![Image Docker créée](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 3. Vérification de l'image construite
```bash
docker images
```
![Vérification image Docker](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 4. Lancement du conteneur avec Docker
```bash
docker run -d -p 5000:5000 -v $(pwd)/student_age.json:/data/student_age.json student_list_api
```
![Conteneur démarré](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 5. Vérification de l'état du conteneur
```bash
docker ps
```
![Conteneur en cours d'exécution](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 6. Consultation des logs du conteneur
```bash
docker logs <CONTAINER_ID>
```
![Logs du serveur Flask](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 7. Test de l'API avec `curl`
```bash
curl -u root:root -X GET http://localhost:5000/supmit/api/v1.0/get_student_ages
```
![Test avec curl](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 8. Utilisation de Docker Compose pour lancer plusieurs services
```bash
docker-compose up -d
```
![Docker Compose](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 9. Affichage des conteneurs lancés
```bash
docker-compose ps
```
![Conteneurs actifs](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 10. Interface Web PHP (client)
![Interface web initiale](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)
![Résultat affiché](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 11. Mise en place du registre privé avec UI
```bash
docker-compose -f docker-compose-registry.yml up -d
```
![Démarrage du registre](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

---

### 12. Accès à l’interface UI du registre
- Lien : http://localhost:8083
![UI du registre](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)
![Détail image poussée](data:image/png;base64,iVBORw0KGgoAAAANSUhEUg...)

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

## 📷 Captures d’écran intégrées
Toutes les captures d'écran sont intégrées directement dans ce fichier README.md.

