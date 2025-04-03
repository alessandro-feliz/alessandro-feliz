<?php 

    # Este ficheiro permite visualizar o utilizador com o id indicado.
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

		// Query que seleciona o utilizador indicado
		$query = "SELECT * FROM utilizador u WHERE u.id = $id";

		// Execução da query
		$res = mysqli_query ($conn, $query);
		$row = mysqli_fetch_array($res);

		// Ir buscar dados à base de dados
		$nome = $row['nome'];
		$username = $row['username'];
		$email = $row['email'];
		$codigo = $row['codigo'];
		$id_perfil = $row['id_perfil'];
		if($id_perfil == "10"){
			$perfil = "Administrador";
		}
		if($id_perfil == "20"){
			$perfil = "Tripulante";
		}
		if($id_perfil == "30"){
			$perfil = "Médico";
		}
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
				<p>Visualizar Utilizador</p>
				</br>
				
				<div class="row">
					<div class="col-sm-4">

						<label for="username" style="margin-top: 0px;">Nome de utilizador</label>
						<input class = "form-control" type="text" name="username" 
							   value="<?php echo htmlspecialchars($username)?>" disabled />

						<label for="nome" style="margin-top: 10px;">Nome</label>
						<input class = "form-control" type="text" name="nome" 
						       value="<?php echo htmlspecialchars($nome)?>" disabled />

						<label for="email" style="margin-top: 10px;">Email</label>
						<input class = "form-control" type="email" name="email" 
							   value="<?php echo htmlspecialchars($email)?>" disabled />

						<label for="codigo" style="margin-top: 10px;">Código</label>
						<input class = "form-control" type="text" name="codigo" 
							   value="<?php echo htmlspecialchars($codigo)?>" disabled />

						<label for="perfil" style="margin-top: 10px;">Perfil</label>
						<input class = "form-control" type="text" name="perfil" 
							   value="<?php echo htmlspecialchars($perfil)?>" disabled />

						<div style="text-align: left; margin-top: 20px;">
							<button class="btn btn-info voltar"/>Voltar</button> 
						</div>
					</div>
				</div>
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