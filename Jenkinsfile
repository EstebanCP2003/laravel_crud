pipeline {
    agent { label 'agent4' }

    triggers {
        pollSCM('H/5 * * * *')
        // githubPush() // si quieres activar por push también
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

        stage('Build & Start Containers') {
            steps {
                sh 'docker-compose down -v'
                sh 'docker-compose up -d --build --force-recreate'
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                sh '''
                    docker compose exec app bash -lc "
                      composer install --no-interaction --prefer-dist
                    "
                '''
            }
        }

        stage('Prepare Laravel') {
            steps {
                sh '''
                    docker compose exec app bash -lc "
                      php artisan key:generate --ansi
                      php artisan migrate --force --ansi
                    "
                '''
            }
        }

        stage('Run Dusk (10 Acceptance Tests)') {
            steps {
                sh '''
                    docker compose exec app bash -lc "
                      php artisan dusk --verbose --headless --disable-gpu
                    "
                '''
            }
        }

        stage('Deploy to Production') {
            when {
                branch 'main'
            }
            steps {
                sh '''
                    docker compose down
                    docker compose up -d --build
                '''
            }
        }
    }

    post {
        always {
            echo '🧹 Limpiando contenedores y volúmenes…'
            sh 'docker compose down -v'
        }
        success {
            echo '✅ Pipeline finalizado con éxito.'
        }
        failure {
            echo '❌ Algo falló en el pipeline.'
        }
    }
}

