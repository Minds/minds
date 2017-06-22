pipeline {
    agent any

    stages {    
        stage('Checkout'){
            steps {
                checkout scm
                //Get dependencies
                sh '/var/www/composer.phar install'
            }
        }

        stage('Test'){
            steps {
                sh 'bin/phpspec run'
            }
        }

        stage('Deploy'){
            when {
                expression {
                    GIT_BRANCH = 'origin/' + sh(returnStdout: true, script: 'git rev-parse --abbrev-ref HEAD').trim()
                    return GIT_BRANCH == 'origin/master'
                }
            }
            steps {
                input "Deploy?"
                echo "Depoying to production";
            }
        }
    }

}
