pipeline {
    
    agent {
    docker {
        image 'docker:24.0.7-cli'
        args '-v /var/run/docker.sock:/var/run/docker.sock'
    }
}


    // Cada vez que haya commits en el repositorio, el pipeline se dispara automáticamente
    triggers {
        
        pollSCM('H/5 * * * *')

        // githubPush()
    }

    environment {
        COMPOSE_PROJECT_NAME = 'laravel_crud'
    }

    stages {
        stage('Checkout & Update Jenkinsfile') {
            steps {
                // Clona el proyecto (incluyendo el Jenkinsfile actualizado)
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
                  docker-compose exec app bash -lc "
                    composer install --no-interaction --prefer-dist
                  "
                '''
            }
        }

        stage('Prepare Laravel') {
            steps {
                sh '''
                  docker-compose exec app bash -lc "
                    php artisan key:generate --ansi
                    php artisan migrate --force --ansi
                  "
                '''
            }
        }

        stage('Run Dusk (10 Acceptance Tests)') {
            steps {
                sh '''
                  docker-compose exec app bash -lc "
                    php artisan dusk --verbose --headless --disable-gpu
                  "
                '''
            }
        }

        stage('Deploy to Production') {
            when {
                branch 'main'  // solo despliega desde main
            }
            steps {
                // Aquí se pone el script de despliegue, por ejemplo:
                sh '''
                  # Copiar contenedores a producción o reiniciar servicios
                  docker-compose down
                  docker-compose up -d --build
                '''
            }
        }
    }

    post {
        always {
            echo '🧹 Limpiando contenedores y volúmenes…'
            sh 'docker-compose down -v'
        }
        success {
            echo '✅ Pipeline finalizado con éxito.'
        }
        failure {
            echo '❌ Algo falló en el pipeline.'
        }
    }
}
