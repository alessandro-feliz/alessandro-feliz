<div class="rendered-form">

    <div class="form-group row">
		<label for="nr-codu" class="col-sm-2 col-form-label">Nº CODU</label>
		<div class="col-sm-10">
			<input type="text" name="nr-codu" access="false" id="nr-codu" 
					value="<?php echo htmlspecialchars($nr_codu)?>" disabled>
		</div>
	</div>

    <div class="form-group row">
		<label for="data" class="col-sm-2 col-form-label">Data</label>
		<div class="col-sm-10">
			<input type="date" name="data" access="false" id="data" 
					value="<?php echo htmlspecialchars($data)?>" disabled>
		</div>
	</div>
	
	<div class="form-group row">
		<label for="cam-local" class="col-md-2 col-form-label">Cam. do Local</label>		
		<div class="col-sm-1">
			<input type="time" name="cam-local" access="false" id="cam-local" class="form-control"
					value="<?php echo htmlspecialchars($hora_caminho_local)?>">
		</div>
		
		<label for="cheg-vitima" class="col-md-2 col-form-label">Cheg. à Vítima</label>
		<div class="col-sm-1">
			<input type="time" name="cheg-vitima" access="false" id="cheg-vitima" class="form-control"
					value="<?php echo htmlspecialchars($hora_chegada_local)?>">
		</div>

		<label for="cam-usaude" class="col-sm-2 col-form-label">Cam. U. Saúde</label>
		<div class="col-sm-1">
			<input type="time" name="cam-usaude" access="false" id="cam-usaude" class="form-control"
					value="<?php echo htmlspecialchars($hora_caminho_hosp)?>">
		</div>
		
		<label for="cheg-usaude" class="col-sm-2 col-form-label">Cheg. U. Saúde</label>
		<div class="col-sm-1">
			<input type="time" name="cheg-usaude" access="false" id="cheg-usaude" class="form-control"
					value="<?php echo htmlspecialchars($hora_chegada_hosp)?>">
		</div>
	</div>
	    
	<div class="form-group row">
		<label for="motivo" class="col-sm-2 col-form-label">Motivo</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="motivo" access="false" id="motivo" 
			value="<?php echo htmlspecialchars($descricao)?>"
			required
			oninvalid="this.setCustomValidity('Motivo obrigatório')" 
			oninput="this.setCustomValidity('')">
		</div>
    </div>
	
	<div class="form-group row">
		<label for="tipolocal" class="col-md-2 col-form-label">Tipo de Local</label>		
		<div class="col-sm-2">
			<select name="tipolocal" id="tipolocal" class="form-control">
				<?php
					$sql = "SELECT id, nome FROM tipo_de_local";

					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {

							$selected = "";
							if ($id_tipo_de_local == $row['id']) {
								$selected = "selected";
							}

							echo "<option value=\"option-{$row['id']}\" " . $selected . " id=\"local-{$row['id']}\">{$row['nome']}</option>";
						}
					} else {
						echo "0 results";
					}
				?>
			</select>
		</div>
		
		<label for="distrito" class="col-md-2 col-form-label">Distrito</label>
		<div class="col-sm-2">
			<select name="distrito" id="distrito" onchange="updateConcelhos(this.value)" class="form-control">
				<?php
					$sql = "SELECT id, nome FROM distrito";

					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {

							$selected = "";
							if ($id_distrito == $row['id']) {
								$selected = "selected";
							}

							echo "<option value=\"{$row['id']}\" id=\"distrito-{$row['id']}\" " . $selected . ">{$row['nome']}</option>";
						}
					} else {
						echo "0 results";
					}
				?>
			</select>
		</div>
		
		<label for="concelho" class="col-sm-2 col-form-label">Concelho</label>
		<div class="col-sm-2">
			<select name="concelho_all" id="concelho_all" hidden>
				<?php
					$sql = "SELECT id, id_distrito, nome FROM concelho";
					
					$result = $conn->query($sql);
					
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<option value=\"{$row['id']}\" id=\"concelho-{$row['id']}\" name=\"{$row['id_distrito']}\" " . $selected . ">{$row['nome']}</option>";
						}
					} else {
						echo "0 results";
					}
				?>
			</select>
			<select name="concelho" id="concelho" class="form-control">	
				<?php
				
					$sql = "
SELECT id, id_distrito, nome 
FROM concelho
WHERE id = $id_concelho";
					
					$result = $conn->query($sql);
					
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<option value=\"{$row['id']}\" id=\"concelho-{$row['id']}\" name=\"{$row['id_distrito']}\" " . $selected . ">{$row['nome']}</option>";
						}
					}
				?>	
			</select>
		</div>

		<script>
			function updateConcelhos(val) {
				
				var concelhosAll = document.getElementById('concelho_all').children;
				var concelhos = document.getElementById('concelho');

				concelhos.innerHTML = "";

				for (let i = 0; i < concelhosAll.length; i++) {
					var concelhoOption = concelhosAll[i];
					var concelhoOptionId = concelhoOption.getAttribute('id');
					var concelhoOptionDistritoId = concelhoOption.getAttribute('name');
					var concelhoOptionName = concelhoOption.innerHTML;
					
					if(concelhoOptionDistritoId == val) {
						var concelho = concelhosAll[i].cloneNode(true);
						concelhos.appendChild(concelho);
					}
				}
			}
			
			updateConcelhos(1);
		</script>
    </div>
	
	<div class="form-group row">
		<label for="local" class="col-sm-2 col-form-label">Local</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="local" access="false" id="local"
			value="<?php echo htmlspecialchars($local)?>"
			required
			oninvalid="this.setCustomValidity('Local obrigatório')" 
			oninput="this.setCustomValidity('')"/>
		</div>
	</div>
	
</div>