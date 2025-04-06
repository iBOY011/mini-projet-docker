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
                    def response = sh(script: "curl -u root:root http://host.docker.internal:5000/supmit/api/v1.0/get_student_ages", returnStatus: true)
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
