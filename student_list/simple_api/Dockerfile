FROM python:3.8-buster

LABEL maintainer="Lahlaouti Haitam <haitamlahlaouti01@gmail.com>"

# Installer les dépendances système nécessaires
RUN apt update -y && apt install python3-dev libsasl2-dev libldap2-dev libssl-dev -y

# Définir le répertoire de travail
WORKDIR /usr/src/app

# Copier le fichier des dépendances et installer les packages Python
COPY requirements.txt ./
RUN pip install --upgrade pip && pip install --default-timeout=100 --no-cache-dir -r requirements.txt

# Copier le script de l'API
COPY student_age.py .

# Exposer le port 5000 pour l'API Flask
EXPOSE 5000

# Lancer l'API
CMD ["python", "./student_age.py"]
