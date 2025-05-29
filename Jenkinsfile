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

        stage('Ejecutar Dusk') {
            steps {
                dir('') {
                    sh '''
                        if [ ! -f artisan ]; then
                          echo "❌ No se encontró el archivo artisan en $(pwd)"
                          ls -lah
                          exit 1
                        fi

                        docker run --rm \
                            -v "$PWD":/var/www/html \
                            -w /var/www/html \
                            dusk-runner \
                            php artisan dusk --headless --disable-gpu
                    '''
                }
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

