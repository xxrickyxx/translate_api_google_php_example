<?php


$DB_host     = 'localhost';
$DB_user     = '';
$DB_password = '';
$DB_name     = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $link = new PDO("mysql:host=".$DB_host.";dbname=".$DB_name, $DB_user, $DB_password,$options);
} catch (Exception $e) {
    exit('Unable to connect to database.');
}

$sql ="SELECT * FROM smartphone";
$results = $link->query($sql); 
$smartphone = $results->fetchAll();



    $apiKey = '';

foreach ($smartphone as $key) {

    $descrizione = $key['Description'];


    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($descrizione) . '&source=en&target=it';


    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

 //   echo 'Source: ' . $text . '<br>';


  //  echo 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];


    $sql="UPDATE smartphone
SET Description='".$responseDecoded['data']['translations'][0]['translatedText']."'
WHERE id='".$key['id']."' ";
$results = $link->query($sql); 

}


?>
