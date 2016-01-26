<?php

require_once('verif_movie.php');

function verif_update($str)
{	
	if ($str == "name")
	{
		echo "New name ? \n> ";
		$up = verif_name();
	}
	else if ($str == "age")
	{
		echo "New age ? \n> ";
		$up = verif_age();
	}
	else if ($str == "email")
	{
		echo "New email ? \n> ";
		$up = verif_mail();
	}
	else if ($str == "phone")
	{
		echo "New age ? \n> ";
		$up = verif_phone();
	}
	else
	{
		echo "cannot update\n";
		return (0);
	}
	return($up);
}

function update_student($argv)
{
	if (isset($argv[2]) && preg_match_all("/[a-z]{6}_[a-z0-9]/", $argv[2], $array))
	{
		echo "What do you want to update? \n> ";
		$str = readLine();
		$up = verif_update($str);
		
		if ($up != 0)
		{
			$connect = new MongoClient();
			$db = $connect->db_etna;
			$collection = $db->students;
			$newdata = array('$set' => array($str => $up));
			$collection->update(array("login" => $argv[2]), $newdata);
			echo "User informations modified !";
		}
	}
	else
		echo "Login incorrect !\n";
}

function show_all_student()
{
	$connect = new MongoClient();
	$db = $connect->db_etna;
	$collection = $db->students;
	$cursor = $collection->find();

	
	foreach ($cursor as $document)
	{
		echo "login : " . $document["login"] . "\n";
		echo "nom : " . $document["name"] . "\n";
		echo "age : " . $document["age"] . "\n";
		echo "email : " . $document["email"] . "\n";
		echo "phone : " . $document["phone"] . "\n";
	}


}

function show_student($argv)
{
	if (isset($argv[2]) && preg_match_all("/[a-z]{6}_[a-z0-9]/", $argv[2], $array))
	{

		$connect = new MongoClient();
		$db = $connect->db_etna;
		$collection = $db->students;
		$cursor = $collection->findOne(array("login" => $argv[2]));

		if (isset($cursor))
		{
			echo "login : " . $cursor["login"] . "\n";
			echo "nom : " . $cursor["name"] . "\n";
			echo "age : " . $cursor["age"] . "\n";
			echo "email : " . $cursor["email"] . "\n";
			echo "phone : " . $cursor["phone"] . "\n";
   		}
		else
			echo "khajiit stole nothing, khajiit is innocent of this crime\n";
	}

	else
		show_all_student();
}

?>