<?php

    # Este ficheiro elimina a sessão do utilizador.


	// Inicia/continua uma sessão
	session_start();

	// Destroi a sessão
	if(session_destroy()) 
	{
		// Redirecionar para a página de login (index.php)
		header("Location: ../index.php");
	}
?>