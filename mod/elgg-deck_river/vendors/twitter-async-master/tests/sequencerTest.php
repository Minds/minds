<?php
include dirname(__FILE__).'/../EpiCurl.php';
include dirname(__FILE__).'/../EpiOAuth.php';
include dirname(__FILE__).'/../EpiTwitter.php';
include dirname(__FILE__).'/../EpiSequence.php';
$consumer_key = 'jdv3dsDhsYuJRlZFSuI2fg';
$consumer_secret = 'NNXamBsBFG8PnEmacYs0uCtbtsz346OJSod7Dl94';
$token = '25451974-uakRmTZxrSFQbkDjZnTAsxDO5o9kacz2LT6kqEHA';
$secret= 'CuQPQ1WqIdSJDTIkDUlXjHpbcRao9lcKhQHflqGE8';
$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObj->useAsynchronous(true);
?>

Test sequencing diagram of api calls

<?php
  $creds = array();
  $creds[] = $twitterObj->get('/direct_messages.json');
  $creds[] = $twitterObj->get('/users/suggestions.json');
  $creds[] = $twitterObj->get('/statuses/public_timeline.json');

  foreach($creds as $cred) {
    $cred->responseText;
  }

  echo EpiCurl::getSequence()->renderAscii();
?>
