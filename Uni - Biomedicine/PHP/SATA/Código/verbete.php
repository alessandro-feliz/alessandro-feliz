<?php 

    # Este ficheiro cria um verbete na base de dados.
	# Apenas o tripulante pode aceder a esta página.


	// Inclui coneção à base de dados e valida a sessão
	require_once('code/db_config.php');
	require_once('code/session.php');
	
	// Se for admin redirecionar para Utilizadores
	if ($login_session_perfil == 10) {
		header("location: utilizadores/listar.php");
	}
		
	// Validar se nova ocorrencia recebida
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];

		// Query que seleciona o utilizador indicado
		$query = "
SELECT
-- ocorrencia
o.id, o.nr_codu, o.data, o.id_utilizador, o.id_estado_ocorrencia, o.descricao, o.id_tipo_de_local, o.local, o.id_concelho,
o.hora_caminho_local, o.hora_chegada_local, o.hora_caminho_hosp, o.hora_chegada_hosp,
-- distrito
cc.id_distrito,
-- vitima
v.id as id_vitima, v.nome, v.data_nascimento, v.id_sexo, v.nr_sns, v.residencia,
-- chamu
c.id as id_chamu, c.circunstancias, c.doencas, c.alergias, c.medicacao, c.hora_ult_ref, c.ult_ref,
-- avaliacao
a.id as id_avaliacao, a.hora, a.id_avds, a.vent_cpm, a.sat_o2, a.sup_o2, a.exp_co2, a.pulso_bpm, 
a.id_ecg, a.pa_sist, a.pa_diast, a.id_pele, a.temp, a.id_pupilas_diametro, a.id_pupilas_reflexos, 
a.id_pupilas_simetria, a.dor, a.glicemia, a.news,
-- vitima lesao
vl.id as id_anot, vl.descricao as anot_desc
FROM 
ocorrencia o 
INNER JOIN vitima v ON v.id_ocorrencia = o.id
INNER JOIN chamu c ON c.id_ocorrencia = o.id
INNER JOIN avaliacao a ON a.id_ocorrencia = o.id
INNER JOIN vitima_lesao vl ON vl.id_ocorrencia = o.id
INNER JOIN concelho cc ON o.id_concelho = cc.id
WHERE o.id = $id;
";

		// Execução da query
		$res = mysqli_query ($conn, $query);
		$row = mysqli_fetch_array($res);

		// Ir buscar dados da ocorrencia à base de dados
		$nr_codu = $row['nr_codu'];
		$data = $row['data'];
		$descricao = $row['descricao'];
		$local = $row['local'];
		$id_tipo_de_local = $row['id_tipo_de_local'];
		$id_distrito = $row['id_distrito'];
		$id_concelho = $row['id_concelho'];
		$id_estado_ocorrencia = $row['id_estado_ocorrencia'];
		$hora_caminho_local = $row['hora_caminho_local'];
		$hora_chegada_local = $row['hora_chegada_local'];
		$hora_caminho_hosp = $row['hora_caminho_hosp'];
		$hora_chegada_hosp = $row['hora_chegada_hosp'];
		
		// Ir buscar dados da vitima à base de dados
		$id_vitima = $row['id_vitima'];
		$nome = $row['nome'];
		$data_nascimento = $row['data_nascimento'];
		$id_sexo = $row['id_sexo'];
		$nr_sns = $row['nr_sns'];
		$residencia = $row['residencia'];
		
		// Ir buscar dados do chamu à base de dados
		$id_chamu = $row['id_chamu'];
		$circunstancias = $row['circunstancias'];
		$doencas = $row['doencas'];
		$alergias = $row['alergias'];
		$medicacao = $row['medicacao'];
		$hora_ult_ref = $row['hora_ult_ref'];
		$ult_ref = $row['ult_ref'];
		
		// Ir buscar dados da avaliação à base de dados
		$id_avaliacao = $row['id_avaliacao'];
		$hora = $row['hora'];
		$id_avds = $row['id_avds'];
		$vent_cpm = $row['vent_cpm'];
		$sat_o2 = $row['sat_o2'];
		$sup_o2 = $row['sup_o2'];
		$exp_co2 = $row['exp_co2'];
		$pulso_bpm = $row['pulso_bpm'];
		$id_ecg = $row['id_ecg'];
		$pa_sist = $row['pa_sist'];
		$pa_diast = $row['pa_diast'];
		$id_pele = $row['id_pele'];
		$temp = $row['temp'];
		$id_pupilas_diametro = $row['id_pupilas_diametro'];
		$id_pupilas_reflexos = $row['id_pupilas_reflexos'];
		$id_pupilas_simetria = $row['id_pupilas_simetria'];
		$dor = $row['dor'];
		$glicemia = $row['glicemia'];
		$news = $row['news'];
		
		// Ir buscar dados das anotações à base de dados
		$id_anot = $row['id_anot'];
		$anot_desc = $row['anot_desc'];

		// Se o verbete estiver como finalizado, marcar como disabled
		$can_edit = "1";
		if($id_estado_ocorrencia == 30){
			$can_edit = "0";
		}
	}

	// Validar se o POST foi chamado a partir do botão 
	if(isset($_POST['submeter'])){
		
		$erro = false;

		// Ir buscar ids enviados pelo POST
		$id = $_POST['id_ocorrencia'];
		$id_vitima = $_POST['id_vitima'];
		$id_chamu = $_POST['id_chamu'];
		$id_avaliacao = $_POST['id_avaliacao'];
		$id_anot = $_POST['id_anot'];
		
		// Dados da ocorrencia
		$cam_local = !empty($_POST['cam-local']) ?     "'" . $_POST['cam-local'] . 	 ":00'" : 'null';
		$cheg_vit = !empty($_POST['cheg-vitima'])?     "'" . $_POST['cheg-vitima'] . ":00'" : 'null';
		$cam_usaude = !empty($_POST['cam-usaude']) ?   "'" . $_POST['cam-usaude'] .  ":00'" : 'null';
		$cheg_usaude = !empty($_POST['cheg-usaude']) ? "'" . $_POST['cheg-usaude'] . ":00'" : 'null';
		$motivo = $_POST['motivo'];
		$tipolocal = explode('-', $_POST['tipolocal'])[1];
		$concelho = $_POST['concelho'];
		$local = $_POST['local'];
		
		// Query para atualizar ocorrencia
		$sql = "
UPDATE ocorrencia
SET
	hora_caminho_local = $cam_local, hora_chegada_local = $cheg_vit,
	hora_chegada_hosp = $cam_usaude, hora_caminho_hosp = $cheg_usaude,
	descricao = '$motivo', id_concelho = $concelho,
	id_tipo_de_local = $tipolocal, local = '$local',
	id_estado_ocorrencia = 30
WHERE id = $id;
";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
			$erro = true;
		}
		
		// Dados da vitima
		$nome = $_POST['nome'];
		$data_nasc = !empty($_POST['data_nasc']) ? "'" . $_POST['data_nasc'] . "'" : 'null';
		$sexo = explode('-', $_POST['sexo'])[1];
		$num_sns = $_POST['num_sns'];
		$resid = $_POST['resid'];
	
		// Query para inserir vitima
		$sql = "
