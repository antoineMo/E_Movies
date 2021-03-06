<?php

function show_it($cursor)
{
	$i = 0;
	foreach ($cursor as $document)
        {
                echo "\nimdb_code : " . $document["imdb_code"] . "\n";
                echo "title     : " . $document["title"] . "\n";
                echo "year      : " . $document["year"] . "\n";
                echo "genres    : ";
		foreach ($document["genres"] as $sous_genre)
			echo $sous_genre . ", ";
                echo "\ndirectors : ";
		foreach ($document["directors"] as $sous_dir)
			echo $sous_dir . ", ";
                echo "\nrate      : " . $document["rate"] . "\n";
                echo "link      : " . $document["link"] . "\n";
                echo "stock     : " . $document["stock"] . "\n";
								echo "\n--------------------------------------------------\n";
		$i++;
        }
	echo "*" . $i . "*\n";
}

function show_movies_norm($x)
{

	$connect = new MongoClient();
       	$db = $connect->db_etna;
       	$collection = $db->movies;
       	$cursor = $collection->find();

	$cursor->sort(array('title' => $x));

	show_it($cursor);

}

function show_movie_genre($genre)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->movies;
	$cursor = $collection->find(array('genres' => new MongoRegex("/$genre/i")));


	$cursor->sort(array('title' => 1));

	show_it($cursor);

}

function show_movie_year($year)
{

	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->movies;
	$cursor = $collection->find(array('year' => intval($year)));

	$cursor->sort(array('title' => 1));

	show_it($cursor);
}

function show_movie_rate($rate)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->movies;

	$intrate = intval($rate);
	$where=array('rate' => array( '$gte' => $intrate, '$lt' => $intrate+1 ));
	$cursor = $collection->find($where);

	$cursor->sort(array('title' => 1));

	show_it($cursor);

}

?>
