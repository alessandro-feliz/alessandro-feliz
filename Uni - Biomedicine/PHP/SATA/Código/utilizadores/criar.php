<?php

    # Este ficheiro cria um utilizador na base de dados.
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

	// Inicializar variáveis
	$erros = array('username' =>'', 'nome' =>'', 'email' =>'', 'codigo' =>'', 'password' =>'', 'confirmar_password' =>'');
	$username = $nome = $email = $codigo = $password = $confirmar_password = '';

	// Validar se o POST foi chamado a partir do botão 
	if(isset($_POST['registarUtilizador'])){

		// Ir buscar dados enviados pelo POST
		$nome = $_POST['nome'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$codigo = $_POST['codigo'];
		$password = $_POST['password'];
		$confirmar_password = $_POST['confirmar_password'];
		$id_perfil = $_POST['id_perfil'];

		// Validar se o nome apenas contem letras e espaços
		if(!preg_match('/^[a-zA-Z\s]+$/', $nome)){
			$erros['nome'] = 'O nome só deve conter letras e espaços.';
		}

		// Validar se ambas as passwords são iguais
		if(strcmp($password, $confirmar_password) !== 0){
			$erros['confirmar_password'] = 'As palavras-chave são diferentes.';								
		}

		// Validar se existe algum utilizador com o nome de utilizador indicado 
		$query = "SELECT COUNT(*) FROM utilizador WHERE username = '$username' AND ativo ='1'";
		$res = mysqli_query ($conn, $query);
		$row = mysqli_fetch_row($res);
		$total_records = $row[0];
		if ($total_records > 0) { $erros['username'] = 'Nome de Utilizador já existe.'; }

		// Validar se existe algum utilizador com o email indicado 
		$query = "SELECT COUNT(*) FROM utilizador WHERE email = '$email' AND ativo ='1'";
		$res = mysqli_query ($conn, $query);
		$row = mysqli_fetch_row($res);
		$total_records = $row[0];
		if ($total_records > 0) { $erros['email'] = 'Email já existe.'; }

		// Validar se existe algum utilizador com o código indicado 
		$query = "SELECT COUNT(*) FROM utilizador WHERE codigo = '$codigo' AND ativo ='1'";
		$res = mysqli_query ($conn, $query);
		$row = mysqli_fetch_row($res);
		$total_records = $row[0];
		if ($total_records > 0) { $erros['codigo'] = 'Código já existe.';  }

		// Se não existir nenhum erro
		if (!array_filter($erros)) {
			// Remover caracteres especiais
			$nome = mysqli_real_escape_string($conn, $nome);
			$username = mysqli_real_escape_string($conn, $username);
			$email = mysqli_real_escape_string($conn, $email);
			$codigo = mysqli_real_escape_string($conn, $codigo);
			$password = mysqli_real_escape_string($conn, $password);
			$id_perfil = mysqli_real_escape_string($conn, $id_perfil);
			
			// Query que insere o utilizador
			$sql = "
INSERT INTO utilizador( id_perfil, username, password, email, nome, codigo, ativo )
VALUES('$id_perfil', '$username', '$password', '$email', '$nome', '$codigo', '1')";

			// Execução da query
			if (mysqli_query($conn, $sql)) {
				// Se criou, redirecionar para a listagem de utilizadores
				header("location: listar.php");
			} else {
				// Se não criou, retonar mensagem de erro
				echo "Erro: " . $sql . "	" . mysqli_error($conn);
			}
		}
		
		// Fechar ligação à base de dados
		mysqli_close($conn);		
	}
?>
<!DOCTYPE html>
<html>

	<head>
		<title>Sistema de Apoio ao Tripulante de Ambulancia</title>
		<!-- Encoding -->
		<meta charset="utf-8" />
		<!-- Including jquery@2.0.3 -->
		<script src ="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
		<!-- Including bootstrap@3.3.2 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<!-- Fontawesome icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!-- Styling -->
		<link rel="stylesheet" type="text/css" media="screen" href="../css/style.css" />
	</head>

	<body>
		<!-- Header -->
		<?php include '../header.php'; ?>

		<!-- Body -->
		<section id="showcase">
			<div class="container">
				<h1>Utilizadores</h1>
				<p>Criar Utilizador</p>
				</br>
				<form action="criar.php" class="form" method="POST">
					<div class="row">
						<div class="col-sm-4">
						
							<label for="username">Nome de utilizador</label>
							<input class = "form-control" type="text" name="username" 
								   value="<?php echo htmlspecialchars($username)?>" 
								   placeholder="" autocomplete="off" required
								   oninvalid="this.setCustomValidity('Nome de utilizador obrigatório')" oninput="this.setCustomValidity('')">
							<?php echo '<p style="color: red">' .$erros['username']. '</p>'; ?>

							<label for="nome">Nome</label>
							<input class = "form-control" type="text" name="nome" 
							       value="<?php echo htmlspecialchars($nome)?>" 
								   placeholder="" autocomplete="off" required
								   oninvalid="this.setCustomValidity('Nome obrigatório')" oninput="this.setCustomValidity('')">
							<?php echo '<p style="color: red">' .$erros['nome']. '</p>'; ?>

							<label for="email">Email</label>
							<input class = "form-control" type="email" name="email" 
								   value="<?php echo htmlspecialchars($email)?>" 
								   placeholder="" autocomplete="off"  required
								   oninvalid="this.setCustomValidity('Email obrigatório')" oninput="this.setCustomValidity('')">
							<?php echo '<p style="color: red">' .$erros['email']. '</p>'; ?>

							<label for="codigo">Código</label>
							<input class = "form-control" type="text" name="codigo" 
								   value="<?php echo htmlspecialchars($codigo)?>" 
								   placeholder="" autocomplete="off" required
								   oninvalid="this.setCustomValidity('Código obrigatório')" oninput="this.setCustomValidity('')">
							<?php echo '<p style="color: red">' .$erros['codigo']. '</p>'; ?>

							<label for="perfil">Perfil</label>
							<select name="id_perfil" class = "form-control">
								<option value="20">Tripulante</option>
								<option value="30">Médico</option>
								<option value="10">Administrador</option>
							</select>

							<label for="password" style="margin-top: 10px;">Palavra-chave</label>
							<input class = "form-control" type="password" name="password"
								   value="" placeholder="introduza a palavra-chave" autocomplete="off" required
								   oninvalid="this.setCustomValidity('Palavra-chave obrigatória')" oninput="this.setCustomValidity('')">

							<label for="password2" style="margin-top: 10px;">Reintroduza a palavra-chave</label>
							<input class = "form-control" type="password" name="confirmar_password" 
								   value="" placeholder="reintroduza a palavra-passe" autocomplete="off" required 
								   oninvalid="this.setCustomValidity('Palavra-chave obrigatória')" oninput="this.setCustomValidity('')">
							<?php echo '<p style="color: red;">' .$erros['confirmar_password']. '</p>'; ?>

							<div class="row" style="margin-top: 20px;"> 
								<div class="col-md-6" style="text-align: left;">
									<button class="btn btn-info voltar"/>Voltar</button> 
								</div>
								<div class="col-md-6" style="text-align: right;">
									<input class="btn btn-info" type="submit" name="registarUtilizador" value="Criar" />
								</div>
							</div>
						</div>
					</div>	
				</form>
			</div>
		</section>	

		<!-- Footer -->
		<?php include '../footer.php'; ?>
	</body>	

	<script type="text/javascript">
		// Função para voltar à listagem de utilizadores
		$(".voltar").click(function(){
			window.location.href = "listar.php";
		});
	</script>
</html>