UPDATE vitima 
SET nome = '$nome', data_nascimento = $data_nasc, id_sexo = $sexo, 
nr_sns = '$num_sns', residencia = '$resid'
WHERE id = $id_vitima;
";
	
		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
			$erro = true;
		}
		
		// Dados da avaliacao
		$hora1 = !empty($_POST['hora1']) ? "'" . $_POST['hora1'] . "'" : 'null';
		$vent1 = $_POST['vent1'];
		$spo21 = $_POST['spo21'];
		$o2sup1 = $_POST['o2sup1'];
		$etco21 = $_POST['etco21'];
		
		$pulso1 = $_POST['pulso1'];
		$parterialsist1 = $_POST['parterialsist1'];
		$parterialdiast1 = $_POST['parterialdiast1'];
		$pele1 =  explode('-', $_POST['pele1'])[1];
		$temperatura1 = $_POST['temperatura1'];
		
		$avds1 =  explode('-', $_POST['avds1'])[1];
		$dor1 = $_POST['dor1'];
		$ecg1 =  explode('-', $_POST['ecg1'])[1];
		
		$dpupilas1 =  explode('-', $_POST['dpupilas1'])[1];
		$rpupilas1 =  explode('-', $_POST['rpupilas1'])[1];
		$spupilas1 =  explode('-', $_POST['spupilas1'])[1];

		// Query para inserir avaliação
		$sql = "
UPDATE avaliacao
SET 
	hora = $hora1, id_avds = $avds1, vent_cpm = $vent1, sat_o2 = $spo21, sup_o2 = $o2sup1,
	exp_co2 = $etco21, pulso_bpm = $pulso1, id_ecg = $ecg1, pa_sist = $parterialsist1,
	pa_diast = $parterialdiast1, id_pele = $pele1, temp = $temperatura1, id_pupilas_diametro = $dpupilas1,
	id_pupilas_reflexos = $rpupilas1, id_pupilas_simetria = $spupilas1, dor = $dor1
WHERE id = $id_avaliacao;
";
	
		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
			$erro = true;
		}

		// Dados da chamu
		$circustancias = $_POST['circustancias'];
		$hist_doencas = $_POST['hist_doencas'];
		$alergias = $_POST['alergias'];
		$med_habit = $_POST['med_habit'];
		$ult_refeicao = $_POST['ult_refeicao'];
		$hora_refeicao = !empty($_POST['hora_refeicao']) ? "'" . $_POST['hora_refeicao'] . ":00'" : 'null';
		
		// Query para inserir chamu
		$sql = "
UPDATE chamu
SET 
	circunstancias = '$circustancias', doencas = '$hist_doencas', alergias = '$alergias',
	medicacao = '$med_habit', hora_ult_ref = $hora_refeicao, ult_ref = '$ult_refeicao'
WHERE id = $id_chamu;
";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
			$erro = true;
		}

		// Dados do exame
		$anotacoes = $_POST['anotacoes'];

		// Query para inserir exame
		$sql = "
