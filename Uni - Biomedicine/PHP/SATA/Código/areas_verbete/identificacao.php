<div class="rendered-form">

    <div class="form-group row">
	    <label for="nome" class="col-sm-2 col-form-label">Nome</label>
		<div class="col-sm-10">
			<input type="text" name="nome" access="false" id="nome" class="form-control"
					value="<?php echo htmlspecialchars($nome)?>">
		</div>
	</div>
		
	<div class="form-group row">
        <label for="data_nasc" class="col-sm-2 col-form-label">Data de Nasc.</label>
		<div class="col-sm-10">
			<input type="date" name="data_nasc" access="false" id="data_nasc" onfocusout="calculateAge()" class="form-control"
					value="<?php echo htmlspecialchars($data_nascimento)?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label for="idade" class="col-sm-2 col-form-label">Idade</label>
		<div class="col-sm-10">
			<input type="text" name="idade" access="false" id="idade" class="form-control" disabled>
		</div>
	</div>
	
	<script>
		function calculateAge() {
		
			// Buscar a data de nascimento introduzida
			var dataNascStr = document.getElementById("data_nasc").value;
			var dataNasc = new Date(dataNascStr);
		
			// Calcula diferença em meses
			var difMeses = Date.now() - dataNasc.getTime();  
		
			// Converte a diferença de meses para "Date"
			var idadeData = new Date(difMeses);   
			
			// Extrai ano da data  
			var ano = idadeData.getUTCFullYear();  
			
			// Calcula a idade a partir da data
			var idade = Math.abs(ano - 1970);  
			
			// Apresentar
			document.getElementById("idade").value = idade;
			document.getElementById("idade").disabled = true;
		}
		
		calculateAge();
	</script>  
	
	<div class="form-group row">
		<label for="sexo" class="col-sm-2 col-form-label">Sexo</label>
		<div class="col-sm-10">
			<select name="sexo" id="sexo" class="form-control">  
<?php
	// Query para ir buscar o sexo
	$sql = "SELECT id, nome FROM sexo";    
	// Execução da query
	$result = $conn->query($sql);
	// Converter para html option
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<option value=\"option-{$row['id']}\" id=\"sexo-{$row['id']}\">{$row['nome']}</option>";
		}
	}
?>
			</select>
		</div>
    </div>
	
	<div class="form-group row">
		<label for="num_sns" class="col-sm-2 col-form-label">Número SNS</label>
		<div class="col-sm-10">
			<input type="text" name="num_sns" access="false" id="num_sns" class="form-control"
					value="<?php echo htmlspecialchars($nr_sns)?>">
		</div>
	 </div>
	 
	<div class="form-group row">
		<label for="resid" class="col-sm-2 col-form-label">Residência</label>
		<div class="col-sm-10">
			<input type="text" name="resid" access="false" id="resid" class="form-control"
					value="<?php echo htmlspecialchars($residencia)?>">
		</div>
	</div>

</div>
