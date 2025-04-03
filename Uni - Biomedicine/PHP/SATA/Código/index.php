<?php
	include('code/login.php');
	
	if(isset($_SESSION['login_user'])){
		header("location: profile.php");
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
		<link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
	</head>

	<body>
		<!-- Header -->
		<header id="bootstrap-overrides">
			<div class="container">
				<div id="branding">
					<h1><span class="highlight">SATA</span> - Sistema de Apoio ao Tripulante de Ambulância</h1>
				</div>
			</div>
		</header>
	
		<!-- Body -->
		<section id="showcase">
			<div class="container">
				<h1>Login</h1>
				</br>
				<div class="container">
					<form action="" method="post">
						<label for="username"><b>Nome de utilizador</b></label>
						<input id="username" type="text" placeholder="Introduza o seu nome de utilizador" name="username" 
								required oninvalid="this.setCustomValidity('Nome de utilizador obrigatório')" oninput="this.setCustomValidity('')">
					
						<label for="password"><b>Palavra-chave</b></label>
						<input id="password" type="password" placeholder="Introduza a sua palavra-chave" name="password" 
								required oninvalid="this.setCustomValidity('Palavra-chave obrigatória')" oninput="this.setCustomValidity('')">
							
						<button name="submit" type="submit">Login</button>
						<span><?php echo $error; ?></span>
					</form>
				</div>	
			</div>
		</section>
		
		Dados de login: admin/admin ou tripulante1/tripulante1 ou medico1/medico1
	
		<!-- Footer -->
		<?php include 'footer.php'; ?>
	</body>

</html>
