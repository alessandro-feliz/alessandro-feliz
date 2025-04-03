<?php 

    # Este ficheiro elimina o utilizador com o id indicado.	
	# Apenas o administrador pode aceder a esta página.


	// Inclui coneção à base de dados e valida a sessão
	require_once('../code/db_config.php');
	require_once('../code/session.php');
			
	// Se um Tripulante tentar aceder a esta página redirecionar para a página de profile (profile.php)
	if ($login_session_perfil == 20) {
		header("location: ../profile.php");
	}
	// Se um Médico tentar aceder a esta página redirecionar para a página de verbetes (historico.php)
	if ($login_session_perfil == 30){
		header("location: ../historico.php");
	}

	// Valida se um id de utilizador foi indicado
	if(isset($_GET['id']))
	{
		// Ir buscar dados enviados pelo GET
		$id = $_GET['id'];
		
		// Query que atualiza o utilizador colocando-o como inativo
		$sql = "UPDATE utilizador SET ativo='0' WHERE id=$id";

		// Execução da query
		if ($conn->query($sql)) {
			// Se eliminou, retonar mensagem de sucesso
			echo 'Eliminado';
		} else {
			// Se não eliminou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}
	}

?>