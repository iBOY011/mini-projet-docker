# Mini-Projet Docker : Application Python (API Flask) + Site Web PHP

## Table des matières
1. [Introduction](#introduction)  
2. [Prérequis](#prérequis)  
3. [Structure du Projet](#structure-du-projet)  
4. [Conteneurisation de l’API Flask](#conteneurisation-de-lapi-flask)  
5. [Orchestration avec Docker Compose](#orchestration-avec-docker-compose)  
6. [Mise en Place du Docker Registry Privé](#mise-en-place-du-docker-registry-privé)  
7. [Résultats et Captures d’Écran](#résultats-et-captures-décran)  
8. [Problèmes Rencontrés et Solutions](#problèmes-rencontrés-et-solutions)  
9. [Conclusion](#conclusion)

---

## Introduction

Ce mini-projet a pour objectif de **containeriser** une application composée de deux modules :  
- Une **API REST** écrite en Python (Flask) pour afficher et gérer la liste des étudiants.  
- Un **site web** en PHP permettant d’afficher la liste des étudiants récupérée depuis l’API.  

Ensuite, nous avons **orchestré** ces deux services avec Docker Compose et **déployé un registre privé** pour stocker et gérer nos images Docker localement.

---

## Prérequis

- **Ubuntu 24.04** (ou équivalent).  
- **Docker** et **Docker Compose** installés et fonctionnels.  
  - Vérification avec :
    ```bash
    docker --version
    docker-compose --version
    ```
- Accès à un éditeur de texte ou IDE (VSCode, nano, vim, etc.).

---

## Structure du Projet

```
mini-projet-docker/
├── Dockerfile
├── docker-compose.yml
├── docker-compose-registry.yml
├── requirements.txt
├── student_age.py
├── student_age.json
├── website/
│   └── index.php
└── README.md
```

---

## Conteneurisation de l’API Flask

1. **Dockerfile**

```Dockerfile
FROM python:3.8-buster
LABEL maintainer="Lahlaouti Haitam <haitamlahlaouti01@gmail.com>"
RUN apt update -y && apt install python3-dev libsasl2-dev libldap2-dev libssl-dev -y
WORKDIR /usr/src/app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
COPY student_age.py .
RUN mkdir /data
VOLUME /data
EXPOSE 5000
CMD [ "python", "./student_age.py" ]
```

2. **Construction**
```bash
docker build -t student_list_api .
```
![Build image](captures/3.png)

3. **Exécution**
```bash
docker run -d -p 5000:5000 \
-v $(pwd)/student_age.json:/data/student_age.json \
student_list_api
```
![Container Run](captures/5.png)

4. **Test avec CURL**
```bash
curl -u root:root http://localhost:5000/supmit/api/v1.0/get_student_ages
```
![API Test](captures/7.png)

---

## Orchestration avec Docker Compose

1. **Fichier docker-compose.yml**
```yaml
version: '3'
services:
  api:
    image: student_list_api
    container_name: student_list_api_container
    volumes:
      - ./student_age.json:/data/student_age.json
    ports:
      - "5000:5000"
    networks:
      - my_app_network

  website:
    image: php:apache
    depends_on:
      - api
    ports:
      - "8080:80"
    volumes:
      - ./website:/var/www/html
    networks:
      - my_app_network

networks:
  my_app_network:
    driver: bridge
```

2. **Lancement**
```bash
docker-compose up -d
```
![Docker Compose Up](captures/9.png)

3. **Accès Web**
- [http://localhost:8080](http://localhost:8080)

![Interface vide](captures/11.png)
![Résultat liste](captures/12.png)

---

## Mise en Place du Docker Registry Privé

1. **Registre**
```bash
docker run -d -p 5001:5000 --name registry registry:2
```

2. **Interface web**
```bash
docker run -d \
-p 8083:80 \
--name registry_ui \
-e REGISTRY_TITLE="Mon_Registry_Privé" \
-e REGISTRY_URL="http://localhost:5001" \
joxit/docker-registry-ui
```

![Registry UI](captures/14.png)
![Image tag](captures/15.png)

3. **Push de l'image**
```bash
docker tag student_list_api localhost:5001/student_list_api:v1
docker push localhost:5001/student_list_api:v1
```

---

## Résultats et Captures d’Écran

Les captures se trouvent dans le dossier `captures/` :
- Installation Docker : `1.png`, `2.png`
- Build et exécution API : `3.png` à `7.png`
- Docker Compose : `8.png`, `9.png`, `10.png`
- Interface web : `11.png`, `12.png`
- Registry : `13.png`, `14.png`, `15.png`

---

## Problèmes Rencontrés et Solutions

- **Port 5000 déjà utilisé** : solution → kill ou changer le port.
- **Docker compose erreur** : `compose is not a docker command` → utilisé `docker-compose` classique.
- **Erreur ContainerConfig** : version Docker Compose corrigée (v1.29.2).

---

## Conclusion

Nous avons :
- Conteneurisé une API Flask
- Créé une interface Web
- Utilisé Docker Compose pour les orchestrer
- Déployé un registre privé pour nos images

Le projet montre les avantages clairs de Docker dans la déploiement rapide, la modularité et l'évolution de projets multi-composants.

---


