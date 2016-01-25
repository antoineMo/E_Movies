<?php

$connect = new MongoClient();
$db = $connect->db_am;

$collection = $db->students;

$log = "login";
$document = array( $log => "moriss_b", "name" => "antoine", "age" => 20, "email" => "mail@mail.net", "phone" => "0101010101");
$collection->insert($document);

$cursor = $collection->find();

// traverse les résultats
foreach ($cursor as $document) {
   echo $document["login"] . "\n";
   }


?>
