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
        stage("Valid") {
            steps {
                sh "make site-validate-schema"
            }
        }
        stage("Lint") {
            steps {
                sh "make site-cs-fix"
            }
        }
        stage("Test") {
            steps {
                sh "make site-test"
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
        stage("Push-Staging") {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'REGISTRY_AUTH',
                        usernameVariable: 'USER',
                        passwordVariable: 'PASSWORD'
                    )
                ]) {
                    sh 'docker login -u=$USER -p=$PASSWORD'
                }
                    sh "make push"
            }
        }
        stage("Staging-Deploy") {
            when {
                branch "staging"
            }
            steps {
                withCredentials([
                    string(credentialsId: 'STAGING_HOST', variable: 'HOST'),
                    string(credentialsId: 'STAGING_PORT', variable: 'PORT'),
                    string(credentialsId: 'STAGING_SHOP_APP_SECRET', variable: 'SHOP_APP_SECRET'),
                    string(credentialsId: 'STAGING_SHOP_DB_PG_PASSWORD', variable: 'SHOP_DB_PG_PASSWORD'),
                    string(credentialsId: 'STAGING_SHOP_MAIL_PASSWORD', variable: 'SHOP_MAIL_PASSWORD'),
                    string(credentialsId: 'STORAGE_FTP_PASSWORD', variable: 'STORAGE_FTP_PASSWORD'),
                    string(credentialsId: 'STAGING_SHOP_SENTRY_DSN', variable: 'SHOP_SENTRY_DSN'),
                    string(credentialsId: 'STAGING_REDIS_PASSWORD', variable: 'SHOP_REDIS_PASSWORD')
                ]) {
                    sshagent (credentials: ['STAGING_AUTH']) {
                        sh "make deploy-staging"
                    }
                }
            }
        }
        stage("Prod-Deploy") {
            when {
                branch "master"
            }
            steps {
                withCredentials([
                    string(credentialsId: 'PROD_HOST', variable: 'HOST'),
                    string(credentialsId: 'PROD_PORT', variable: 'PORT'),
                    string(credentialsId: 'PROD_SHOP_APP_SECRET', variable: 'SHOP_APP_SECRET'),
                    string(credentialsId: 'PROD_SHOP_DB_PG_PASSWORD', variable: 'SHOP_DB_PG_PASSWORD'),
                    string(credentialsId: 'PROD_SHOP_MAIL_PASSWORD', variable: 'SHOP_MAIL_PASSWORD'),
                    string(credentialsId: 'PROD_STORAGE_FTP_PASSWORD', variable: 'STORAGE_FTP_PASSWORD'),
                    string(credentialsId: 'PROD_SHOP_SENTRY_DSN', variable: 'SHOP_SENTRY_DSN'),
                    string(credentialsId: 'PROD_REDIS_PASSWORD', variable: 'SHOP_REDIS_PASSWORD'),
                    string(credentialsId: 'PROD_ECONT_USER', variable: 'ECONT_USER'),
                    string(credentialsId: 'PROD_ECONT_PASSWORD', variable: 'ECONT_PASSWORD'),
                    string(credentialsId: 'PROD_RABBITMQ_PASS', variable: 'RABBITMQ_PASS')
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
            sh 'make deploy-staging-clean || true'
            sh 'make deploy-clean || true'
            cleanWs()
        }
    }
}