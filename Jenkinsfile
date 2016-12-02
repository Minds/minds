node {
    
    stage 'Checkout'
        checkout scm
        //Get dependencies
        sh '/var/www/composer.phar install'
  
    stage 'Test'
   
        sh 'bin/phpspec run'

}
