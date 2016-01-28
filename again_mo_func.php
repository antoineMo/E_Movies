<?php

function rent_movie($argv)
{
	$tmp = verif_rent_movie($argv);
	if ($tmp == 1)
	{
		$connect = new MongoClient();
		$db = $connect->db_etna;
		$collection = $db->students;
		$collection2 = $db->movies;
		$cursor = $collection->findOne(array("login" => $argv[2]));
		$cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));
		if ($cursor2["stock"] == 0)
		   echo "Stock-out !\n";
		else {
		     $newdata = array('$set' => array("stock" => $cursor2["stock"] - 1));
		     if (isset($cursor2["renting_students"]))
		     $newdata2 = array('$set' => array("renting_students" => $cursor2["renting_students"] . $cursor['_id']->{'$id'} . ", "));
		else
			$newdata2 = array('$set' => array("renting_students" => $cursor['_id']->{'$id'} . ", "));
			$collection2->update(array("imdb_code" => $argv[3]), $newdata);
			$collection2->update(array("imdb_code" => $argv[3]), $newdata2, array("upsert" => true));
		if (isset($cursor["rented_movies"]))
		   $newdata3 = array('$set' => array("rented_movies" => $cursor["rented_movies"] . $cursor2['_id']->{'$id'} . ", "));
		else
			$newdata3 = array('$set' => array("rented_movies" => $cursor2['_id']->{'$id'} . ", "));
			$collection->update(array("login" => $argv[2]), $newdata3, array("upsert" => true));
		}
	}
}


?>