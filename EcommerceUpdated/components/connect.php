<?php

$host = "localhost";
$dbName = "ecommerceupdated";
$userName = "root";
$password = "";

try
{
	$con = new PDO("mysql:host={$host};dbname={$dbName}",$userName,$password);
	//echo "Connection Good!";

	function create_unique_id()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strLen($characters);
		$randomString = '';
		for ($i=0; $i < 20; $i++) 
		{ 
			$randomString .= $characters[mt_rand(0, $charactersLength -1)];
			
		}
		return $randomString;

	}

	//echo $randomString = create_unique_id();
}

catch(PDOException $e)
{
	echo "Connection error; ".$e->getMessage();
}

?>
