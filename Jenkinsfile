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
                '''
            }
        }

        stage('Checkout & Update Jenkinsfile') {
            steps {
                checkout scm
            }
        }

        stage('Levantar containers Laravel') {
            steps {
                sh '''
                    docker-compose up -d --build --force-recreate
                '''
            }
        }

        stage('Instalar dependencias Composer') {
            steps {
                sh '''
                    docker-compose exec app bash -c "composer install --no-interaction --prefer-dist"
                '''
            }
        }

        stage('Preparar Laravel') {
            steps {
                sh '''
                    docker-compose exec app bash -c "php artisan key:generate --ansi && php artisan migrate --force --ansi"
                '''
            }
        }

        stage('Ejecutar Dusk') {
            steps {
                sh '''
                    docker-compose exec app bash -c "php artisan dusk --verbose --headless --disable-gpu"
                '''
            }
        }
    }

    post {
        success {
            echo '✅ ¡Todo salió bien!'
        }
        failure {
            echo '❌ Algo falló.'
        }
    }
}


