pipeline {
    agent { label 'agent4' }

    triggers {
        pollSCM('H/5 * * * *')
    }

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
                    pwd
                    ls -l
                '''
            }
        }

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Instalar dependencias Composer') {
            steps {
                sh '''
                    docker run --rm -v $(pwd):/app -w /app composer:2 composer install --no-interaction --prefer-dist
                '''
            }
        }

        stage('Preparar Laravel') {
            steps {
                sh '''
                    php artisan key:generate --ansi
                    php artisan migrate --force --ansi
                '''
            }
        }

        stage('Ejecutar Dusk') {
            steps {
                sh '''
                    php artisan dusk --verbose --headless --disable-gpu
                '''
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline completado correctamente.'
        }
        failure {
            echo '❌ El pipeline falló.'
        }
    }
}


