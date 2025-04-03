<?php

	# Esta página é onde o tripulante aguarda por uma ocorrencia.
	# Assim que uma ocorrenca é encontrada uma modal é apresentada onde o tripulante pode aceitar ou rejeitar a ocorrencia.

	
	// Inclui validação da sessão
	include('code/session.php');
	
	// Se um Administradot tentar aceder a esta página redirecionar para a página de utilizadores (utilizadores/listar.php)
	if ($login_session_perfil == 10) {
		header("location: utilizadores/listar.php");
	}
	// Se um Médico tentar aceder a esta página redirecionar para a página de verbetes (historico.php)
	if ($login_session_perfil == 30){
		header("location: historico.php");
	}
?>
<!DOCTYPE html>
<html>

	<head>
		<title>Sistema de Apoio ao Tripulante de Ambulancia</title>
		<!-- Encoding -->
		<meta charset="utf-8" />
		<!-- Incluir jquery@2.0.3 -->
		<script src ="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
		<!-- Incluir bootstrap@3.3.2 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<!-- Styling -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
		<!-- Scripting -->	
		<script>
			// Periodicamente procura uma ocorrencia (chamando o ficheiro ocorrencia/procurar.php)
			// Se encontrar abre uma modal com os seus detalhes		
			setInterval(function() {
				$.get("ocorrencia/procurar.php", 
					function (response) {
						// Se uma ocorrencia for encontrada 
						if (response !== " Sem ocorrencias"){

							// Fazer parse da string retornada (json com os detalhes da ocorrencia)
							var ocorrencia = JSON.parse( response );

							// Carregar os detalhes da ocorrencia
							document.getElementById("id_ocorrencia").value = ocorrencia.id;
							document.getElementById("id_utilizador").value = ocorrencia.id_utilizador;
							document.getElementById("nr_codu").innerHTML = ocorrencia.codu;
							document.getElementById("descricao").innerHTML = ocorrencia.desc;
							document.getElementById("nome").innerHTML = ocorrencia.tipo_de_local;
							document.getElementById("local").innerHTML = ocorrencia.local;

							// Abrir modal para apresentar a ocorrencia
							$("#myModal").modal()
						}
					});
			}, 1000);

			// Comportamento dos btns aceitar/rejeitar (chamando o ficheiro ocorrencia/aceitar.php)
			$(document).ready(function(){
				$('.button').click(function() {
					// Pegar no id da ocorrencia, id do utilizador e ação (aceitar ou rejeitar)
					var id_ocorrencia = document.getElementById("id_ocorrencia").value;
					var id_utilizador = document.getElementById("id_utilizador").value;
					var accao = $(this).val();
					
					// Aceitar ou rejeitar uma ocorrencia 
					$.post("ocorrencia/aceitar.php", {"utilizador": id_utilizador, "ocorrencia": id_ocorrencia, "accao": accao}, function (response) {
						if (response){
							// Se uma ocorrencia foi aceite 
							if (response === " Aceitar") {
								// Redirecionar para a página do verbete
								window.location.replace("verbete.php?id=" + id_ocorrencia);
							}
						} else {
							console.log(response);
						}
					});
				});
			});

		</script>
	</head>

	<body>
		<!-- Header -->
		<?php include 'header.php'; ?>
	
		<!-- Body -->
		<section id="showcase">
			<div class="container">
				<div>
					<h1>Inicio</h1>
					<p>Aguarde uma ocorrência</p>
					</br>
						
					<!-- Modal a perguntar se deseja aceitar a ocorrencia -->
					<div class="modal fade" id="myModal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Ocorrência disponível...</h4>
								</div>
								<div class="modal-body">
									<input type="hidden" id="id_ocorrencia"/>
									<input type="hidden" id="id_utilizador"/>
									<p style="font-weight:bold;">Nº CODU:</p>
									<p id="nr_codu"></p>
									<p style="font-weight:bold;">Descrição:</p>
									<p id="descricao"></p>
									<p style="font-weight:bold;">Tipo:</p>
									<p id="nome"></p>
									<p style="font-weight:bold;">Local:</p>
									<p id="local"></p>
								</div>
								<div class="modal-footer">
									<input type="submit" class="button btn btn-danger"  data-dismiss="modal" name="Rejeitar" value="Rejeitar"/>
									<input type="submit" class="button btn btn-success" data-dismiss="modal" name="Aceitar"  value="Aceitar"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	
		<!-- Footer -->
		<?php include 'footer.php'; ?>
	</body>

</html>