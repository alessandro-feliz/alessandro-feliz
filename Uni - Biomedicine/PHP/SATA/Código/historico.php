<?php 

    # Este ficheiro lista todos os verbetes da base de dados.
	# Apenas o médico pode aceder a esta página.


	// Inclui coneção à base de dados e valida a sessão
	require_once('code/db_config.php');
	require_once('code/session.php');
	
	// Se um Administradot tentar aceder a esta página redirecionar para a página de utilizadores (utilizadores/listar.php)
	if ($login_session_perfil == 10) {
		header("location: utilizadores/listar.php");
	}
	// Se um Tripulante tentar aceder a esta página redirecionar para a página de profile (profile.php)
	if ($login_session_perfil == 20) {
		header("location: ../profile.php");
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
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
	</head>

	<body>
		<!-- Header -->
		<?php include 'header.php';	?>

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

				// Calcular qual o primeiro verbete a ver
				$start_from = ($page-1) * $per_page_record;

				// Query que vai buscar os verbetes com base no primeiro verbete e nº de verbetes por página
				$query = "
SELECT o.id, o.nr_codu, o.data, v.nr_sns, v.nome 
FROM ocorrencia o
LEFT JOIN vitima AS v ON v.id_ocorrencia = o.id
WHERE o.id_estado_ocorrencia = 30
ORDER BY o.data DESC LIMIT $start_from, $per_page_record";

				// Execução da query
				$res = mysqli_query ($conn, $query);
			?>

			<div>
				<h1>Histórico</h1>
				<p>Histórico de Verbetes</p>

				<table class="table table-striped table-condensed table-bordered">
					<thead>
						<tr>
						<th width="20%">CODU</th>
						<th width="20%">Data de Ocorrência</th>
						<th>SNS</th>
						<th>Nome</th>
						<th width="10%">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while ($row = mysqli_fetch_array($res)) { ?>
							<tr id="<?php echo $row["id"]; ?>">
								<td><?php echo $row['nr_codu']; ?></td>
								<td><?php echo $row['data']; ?></td>
								<td><?php echo $row['nr_sns']; ?></td>
								<td><?php echo $row['nome']; ?></td>								
								<td>
									<button class="btn btn-info btn-sm view"><i class="fa fa-eye"></i></button>
								</td>
							</tr>
						<?php }; ?>
					</tbody>
				</table>

				<div class="pagination">
				<?php  
					// Vai buscar o total de verbetes
					$query = "SELECT COUNT(*) FROM ocorrencia WHERE id_estado_ocorrencia = 30;";
					$res = mysqli_query($conn, $query);
					$row = mysqli_fetch_row($res);
					$total_records = $row[0];

					echo "</br>";
					
					// Calcula o número de páginas necessárias
					$total_pages = ceil($total_records / $per_page_record);
					$pagLink = "";

					// Cria o controlo de paginação
					if($page>=2){
						echo "<a href='historico?page=".($page-1)."'>  Anterior </a>";
					}

					for ($i=1; $i<=$total_pages; $i++) {
						if ($i == $page) {
							$pagLink .= "<a class = 'active' href='historico?page=".$i."'>".$i." </a>";
						}
						else  {
							$pagLink .= "<a href='historico?page=".$i."'>".$i." </a>";
						}
					};
					echo $pagLink;

					if($page<$total_pages){
						echo "<a href='historico?page=".($page+1)."'>  Seguinte </a>";
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
		<?php include 'footer.php'; ?>
	</body>
				
	<script type="text/javascript">
		// Função para navegar para uma página de verbetes em particular
		function go2Page()
		{
			var page = document.getElementById("page").value;
			page = ((page > <?php echo $total_pages; ?>) ? <?php echo $total_pages; ?> : ((page < 1) ? 1 : page));
			window.location.href = 'historico?page=' + page;
		}

		// Função para visualizar um verbete (chamando o ficheiro verbete.php)
		$(".view").click(function(){
			var id = $(this).parents("tr").attr("id");
			window.location.href = "verbete.php?id=" + id;
		});
	</script>

</html>