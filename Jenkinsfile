pipeline {
    agent any

    environment {
        IMAGE_NAME = 'iboy01/student_list_api'
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                echo 'Clonage du dépôt'
                checkout scm
            }
        }

        stage('Construire l\'image Docker') {
            steps {
                dir('student_list/simple_api') {
                    script {
                        sh "docker build -t ${IMAGE_NAME} ."
                    }
                }
            }
        }

        stage("Tester l'API") {
            steps {
                script {
                    def workspacePath = pwd()
                    def containerId = sh(
                        script: """
                            docker run -d -p 5000:5000 \
                            -v ${workspacePath}/student_list/simple_api/student_age.json:/data/student_age.json:ro \
                            ${IMAGE_NAME}
                        """,
                        returnStdout: true
                    ).trim()

                    sleep(time: 30, unit: "SECONDS")

                    def response = sh(
                        script: 'curl -u root:root http://localhost:5000/supmit/api/v1.0/get_student_ages',
                        returnStatus: true
                    )

                    if (response != 0) {
                        echo "Erreur lors de l'appel de l'API, affichage des logs du conteneur :"
                        sh "docker logs ${containerId}"
                        error("L'API ne répond pas correctement.")
                    }

                    sh "docker stop ${containerId}"
                }
            }
        }

        stage('Pousser l\'image sur Docker Hub') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub-creds', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    script {
                        sh "echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin"
                        sh "docker push ${IMAGE_NAME}"
                    }
                }
            }
        }
    }

    post {
        success {
            echo 'Le pipeline a réussi ✅'
        }
        failure {
            echo 'Le pipeline a échoué ❌'
        }
    }
}
