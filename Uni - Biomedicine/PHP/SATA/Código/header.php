<header>
	<div style="float:right;">
		<b id="welcome">Bem vindo <i><?php echo $login_session; ?></i></b>
		<b id="logout"><a href="/sata/code/logout.php">(logout)</a></b>
	</div>
	<div class="container">
		<div id="branding">
			<h1><span class="highlight">SATA</span> - Sistema de Apoio ao Tripulante de Ambulância</h1>
		</div>
		<nav>
			<ul>
				<?php
					// Tripulante
					if ($login_session_perfil == 20) {
						echo "<li><a href=\"/sata/index.php\">Inicio</a></li>";
					}
				?>
				<?php
					// Médico
					if ($login_session_perfil == 30) {			
						echo "<li><a href=\"/sata/historico.php\">Histórico</a></li>";
					}
				?>
				<?php
					// Admin
					if ($login_session_perfil == 10) {	
						echo "<li><a href=\"/sata/utilizadores/listar.php\">Utilizadores</a></li>";
					}
				?>
				
				<li><a href="/sata/sobre.php">Sobre</a></li>
			</ul>
		</nav>
	</div>
</header>