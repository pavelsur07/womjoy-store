pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        REGISTRY = credentials('REGISTRY')
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
        stage("Down") {
            steps {
                sh "make docker-down-clear"
            }
        }
        stage("Build") {
            steps {
                sh "make build"
            }
        }
        stage("Push") {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'DOCKER_HUB_AUTH',
                        usernameVariable: 'USER',
                        passwordVariable: 'PASSWORD'
                    )
                ]) {
                    sh 'docker login -u=$USER -p=$PASSWORD'
                }
                    sh "make push"
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