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
                    docker run --rm \
                        -v "$PWD":/app \
                        -w /app \
                        composer:2 \
                        composer install --no-interaction --prefer-dist
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
                sh 'php artisan dusk --verbose --headless --disable-gpu'
            }
        }

        stage('Deploy a Producción') {
            when {
                branch 'main'
            }
            steps {
                sh '''
                    docker-compose down
                    docker-compose up -d --build
                '''
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
            echo '❌ El pipeline falló.'
        }
    }
}

