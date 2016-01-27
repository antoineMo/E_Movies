<?php

require_once('verif_movie.php');
require_once('other_func.php');
require_once('show_movies.php');

function movies_storing($argv)
{
	$connect = new MongoClient();
        $db = $connect->db_etna;
	$collection = $db->movies;
        $file = "movies.csv";
	if (is_readable($file) == true)
	{
		$handle = fopen($file, "r");
		while (($array = fgetcsv($handle, 1000, ",")) !== false)
		      $tab[] = $array;
        }
	for ($i = 1; isset($tab[$i]); $i++)
	{
		$int = rand(0, 5);
		$document = array( "imdb_code" => $tab[$i][1],
			    	  "title" => $tab[$i][5],
				  "year" => $tab[$i][11],
				  "genres" => $tab[$i][12],
				  "directors" => $tab[$i][7],
				  "rate" => $tab[$i][9],
				  "link" => $tab[$i][15],
				  "stock" => $int
				  );
		$collection->insert($document);
	}
	echo $i . " films ajoutés !\n";
}

function show_movies($argv)
{
	if (!isset($argv[2]))
		show_movies_norm(1);
	else if ($argv[2] == "desc")
	     	show_movies_norm(-1);
	else if ($argv[2] == "genre" && isset($argv[3]))
	     	show_movie_genre($argv[3]);
	else if ($argv[2] == "year")
	     	show_movie_year($argv[3] && isset($argv[3]));
	else
	echo "arguments invalides !\n";
}


function del_student($argv)
{
	if (!isset($argv[2]))
	{
		echo "Pas assez d'argument\n";
		return (0);
	}
	$connect = new MongoClient();
	$db = $connect->db_etna;
        $collection = $db->students;
	$cursor = $collection->findOne(array("login" => $argv[2]));
        if (isset($cursor))
	{
		echo "Are you sure ? oui/non\n> ";
		$str = readLine();
		if (strcmp($str, "oui") == 0 || strcmp($str, "yes") == 0)
		{
			$collection->remove(array('login' => $argv[2]));
			echo "L'utilisateur a été supprimé\n";
		}
		else if (strcmp($str, "non") == 0 || strcmp($str, "no") == 0)
		echo "L'utilisateur n'a pas été supprimé\n";
		else
		{
			echo "Commande incorrect !\n\n";
			del_student($argv);
		}
	}
	else
	 echo "Cet utilisateur n'est pas enregistré !\n";
}

function add_student($argv)
{
	if (!isset($argv[2]))
	{
		echo "Pas assez d'argument\n";
		return (0);
	}
	if (preg_match_all("/[a-z]{1,6}_[a-z0-9]/", $argv[2], $array))
	{
		echo "Nom ?\n> ";
		$name = verif_name();
		echo "Age ?\n> ";
		$age = verif_age();
		echo "Email ?\n> ";
		$mail = verif_mail();
		echo "Numéro de téléphone ?\n> ";
		$phone = verif_phone();
		echo "Utilisateur enregistré \n";

		$connect = new MongoClient();
		$db = $connect->db_etna;
		$collection = $db->students;

		$document = array( "login" => $argv[2], "name" => $name, "age" => $age, "email" => $mail, "phone" => $phone);
		$collection->insert($document);
	}
	else
		echo "Login incorrect !\n";
}

function verif($argv)
{
	if	(isset($argv[4]))
	   echo "Trop d'arguments !\n";
	else if (isset($argv[1]))
	{
		$ptr = $argv[1];
		if (function_exists($ptr) == true)
		   $ptr($argv);
		else
		   echo "Argument incorrect\n";
	
	}
	else
	echo "Pas assez d'arguemtns !\n";
	

}

verif($argv);
?>