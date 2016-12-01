node {
    
    stage 'Checkout'
        //Get dependencies
        sh '/var/www/composer.phar install'
  
    stage 'Test'
   
        sh 'bin/phpspec run'

}
