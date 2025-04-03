<?php

    # Este ficheiro procura uma ocorrencia pendente (estado 10).


	// Inclui coneção à base de dados e valida sessão
	require_once "../code/db_config.php";
	require_once "../code/session.php";

	// Query que procura uma ocorrencia pendente (estado 10) na base de dados
	$query = "
	SELECT o.id, nr_codu, descricao, o.id_tipo_de_local, tl.nome, o.local
      FROM ocorrencia AS o 
INNER JOIN tipo_de_local AS tl ON (o.id_tipo_de_local = tl.id)
     WHERE o.id_estado_ocorrencia = 10 
	 LIMIT 1";

	// Execução da query
	$result = $conn->query($query);

	// Validar se a execução da query retornou resultados
	if ($result->num_rows > 0) {
		// Se encontrou uma ocorrencia, ir buscar detalhes
		$row = $result->fetch_assoc();

		// Gerar json com todos os dados e retornar
		echo "
{
\"id\":{$row['id']},
\"id_utilizador\":{$login_session_id},
\"codu\": {$row['nr_codu']}, 
\"desc\": \"{$row['descricao']}\",
\"id_tipo_de_local\":{$row['id_tipo_de_local']},
\"tipo_de_local\":\"{$row['nome']}\",
\"local\":\"{$row['local']}\"
}";
	} else {
		// Se não encontrou, retornar mensagem a indicar
		echo "Sem ocorrencias";
	}

	// Fechar ligação à base de dados
	mysqli_close($conn);
?>
