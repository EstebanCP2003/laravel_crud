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

