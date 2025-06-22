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
                docker run -d --name ${IMAGE_NAME}_test --network host \
                  -v $(pwd)/student_list/simple_api/student_age.json:/data/student_age.json \
                  $IMAGE_NAME

                sleep 5
                curl -u root:root http://127.0.0.1:5000/supmit/api/v1.0/get_student_ages
                docker rm -f ${IMAGE_NAME}_test
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
