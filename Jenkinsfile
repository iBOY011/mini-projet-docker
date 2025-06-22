pipeline {
    agent any

    environment {
        IMAGE_NAME      = 'student_list_api'
        DOCKERHUB_CREDS = credentials('dockerhub-creds')  // <- ton ID Jenkins
    }

    stages {
        stage('Checkout') {
            steps {
                git url: 'https://github.com/iBOY011/mini-projet-docker.git'
            }
        }

        stage('Build image') {
            steps {
                sh 'docker build -t $IMAGE_NAME student_list/simple_api'
            }
        }

        stage('Test image') {
            steps {
                sh '''
                # Toujours repartir d'un état propre
                docker rm -f student_list_api_test || true

                # Lancement du conteneur de test
                CID=$(docker run -d --name student_list_api_test -p 5001:5000 \
                    -v $(pwd)/student_list/simple_api/student_age.json:/data/student_age.json \
                    $IMAGE_NAME)

                # Attente que l’API réponde (30 s max)
                for i in {1..30}; do
                if curl -s -u root:root http://127.0.0.1:5001/supmit/api/v1.0/get_student_ages > /dev/null; then
                    echo "API is up!"
                    curl -u root:root http://127.0.0.1:5001/supmit/api/v1.0/get_student_ages
                    docker rm -f $CID
                    exit 0
                fi
                echo "Waiting for API... ($i)"
                sleep 1
                done

                echo "API did not start in time"
                docker logs $CID || true
                docker rm -f $CID
                exit 1
                '''
            }
        }



        stage('Push to Docker Hub') {
            steps {
                sh '''
                echo $DOCKERHUB_CREDS_PSW | \
                  docker login -u $DOCKERHUB_CREDS_USR --password-stdin

                docker tag $IMAGE_NAME $DOCKERHUB_CREDS_USR/$IMAGE_NAME:latest
                docker push      $DOCKERHUB_CREDS_USR/$IMAGE_NAME:latest
                '''
            }
        }
    }

    post {
        always {
            sh 'docker rmi $IMAGE_NAME || true'
        }
    }
}
