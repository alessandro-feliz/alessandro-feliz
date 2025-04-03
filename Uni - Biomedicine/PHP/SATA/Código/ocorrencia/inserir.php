<?php

    # Este ficheiro cria uma ocorrenca na base de dados.
	# Deve ser chamado a partir de uma entidade externa (ex: CODU).
	# Para simular esta entidade externa é utilizado um Powershell Script.
	# Deveria ser feita a autenticação de quem chama a API.


	// Inclui coneção à base de dados
	require_once "../code/db_config.php";

	// Ir buscar dados enviados pelo POST
	$nr_codu = $_POST['nr_codu'];
	$data = date('Y-m-d H:i:s');
	$id_estado_ocorrencia = 10;
	$descricao = $_POST['descricao'];
	$id_tipo_de_local = $_POST['id_tipo_de_local'];
	$local = $_POST['local'];
	$id_concelho = $_POST['id_concelho'];
		echo "$id_tipo_de_local";
	// Query que insere uma ocorrencia pendente (estado 10) na base de dados
	$sql = "
INSERT INTO ocorrencia(id, nr_codu, data, id_estado_ocorrencia, descricao, id_tipo_de_local, local, id_concelho) 
VALUES (NULL,'$nr_codu','$data',$id_estado_ocorrencia,'$descricao',$id_tipo_de_local,'$local','$id_concelho');
";

	// Execução da query
	if (mysqli_query($conn, $sql)) {
		// Se inseriu, ir buscar id da ocorrencia
		$id_ocorrencia = mysqli_insert_id($conn);
			
		// Query que insere a vitima na base de dados
		$sql = "
INSERT INTO vitima ( id_ocorrencia,	nome, data_nascimento, id_sexo, nr_sns, residencia )
VALUES ($id_ocorrencia, null, null, 10, null, null);";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não criou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}
		
		$time = date('h:i:s');
		
		// Query que insere a avaliacao na base de dados
		$sql = "
INSERT INTO avaliacao ( id_ocorrencia, hora, id_avds, vent_cpm, sat_o2, sup_o2, exp_co2, pulso_bpm, id_ecg, pa_sist, pa_diast, id_pele, temp, id_pupilas_diametro, id_pupilas_reflexos, id_pupilas_simetria, dor, glicemia, news )
VALUES ($id_ocorrencia, '$time', 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 1, 1, 1, 0, 0, 0);
";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não criou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}
		
		// Query que insere o chamu na base de dados
		$sql = "
INSERT INTO chamu ( id_ocorrencia, circunstancias, doencas, alergias, medicacao, hora_ult_ref, ult_ref )
VALUES ($id_ocorrencia, '', '', '', '', null, '');
";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não criou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}	
		
		// Query que insere as anotacoes na base de dados
		$sql = "
INSERT INTO vitima_lesao ( id_ocorrencia )
VALUES ($id_ocorrencia);
";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não criou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
		}		
		
	} else {
		// Se não inseriu, retonar mensagem de erro
		echo "Erro: " . $sql . "	" . mysqli_error($conn);
	}

	// Fechar ligação à base de dados
	mysqli_close($conn);
?>
