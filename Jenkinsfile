pipeline {
    agent any

    environment {
        IMAGE_NAME = "iboy01/student_list_api"
        DOCKER_CREDENTIALS_ID = "dockerhub-creds"
    }

    stages {
        stage('Build Docker Image') {
            steps {
                dir('student_list/simple_api') {
                    script {
                        sh 'docker build -t $IMAGE_NAME .'
                    }
                }
            }
        }

        stage('Tester l\'image') {
            steps {
                script {
                    // Monter le fichier student_age.json dans /data/student_age.json
                    def containerId = sh(
                    script: "docker run -d --add-host=host.docker.internal:host-gateway -p 5000:5000 -v ${WORKSPACE}/student_list/simple_api/student_age.json:/data/student_age.json $IMAGE_NAME",
                    returnStdout: true
                    ).trim()
                    sleep 30  // donner plus de temps à l'API pour démarrer
                    def response = pipeline {
    agent any

    environment {
        // Variables pour Docker Hub et les images
        DOCKER_REGISTRY = 'docker.io'
        DOCKER_IMAGE_API = 'iboy01/student_list_api:latest'
        DOCKER_IMAGE_WEBSITE = 'iboy01/student_list_website:latest'
        
        // Variables pour le déploiement sur AWS (optionnel)
        AWS_EC2_INSTANCE = 'ec2-user@<EC2_IP>'  // Remplace <EC2_IP> par l'adresse IP de ton instance EC2
        EC2_PRIVATE_KEY = credentials('AWS_SSH_CREDENTIAL')
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                // Remplace l'URL par celle de ton dépôt Git
                git branch: 'master', url: 'https://github.com/iboy01/mini-projet-docker.git'
            }
        }
        
        stage('Construire l\'image API') {
            steps {
                dir('student_list/simple_api') {
                    script {
                        sh 'docker build -t $DOCKER_IMAGE_API .'
                    }
                }
            }
        }
        
        stage('Construire l\'image Website') {
            steps {
                dir('student_list/website') {
                    script {
                        sh 'docker build -t $DOCKER_IMAGE_WEBSITE .'
                    }
                }
            }
        }
        
        stage('Tester l\'image') {
            steps {
                script {
                    // Déclare containerId une seule fois
                    containerId = sh(script: "docker run -d --add-host=host.docker.internal:host-gateway -p 5000:5000 -v ${WORKSPACE}/student_list/simple_api/student_age.json:/data/student_age.json $DOCKER_IMAGE_API", returnStdout: true).trim()
                    sleep 30  // laisser le temps à l'API de démarrer
                    def status = sh(script: "curl -u root:root http://host.docker.internal:5000/supmit/api/v1.0/get_student_ages", returnStatus: true)
                    if (status != 0) {
                        echo "Erreur lors de l'appel API. Affichage des logs du conteneur API :"
                        sh "docker logs ${containerId}"
                        error "L'API ne répond pas correctement."
                    }
                    sh "docker stop ${containerId}"
                }
            }
        }

        
        stage('Pousser les images sur Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub-creds', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASSWORD')]) {
                    script {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USER" --password-stdin'
                        sh 'docker push $DOCKER_IMAGE_API'
                        sh 'docker push $DOCKER_IMAGE_WEBSITE'
                    }
                }
            }
        }
        
        stage('Déployer sur AWS EC2 (optionnel)') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub-creds', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                    script {
                        sh """
                        # Copier le fichier student_age.json sur l'instance EC2
                        scp -i $EC2_PRIVATE_KEY -o StrictHostKeyChecking=no student_list/simple_api/student_age.json $AWS_EC2_INSTANCE:/home/ec2-user/student_age.json

                        # Connecter à l'instance EC2 et déployer les images
                        ssh -i $EC2_PRIVATE_KEY -o StrictHostKeyChecking=no $AWS_EC2_INSTANCE '
                            echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin &&
                            docker pull $DOCKER_REGISTRY/$DOCKER_IMAGE_API &&
                            docker pull $DOCKER_REGISTRY/$DOCKER_IMAGE_WEBSITE &&
                            docker stop api || true && docker rm api || true &&
                            docker stop website || true && docker rm website || true &&
                            docker run -d -p 5000:5000 --name api -v /home/ec2-user/student_age.json:/data/student_age.json $DOCKER_REGISTRY/$DOCKER_IMAGE_API &&
                            docker run -d -p 80:80 --name website $DOCKER_REGISTRY/$DOCKER_IMAGE_WEBSITE
                        '
                        """
                    }
                }
            }
        }
    }
    
    post {
        success {
            echo 'Pipeline terminé avec succès.'
        }
        failure {
            echo 'Échec du pipeline.'
        }
    }
}
h(script: "curl -u root:root http://host.docker.internal:5000/supmit/api/v1.0/get_student_ages", returnStatus: true)
                    if(response != 0) {
                        echo "Erreur lors de l'appel API, affichage des logs du conteneur :"
                        sh "docker logs ${containerId}"
                        error "L'API ne répond pas"
                    }
                    sh "docker stop ${containerId}"
                }
            }
        }



        stage('Pousser sur Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS_ID}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh """
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push $IMAGE_NAME
                    """
                }
            }
        }
    }
}
