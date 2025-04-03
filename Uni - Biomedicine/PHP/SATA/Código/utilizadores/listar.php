<?php

    # Este ficheiro lista todos os utilizadores da base de dados.
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
			
			<!-- Paginação baseada no trabalo de https://www.javatpoint.com/php-pagination -->
			<?php 				
				// Número de registos a apresentar por página
				$per_page_record = 5;  

				// Valida se estamos a ver alguma página em particular (guardada em sessão)
				if (isset($_GET["page"])) {
					// Se sim, especificar essa página
					$page  = $_GET["page"];
				}
				else {
					// Se não, assumir a primeira página por defeito
					$page = 1;
				}

				// Calcular qual o primeiro utilizador a ver
				$start_from = ($page-1) * $per_page_record;

				// Query que vai buscar os utilizadores com base no primeiro utilizador e nº de utilizadores por página
				$query = "SELECT * FROM utilizador u, perfil p WHERE u.id_perfil = p.id AND ativo = true ORDER BY 1 LIMIT $start_from, $per_page_record";

				// Execução da query
				$res = mysqli_query ($conn, $query);
			?>

			<div>
				<h1>Utilizadores</h1>

				<div class="row">
					<div class="col-md-6">
						<p>Listagem de utilizadores</p>
					</div>
					<div class="col-md-6" style="text-align: right;">
						<form action="criar.php" class="form" method="POST">
							<input class="btn btn-info" type="submit" name="inserir" value="Criar utilizador" />
						</form>
					</div>
				</div>
				</br>
				
				<table class="table table-striped table-condensed table-bordered">
					<thead>
						<tr>
						<th width="10%">Código</th>
						<th>Nome</th>
						<th>Email</th>
						<th>Perfil</th>
						<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while ($row = mysqli_fetch_array($res)) { ?>
							<tr id="<?php echo $row["0"]; ?>">
								<td><?php echo $row["6"]; ?></td>
								<td><?php echo $row["5"]; ?></td>
								<td><?php echo $row["4"]; ?></td>
								<td><?php echo $row["9"]; ?></td>								
								<td>
									<button class="btn btn-info btn-sm view"><i class="fa fa-eye"></i></button>
									<?php
										if ($row["8"] != 10){	
											echo "<button style='margin: 5px' class='btn btn-info btn-sm edit'><i class='fa fa-pencil'></i></button>";
											echo "<button style='margin: 5px' class='btn btn-danger btn-sm remove'><i class='fa fa-trash'></i></button>";
										}
									?>
								</td>
							</tr>
						<?php }; ?>
					</tbody>
				</table>

				<div class="pagination">
				<?php  
					// Vai buscar o total de utilizadores
					$query = "SELECT COUNT(*) FROM utilizador WHERE ativo = true";
					$res = mysqli_query($conn, $query);
					$row = mysqli_fetch_row($res);
					$total_records = $row[0];

					echo "</br>";
					
					// Calcula o número de páginas necessárias
					$total_pages = ceil($total_records / $per_page_record);
					$pagLink = "";

					// Cria o controlo de paginação
					if($page>=2){
						echo "<a href='listar.php?page=".($page-1)."'>  Anterior </a>";
					}

					for ($i=1; $i<=$total_pages; $i++) {
						if ($i == $page) {
							$pagLink .= "<a class = 'active' href='listar.php?page=".$i."'>".$i." </a>";
						}
						else  {
							$pagLink .= "<a href='listar.php?page=".$i."'>".$i." </a>";
						}
					};
					echo $pagLink;

					if($page<$total_pages){
						echo "<a href='listar.php?page=".($page+1)."'>  Seguinte </a>";
					}
				?>
				</div>

				<div class="inline">
					<input id="page" type="number" min="1" max="<?php echo $total_pages?>" placeholder="<?php echo $page."/".$total_pages; ?>" required>
					<button onClick="go2Page();">Ir</button>
				</div>
			</div>

		</section>

		<!-- Footer -->
		<?php include '../footer.php'; ?>
	</body>
				
	<script type="text/javascript">
		// Função para navegar para uma página de utilizadores em particular
		function go2Page()
		{
			var page = document.getElementById("page").value;
			page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
			window.location.href = 'listar.php?page=' + page;
		}

		// Função para eliminar um utilizador (chamando o ficheiro eliminar.php)
		$(".remove").click(function(){
			var id = $(this).parents("tr").attr("id");

			if(confirm('Deseja eliminar este utilizador?'))
			{
				$.ajax({
					url: 'eliminar.php',
					type: 'GET',
					data: {id: id},
					error: function() {
						console.log('Não foi possível eliminar o utilizador');
					},
					success: function(data) {
						console.log(data);
						$("#"+id).remove();
						alert("Utilizador eliminado");  
					}
				});
			}
		});

		// Função para editar um utilizador (chamando o ficheiro editar.php)
		$(".edit").click(function(){
			var id = $(this).parents("tr").attr("id");
			window.location.href = "editar.php?id=" + id;
		});

		// Função para visualizar um utilizador (chamando o ficheiro visualizar.php)
		$(".view").click(function(){
			var id = $(this).parents("tr").attr("id");
			window.location.href = "visualizar.php?id=" + id;
		});
	</script>

</html>