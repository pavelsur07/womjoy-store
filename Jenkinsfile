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
        stage("Deploy") {
            when {
                branch "master"
            }
            steps {
                withCredentials([
                    string(credentialsId: 'PROD_HOST', variable: 'HOST'),
                    string(credentialsId: 'PROD_PORT', variable: 'PORT'),
                    string(credentialsId: 'PROD_APP_SECRET', variable: 'APP_SECRET'),
                    string(credentialsId: 'PROD_SENTRY_DSN', variable: 'SENTRY_DSN'),
                    string(credentialsId: 'PROD_DB_PASSWORD', variable: 'DB_PASSWORD'),
                    string(credentialsId: 'PROD_REDIS_PASSWORD', variable: 'REDIS_PASSWORD')
                ]) {
                    sshagent (credentials: ['PROD_AUTH']) {
                        sh 'make deploy'
                    }
                }
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