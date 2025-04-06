pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'iboy01/student_list_api'
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                echo 'Clonage du dépôt'
            }
        }

        stage('Construire l\'image Docker') {
            steps {
                dir('student_list/simple_api') {
                    script {
                        sh "docker build -t $DOCKER_IMAGE ."
                    }
                }
            }
        }

        stage('Tester l\'API') {
            steps {
                script {
                    // Lancer le conteneur avec montage du fichier student_age.json
                    def containerId = sh(
                        script: "docker run -d -p 5000:5000 -v ${pwd()}/student_list/simple_api/student_age.json:/data/student_age.json:ro $DOCKER_IMAGE",
                        returnStdout: true
                    ).trim()

                    // Attendre un peu que l'API démarre
                    sleep(time: 30, unit: 'SECONDS')

                    // Tester l'API
                    try {
                        sh 'curl -u root:root http://localhost:5000/supmit/api/v1.0/get_student_ages'
                    } catch (Exception e) {
                        echo "Erreur lors de l'appel de l'API, affichage des logs du conteneur :"
                        sh "docker logs ${containerId}"
                        error("L'API ne répond pas correctement.")
                    } finally {
                        sh "docker stop ${containerId}"
                    }
                }
            }
        }

        stage('Pousser l\'image sur Docker Hub') {
            when {
                expression {
                    return currentBuild.currentResult == 'SUCCESS'
                }
            }
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub-creds', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASSWORD')]) {
                    script {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USER" --password-stdin'
                        sh "docker push $DOCKER_IMAGE"
                    }
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline terminé avec succès 🎉'
        }
        failure {
            echo 'Le pipeline a échoué ❌'
        }
    }
}
