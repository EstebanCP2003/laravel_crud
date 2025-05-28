pipeline {
    agent { label 'agent4' }

    environment {
        COMPOSE_PROJECT_NAME = 'laravel_crud'
    }

    stages {
        stage('Verificar entorno') {
            steps {
                sh '''
                    whoami
                    ls -l /var/run/docker.sock
                    docker ps
                '''
            }
        }

        stage('Checkout & Update Jenkinsfile') {
            steps {
                checkout scm
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                sh """
                    docker run --rm \
                        -v ${env.WORKSPACE}:/app \
                        -w /app \
                        composer:2 \
                        composer install --no-interaction --prefer-dist
                """
            }
        }
    }

    post {
        always {
            echo '🧹 Limpiando contenedores y volúmenes…'
            sh 'docker-compose down -v || true'
        }
        success {
            echo '✅ Pipeline finalizado con éxito.'
        }
        failure {
            echo '❌ Algo falló en el pipeline.'
        }
    }
}