UPDATE vitima_lesao 
SET descricao = '$anotacoes'
WHERE id =  $id_anot;";

		// Execução da query
		if (!mysqli_query($conn, $sql)) {
			// Se não atualizou, retonar mensagem de erro
			echo "Erro: " . $sql . "	" . mysqli_error($conn);
			$erro = true;
		}
		
		// Se não ocorreu nenhum erro, redirecionar para o perfil inicial
		if(!$erro) {
			header("location: profile.php");
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
		<!-- Styling -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
		
		<style>
			label {
				text-align: right;
			}
		</style>
	</head>

	<body>
		<!-- Header -->
		<?php include 'header.php'; ?>
	
		<!-- Body -->
		<section id="showcase">
			<div class="container">
				<div>
					<h1>Verbete</h1>
					<p>Preenchimento do verbete</p>
					
					</br>
					<form id="verbete" action="verbete.php" class="form" method="POST">

						<!-- IDs -->
						<input name="id_ocorrencia" type="number" value="<?php echo htmlspecialchars($id)?>" hidden>
						<input name="id_vitima"     type="number" value="<?php echo htmlspecialchars($id_vitima)?>" hidden>
						<input name="id_chamu"      type="number" value="<?php echo htmlspecialchars($id_chamu)?>" hidden>
						<input name="id_avaliacao"  type="number" value="<?php echo htmlspecialchars($id_avaliacao)?>" hidden>
						<input name="id_anot"  		type="number" value="<?php echo htmlspecialchars($id_anot)?>" hidden>

						<input id="can_edit" type="number" value="<?php echo htmlspecialchars($can_edit)?>" hidden>
						<input id="anot" 	 type="text" value="<?php echo $anot_desc?>" hidden>
					
						<div id="conteudo_verbete" class="panel-group" id="accordion">

							<!-- Area Ocorrencia -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									<h4 class="panel-title">
										Ocorrência
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<?php include 'areas_verbete\ocorrencia.php';?>
									</div>
								</div>
							</div>  

							<!-- Area Identificação -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
									<h4 class="panel-title">
										Identificação
									</h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										<?php include 'areas_verbete\identificacao.php';?>
									</div>
								</div>
							</div>

							<!-- Area Avaliação -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
									<h4 class="panel-title">
										Avaliação
									</h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
										<?php include 'areas_verbete\avaliacao.php';?>
									</div>
								</div>
							</div>

							<!-- Area Historial Clínico -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
									<h4 class="panel-title">
										Historial Clínico
									</h4>
								</div>
								<div id="collapseFour" class="panel-collapse collapse">
									<div class="panel-body">
									<?php include 'areas_verbete\historial.php';?>
									</div>
								</div>
							</div>

							<!-- Area Exame da Vítima, Terapêutica e Observações -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
									<h4 class="panel-title">
										Exame da Vítima, Terapêutica e Observações
									</h4>
								</div>
								<div id="collapseFive" class="panel-collapse collapse">
									<div class="panel-body">
									<?php include 'areas_verbete\exame.php';?>
									</div>
								</div>
							</div>

							<!-- Area Procedimentos -->
							<div class="panel panel-default">
								<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
									<h4 class="panel-title">
										Procedimentos
									</h4>
								</div>
								<div id="collapseSix" class="panel-collapse collapse">
									<div class="panel-body">
									<?php include 'areas_verbete\procedimentos.php';?>
									</div>
								</div>
							</div>
						</div>
										
						<div class="col-md-12" style="text-align: right;">
							<input id="submeter" class="btn btn-success" type="submit" name="submeter" value="Submeter" />

							<script>
								// Ir buscar anotações para dar ao POST
								$('#verbete').submit(function() {
									
									var anotacoes = btoa(JSON.stringify(anno.getAnnotations()));
	
									$("<input />")
									.attr("type", "hidden")
									.attr("name", "anotacoes")
									.attr("value", anotacoes)
									.appendTo("#verbete");
	
									return true;
								});
							</script>

						</div>
												
						<div style="text-align: left; margin-top: 20px;">
						
							<button id="voltar" type="button" class="btn btn-info voltar" style="visibility: hidden;"/>Voltar</button>						
						
							<script>
								// Função para voltar à listagem de verbetes
								$(".voltar").click(function(){
									window.location.href = "historico.php";
								});
							</script>
						</div>
					</form>
					
				</div>
			</div>
		</section>
	
		<!-- Footer -->
		<?php include 'footer.php'; ?>
	</body>
	
	 <script>
		$( document ).ready(function() {
			var canEdit = document.getElementById("can_edit").value;

			if (canEdit == 0) {
				document.getElementById('submeter').style.visibility = 'hidden';
				document.getElementById('voltar').style.visibility = 'visible';

				$("#conteudo_verbete :input").prop("disabled", true);

				var annotsJson = atob(document.getElementById("anot").value);
				var annots = JSON.parse(annotsJson);

				if(annots) {
					for (let i = 0; i < annots.length; i++) {
						anno.addAnnotation(annots[i]);
					}
				}

				anno.readOnly = true;
			}	

		});
    </script>

</html>
