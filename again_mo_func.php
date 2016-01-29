<?php

function show_rented_movies($argv)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->movies;
	$collection2 = $db->students;

	$cursor = $collection->find(
	array('renting_students' => new MongoRegex("/,/i")));
	$cursor->sort(array('title' => 1));

	show_it_rented($cursor);
}

function show_it_rented($cursor)
{
	$i = 0;
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection2 = $db->students;
	foreach ($cursor as $document) {
		echo "\nimdb_code : " . $document["imdb_code"] . "\n";
		echo "title     : " . $document["title"] . "\n";
		echo "year      : " . $document["year"] . "\ngenres    : ";
		foreach ($document["genres"] as $sous_genre)
			echo $sous_genre . ", ";
		echo "\nrate      : " . $document["rate"] . "\n";
		echo "stock     : " . $document["stock"] . "\n";
		echo "borrower  : ";
		$array = explode( ', ', $document["renting_students"]);
		foreach ($array as $debt)	{
			if ($debt != null) {
				$cursor2 = $collection2->findOne(array('_id' => new MongoId($debt)));
				echo $cursor2['login'] . ", ";
				echo "\n\n--------------------------------------------------------";
			}
		}
		echo "\n";
		$i++;
	}
	echo "*" . $i . "*\n";
}

function rent_movie($argv)
{
	$ver = 1;
	$tmp = verif_rent_movie($argv);
	if ($tmp == 1)
	{
		$connect = new MongoClient();
		$db = $connect->db_etna;
		$collection = $db->movies;

		$cursor = $collection->findOne(array("imdb_code" => $argv[3]));
		$ver = movie_al_rented($argv);

		if ($cursor["stock"] == 0)
		{
			echo "Stock-out !\n";
		}

		else if ($ver == 0)
		{
			rent_this_movie($argv);
			echo "Rented !\n";
		}
	}
}

function movie_al_rented($argv)
{
        $connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->students;
	$collection2 = $db->movies;
	$cursor = $collection->findOne(array("login" => $argv[2]));
	$cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));
	if (isset($cursor["rented_movies"]))
	{
		$array = explode( ', ', $cursor["rented_movies"]);
		if (isset($cursor2["renting_students"]))
		{
			if (in_array($cursor2['_id']->{'$id'}, $array))
			{
				echo "you already have this one !\n";
				return (1);
			}
		}
	}
	return(0);
}

function rent_this_movie($argv)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->students;
	$collection2 = $db->movies;

	$cursor = $collection->findOne(array("login" => $argv[2]));
	$cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));

	$newdata = array('$set' => array("stock" => $cursor2["stock"] - 1));
	if (isset($cursor2["renting_students"]))
		$newdata2 = array('$set' => array("renting_students" =>
			  $cursor2["renting_students"] . $cursor['_id']->{'$id'} . ", "));
	else
		$newdata2 = array('$set' => array("renting_students" =>
			  $cursor['_id']->{'$id'} . ", "));

	$collection2->update(array("imdb_code" => $argv[3]), $newdata);
	$collection2->update(array("imdb_code" => $argv[3]), $newdata2, array("upsert" => true));

	if (isset($cursor["rented_movies"]))
		$newdata3 = array('$set' => array("rented_movies" =>
			  $cursor["rented_movies"] . $cursor2['_id']->{'$id'} . ", "));
	else
		$newdata3 = array('$set' => array("rented_movies" => $cursor2['_id']->{'$id'} . ", "));
	$collection->update(array("login" => $argv[2]), $newdata3, array("upsert" => true));
}

?>
