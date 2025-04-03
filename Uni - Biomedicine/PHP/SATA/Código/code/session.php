<?php

    # Este ficheiro valida e inicializa a sessão do utilizador.
	# Esta validação é feita sempre que uma página que necessite de permissões é acedida.


	// Inclui coneção à base de dados
	require_once "db_config.php";
	
	// Inicia/continua uma sessão
	session_start();
	
	// Valida se existe algum utilizador guardado na sessão
	if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])) {
		// Utilizador guardado em sessão
		$user_check=$_SESSION['login_user'];

		// Query que valida a existência do utilizador na base de dados
		$query = "SELECT id, id_perfil, nome FROM utilizador WHERE username='$user_check' AND ativo = true";

		// Execução da query
		$res = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($res);
		$login_session_id = $row[0];
		$login_session_perfil = $row[1];
		$login_session = $row[2];


		// Se o utilizador indicado não existir na base de dados, redirecionar para a página de login (index.php)
		if(!isset($login_session)){
			// Fechar ligação à base de dados
			mysqli_close($conn);
			// Redirecionar para a página de login (index.php)
			header('Location: index.php');
		}
	} else {
		// Fechar ligação à base de dados
		mysqli_close($conn);
		// Se não exitir nenhum utilizador na sessão, redirecionar para a página de login (index.php)
		header('Location: index.php');
	}

?>