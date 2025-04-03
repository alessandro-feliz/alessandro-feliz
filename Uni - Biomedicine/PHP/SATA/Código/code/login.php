<?php

    # Este ficheiro realiza o login validando as credenciais na base de dados.
	# Esta lógica é chamada a partir da página index.php


	// Inclui coneção à base de dados
	require_once "db_config.php";

	// Inicia/continua uma sessão
	session_start();
	
	// Inicializar mensagem de erro como vazia
	$error = "";

	// Valida se este ficheiro é chamado a partir de um POST
	if (isset($_POST['submit'])) {
		// Valida se as credenciais (nome de utilizador e password) foram introduzidas
		if (empty($_POST['username']) || empty($_POST['password'])) {
			// Se não foram introduzidas, apresentar erro
			$error = "Nome de utilizador ou palavra-chave inválidas";
		}
		else
		{
			// Definir credenciais introduzidas pelo utilizador
			$username=$_POST['username'];
			$password=$_POST['password'];

			// Remover caracteres especiais
			$username = stripslashes($username);
			$password = stripslashes($password);
			$username = mysqli_real_escape_string($conn, $username);
			$password = mysqli_real_escape_string($conn, $password);

			// Query que valida a existência do utilizador na base de dados
			$query = "SELECT COUNT(*) FROM utilizador WHERE password='$password' AND username='$username' AND ativo = true";

			// Execução da query
			$res = mysqli_query($conn, $query);
			$row = mysqli_fetch_row($res);
			$total_records = $row[0];

			// Fechar ligação à base de dados
			mysqli_close($conn);
			
			// Se utilizador existir, guardar em sessão e redirecionar para a página de perfil (profile.php)
			if ($total_records == 1) {
				// Guardar em sessão
				$_SESSION['login_user']=$username;
				// Redirecionar para a página de perfil (profile.php)
				header("location: profile.php");
			} else {
				// Se o utilizador nã existir, apresentar erro
				$error = "Nome de utilizador ou palavra-chave inválidas";
			}			
		}
	}
?>