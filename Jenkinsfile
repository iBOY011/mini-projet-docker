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
                    def containerId = sh(script: "docker run -d -p 5000:5000 $IMAGE_NAME", returnStdout: true).trim()
                    sleep 10  // laisse le temps à l'API de démarrer
                    // Utilise l'IP de l'hôte au lieu de localhost
                    sh 'curl -u root:root http://172.17.0.1:5000/supmit/api/v1.0/get_student_ages'
                    sh "docker stop $containerId"
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
