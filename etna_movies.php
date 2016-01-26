<?php




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
				  "genres" => $tab[$i][15],
				  "directors" => $tab[$i][7],
				  "rate" => $tab[$i][9],
				  "link" => $tab[$i][15],
				  "stock" => $int
				  );
		$collection->insert($document);
	}
	echo $i . " films ajoutés !\n";
}

function verif_age()
{
	$str = readLine();
	while (preg_match_all("/^[0-9]{1,2}$/", $str) == 0)
	{
		echo "Age incorrect !\n> ";
		$str = readLine();
	}
	return ($str);
}

function verif_mail()
{
	$str = readLine();
	while (preg_match_all("/^[a-z0-9._-]+@[a-z0-9._-]+.[a-z]{2,4}$/", $str) == 0)
	{
		echo "Mail incorrect !\n> ";
		$str = readLine();
	}
	return ($str);
}

function verif_name()
{
	$str = readLine();
	while (preg_match_all("/^[ a-zA-Z-]+$/", $str) == 0)
	{
		echo "Nom incorrect !\n> ";
		$str = readLine();
	}
        return ($str);
}

function champ_vide()
{
	$str = readLine();
        while (strcmp($str, "") == 0)
	{
		echo "Champ obligatoire !\n> ";
                $str = readLine();
	}
	return ($str);
}

function verif_phone()
{
	$str = readLine();
	while (preg_match_all("/^0[1-68]([-. ]?[0-9]{2}){4}$/", $str) == 0)
	{
		echo "Numéro invalide !\n> ";
		$str = readLine();
	}
	return ($str);
}	

require_once('verif_movie.php');


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
	if (preg_match_all("/[a-z]{6}_[a-z0-9]/", $argv[2], $array))
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

function show_student($argv)
{
	$connect = new MongoClient();
        $db = $connect->db_etna;
	        $collection = $db->students;
	if (preg_match_all("/[a-z]{6}_[a-z0-9]/", $argv[2], $array))
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
		echo "Login incorrect !\n";
}

function verif($argv)
{
	if	(isset($argv[3]))
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