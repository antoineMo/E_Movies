<?php

function show_it($cursor)
{
	$i = 0;
	foreach ($cursor as $document)
        {
                echo "\nimdb_code : " . $document["imdb_code"] . "\n";
                echo "title     : " . $document["title"] . "\n";
                echo "year      : " . $document["year"] . "\n";
                echo "genres    : " . $document["genres"] . "\n";
                echo "directors : " . $document["directors"] . "\n";
                echo "rate      : " . $document["rate"] . "\n";
                echo "link      : " . $document["link"] . "\n";
                echo "stock     : " . $document["stock"] . "\n";
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
	$cursor = $collection->find(array('year' => $year));

	$cursor->sort(array('title' => 1));

	show_it($cursor);
}

function show_movie_rate($rate)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->movies;

	$intrate = intval($rate);
	$cursor = $collection->find(array('rate' => new MongoRegex("/^$intrate/i")));

	$cursor->sort(array('title' => 1));

	show_it($cursor);

}

?>