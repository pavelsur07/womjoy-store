pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        IMAGE_TAG = sh(
            returnStdout: true,
            script: "echo '${env.BUILD_TAG}' | sed 's/%2F/-/g'"
        ).trim()

    }
    stages {
        stage("Init") {
            steps {
                sh "make init"
            }
        }
    }
    post {
        always {
            sh "make docker-down-clear || true"
            sh 'make deploy-clean || true'
            cleanWs()
        }
    }
}