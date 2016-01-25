<?php

function connexion()
{
	$connect = new MongoClient();
	$db = $connect->db_etna;

	$collection = $db->students;
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

function add_student($argv)
{
	if (preg_match_all("/[a-z]{6}_[a-z0-9]/", $argv[2], $array))
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

function show_student($argv)
{
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
	else if	    (!isset($argv[2]))
	   echo "Pas assez d'arguments !\n";
	else if (isset($argv[1]) && isset($argv[2]))
	{
		$ptr = $argv[1];
		if (function_exists($ptr) == true)
		   $ptr($argv);
		else
		   echo "Argument incorrect\n";
	
	}

}

verif($argv);
?>