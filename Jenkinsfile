pipeline {
    agent any

    environment {
        IMAGE_NAME = "iboy01/student_list_api"
        DOCKER_CREDENTIALS_ID = "dockerhub-creds"
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                // Jenkins gère le clonage via la configuration SCM du job.
                echo 'Clonage du dépôt'
            }
        }

        stage('Construire l\'image Docker') {
            steps {
                dir('student_list/simple_api') {
                    script {
                        sh 'docker build -t $IMAGE_NAME .'
                    }
                }
            }
        }

        stage('Tester l\'API') {
            steps {
                script {
                    // Monter le fichier student_age.json depuis le workspace dans le conteneur à /data/student_age.json.
                    def containerId = sh(
                        script: "docker run -d -p 5000:5000 -v ${WORKSPACE}/student_list/simple_api/student_age.json:/data/student_age.json $IMAGE_NAME",
                        returnStdout: true
                    ).trim()
                    sleep 30  // Attendre que l'API démarre
                    def status = sh(script: "curl -u root:root http://localhost:5000/supmit/api/v1.0/get_student_ages", returnStatus: true)
                    if (status != 0) {
                        echo "Erreur lors de l'appel de l'API, affichage des logs du conteneur :"
                        sh "docker logs ${containerId}"
                        error "L'API ne répond pas correctement."
                    }
                    sh "docker stop ${containerId}"
                }
            }
        }

        stage('Pousser l\'image sur Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS_ID}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    script {
                        sh 'echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin'
                        sh 'docker push $IMAGE_NAME'
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
