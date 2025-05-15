pipeline {
    agent { label 'docker' }                         // Usa un agente con Docker instalado

    environment {
        COMPOSE_PROJECT_NAME = 'laravel_crud'        // Nombre de proyecto para docker-compose
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build & Start Containers') {
            steps {
                // Reconstruye imágenes y levanta todos los servicios en background
                sh 'docker-compose down -v'
                sh 'docker-compose up -d --build --force-recreate'
            }
        }

        stage('Install Dependencies') {
            steps {
                // Instala composer dentro del contenedor "app"
                sh '''
                   docker-compose exec app bash -lc "
                       composer install --no-interaction --prefer-dist
                   "
                '''
            }
        }

        stage('Prepare Application') {
            steps {
                // Genera APP_KEY, migra y seed si aplica
                sh '''
                   docker-compose exec app bash -lc "
                       php artisan key:generate --ansi
                       php artisan migrate --force --ansi
                   "
                '''
            }
        }

        stage('Run Dusk Acceptance Tests') {
            steps {
                // Ejecuta las pruebas Dusk en modo headless
                sh '''
                   docker-compose exec app bash -lc "
                       php artisan dusk --verbose --headless --disable-gpu
                   "
                '''
            }
        }
    }

    post {
        always {
            // Derriba todos los contenedores y limpia volúmenes
            echo '🧹 Cleaning up…'
            sh 'docker-compose down -v'
        }
        success {
            echo '✅ ¡Build y tests OK!'
        }
        failure {
            echo '❌ Hubo errores en el pipeline.'
        }
    }
}