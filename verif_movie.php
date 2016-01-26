<?php

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

?>