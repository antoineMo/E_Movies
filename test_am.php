<?php

$connect = new MongoClient();
$db = $connect->db_am;

$collection = $db->students;

$log = "moriss_c";
$document = array( "login" => $log, "name" => "antoine", "age" => 20, "email" => "mail@mail.net", "phone" => "0101010101");
$collection->insert($document);

$newdata = array('$set' => array("age" => 30));
$collection->update(array("login" => "moriss_c"), $newdata);


$cursor = $collection->find();

// traverse les résultats
foreach ($cursor as $document) {
   echo $document["login"] . "\n";
}


?>
