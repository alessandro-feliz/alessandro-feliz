<div class="rendered-form">

    <div class="form-group row">
        <label for="circustancias" class="col-sm-2 col-form-label">Circustâncias</label>
		<div class="col-sm-10">
			<input type="text" name="circustancias" access="false" id="circustancias" class="form-control"
			required
			oninvalid="this.setCustomValidity('Circustâncias obrigatórias')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($circunstancias)?>">
		</div>
	</div>

    <div class="form-group row">
        <label for="hist_doencas" class="col-sm-2 col-form-label">Histórico de Doenças</label>
		<div class="col-sm-10">
			<input type="text" name="hist_doencas" access="false" id="hist_doencas" class="form-control"
			value="<?php echo htmlspecialchars($doencas)?>">
		</div>
	</div>

    <div class="form-group row">
		<label for="alergias" class="col-sm-2 col-form-label">Alergias</label>
		<div class="col-sm-10">
			<input type="text" name="alergias" access="false" id="alergias" class="form-control"
			value="<?php echo htmlspecialchars($alergias)?>">
		</div>
	</div>
	
    <div class="form-group row">
		<label for="med_habit" class="col-sm-2 col-form-label">Medicação Habitual</label>
		<div class="col-sm-10">
			<input type="text" name="med_habit" access="false" id="med_habit" class="form-control"
			value="<?php echo htmlspecialchars($medicacao)?>"> 
		</div>
	</div>	

    <div class="form-group row">
		<label for="ult_refeicao" class="col-sm-2 col-form-label">Última Refeição</label>
		<div class="col-sm-1">
			<input type="time" name="hora_refeicao" access="false" id="hora_refeicao" class="form-control"
			value="<?php echo htmlspecialchars($hora_ult_ref)?>">
		</div>
		<div class="col-sm-9">
			<input type="text" name="ult_refeicao" access="false" id="ult_refeicao" class="form-control"
			value="<?php echo htmlspecialchars($ult_ref)?>">
		</div>
	</div>

</div>
