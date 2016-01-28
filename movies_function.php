<?php

function verif_rent_movie($argv)
{
        $connect = new MongoClient();
        $db = $connect->db_etna;
        $collection = $db->students;
        $collection2 = $db->movies;
        if (isset($argv[2]) && isset($argv[3]))
        {
                $cursor = $collection->findOne(array("login" => $argv[2]));
                if (isset($cursor))
                {
                        $cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));
                        if (isset($cursor2))
			   return (1);
                        else
				echo "imdb_code incorrect \n";
		}
		else
			echo "Login incorrect ou n'est pas enregistré \n";
	}
	else
		echo "Pas assez d'arguments !\n";
	return (0);
}

function return_movie($argv)
{
	$tmp = verif_rent_movie($argv);
	if ($tmp == 1)
	{
		$ver = is_movie_rented($argv);
		if ($ver == 1)
		{
			up_user($argv);
			up_movie($argv);
			echo "Returned\n";
		}
	}
}

function is_movie_rented($argv)
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
				return (1);
			}
		else
			echo "Error: movie isn't rented by this user\n";
		}
		else
		echo "Error: movie isn't rented\n";
	}
	else
		echo "Error: this user haven't rented any movie\n";
	return(0);
}

function up_user($argv)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->students;
	$collection2 = $db->movies;
	
	$cursor = $collection->findOne(array("login" => $argv[2]));
	$cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));
	
	$array = explode( ', ', $cursor["rented_movies"]);
	$i = 0;
	$newupdate = " ";
	
	while (isset($array[$i]))
	{
		if ($array[$i] != $cursor2['_id']->{'$id'})
		{
			$newupdate .= $array[$i];
			if ($array[$i] != null)
			   $newupdate .= ", ";
		}
	$i++;
	}
	$newdata = array('$set' => array("rented_movies" => $newupdate));
	$collection->update(array("login" => $argv[2]), $newdata, array("upsert" => true));
}

function up_movie($argv)
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->students;
	$collection2 = $db->movies;
	
	$cursor = $collection->findOne(array("login" => $argv[2]));
	$cursor2 = $collection2->findOne(array("imdb_code" => $argv[3]));
	
	$array = explode( ', ', $cursor2["renting_students"]);
	$i = 0;
	$newupdate = " ";
	while (isset($array[$i]))
	{
		if ($array[$i] != $cursor['_id']->{'$id'})
		{
			$newupdate .= $array[$i];
			if ($array[$i] != null)
			   $newupdate .= ", ";
		}
	$i++;
	}
	$newdata = array('$set' => array("stock" => $cursor2["stock"] + 1));
	$newdata2 = array('$set' => array("renting_students" => $newupdate));
	
	$collection2->update(array("imdb_code" => $argv[3]), $newdata);
	$collection2->update(array("imdb_code" => $argv[3]), $newdata2, array("upsert" => true));
}

?>