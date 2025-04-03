<?php

    # Este ficheiro inicializa uma coneção à base de dados.
	# Deve ser re-utilizado em outros ficheiros.


	// Detalhes da coneção à base de dados
	$servername = "localhost";
	$username = "root";
	$password = "admin";
	$dbname = "SATA";
	
	// Inicialização da coneção
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	// Se não conectar à base de dados apresentar erro
	if (! $conn) {  
		echo "Connection failed" . mysqli_connect_error();
	}
?> 