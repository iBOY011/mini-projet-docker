FROM python:3.8-buster

LABEL maintainer="Lahlaouti Haitam <haitamlahlaouti01@gmail.com>"

RUN apt update -y && apt install python3-dev libsasl2-dev libldap2-dev libssl-dev -y

WORKDIR /usr/src/app

COPY requirements.txt ./
RUN pip install --upgrade pip && pip install --default-timeout=100 --no-cache-dir -r requirements.txt

COPY student_age.py .

EXPOSE 5000

CMD ["python", "./student_age.py"]
