<?php

	# Este ficheiro vai aceitar ou rejeitar uma ocorrencia, ou seja, vai alterar o seu estado para 20 (aceite) ou 50 (rejeitada).


	// Inclui coneção à base de dados
	require_once "../code/db_config.php";

	// Valida se preovidenciado um id de utilizador, id de ocorrencia e ação
	if(isset($_POST["utilizador"]) && isset($_POST["ocorrencia"]) && isset($_POST["accao"]))
	{	
		$utilizador = $_POST['utilizador'];
		$ocorrencia = $_POST['ocorrencia'];
		$accao = $_POST['accao'];
		$estado = 10;
		
		if($accao == "Aceitar"){
			$estado = 20;
		}
		
		if($accao == "Rejeitar"){
			$estado = 50;
		}

		// Query que atualiza o estado da ocorrencia na base de dados
		$sql = "
UPDATE ocorrencia
SET id_utilizador = $utilizador, id_estado_ocorrencia = $estado
WHERE id = $ocorrencia AND id_estado_ocorrencia = 10";

		// Execução da query
		if (mysqli_query($conn, $sql)) {
			// Se atualizou, retonar ação
			echo $accao;
		} else {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}

		// Fechar ligação à base de dados
		mysqli_close($conn);		
	}
?>
