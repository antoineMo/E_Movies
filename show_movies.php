<?php

function compare_t($a, $b)
{
	return strnatcmp($a['title'], $b['title']);
}

function show_movies_norm()
{
        $connect = new MongoClient();
        $db = $connect->db_etna;
        $collection = $db->movies;
        $cursor = $collection->find();

	$array = iterator_to_array($cursor);

	usort($array, 'compare_t');

	foreach ($array as $document)
        {
                echo "\nimdb_code : " . $document["imdb_code"] . "\n";
                echo "title     : " . $document["title"] . "\n";
                echo "year      : " . $document["year"] . "\n";
                echo "genres    : " . $document["genres"] . "\n";
                echo "directors : " . $document["directors"] . "\n";
                echo "rate      : " . $document["rate"] . "\n";
                echo "link      : " . $document["link"] . "\n";
                echo "stock     : " . $document["stock"] . "\n";
        }
}

?>