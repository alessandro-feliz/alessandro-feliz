<div class="rendered-form">

	<h3>1º Avaliação</h3>

    <div class="form-group row">
		<label for="hora1" class="col-sm-2 col-form-label">Hora</label>
		<div class="col-sm-1">
			<input type="time" name="hora1" access="false" id="hora1" class="form-control" 
			required
			oninvalid="this.setCustomValidity('Hora da avaliação obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($hora)?>">
		</div>
	</div>

    <div class="form-group row">
		<label for="vent1" class="col-md-2 col-form-label">Vent.</label>
		<div class="col-sm-1">
			<input type="number" name="vent1" access="false" id="vent1" class="form-control"
			required min="0" max="60"
			oninvalid="this.setCustomValidity('Ventilação obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($vent_cpm)?>">
		</div>

		<label for="spo21" class="col-md-1 col-form-label">SpO2</label>
		<div class="col-sm-1">
			<input type="number" name="spo21" access="false" id="spo21" class="form-control"
			required min="0" max="100"
			oninvalid="this.setCustomValidity('Saturação de O2 obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($sat_o2)?>">
		</div>

	    <label for="o2sup1" class="col-md-1 col-form-label">O2 Supl.</label>
		<div class="col-sm-1">
			<input type="number" name="o2sup1" access="false" id="o2sup1" class="form-control"
			required min="0" max="15"
			oninvalid="this.setCustomValidity('Suplemento O2 obrigatório')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($sup_o2)?>">
		</div>

		<label for="etco21" class="col-md-1 col-form-label">EtCO2</label>
		<div class="col-sm-1">
			<input type="number" name="etco21" access="false" id="etco21" class="form-control"
			required min="0" max="100"
			oninvalid="this.setCustomValidity('CO2 exalado obrigatório')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($exp_co2)?>">
		</div>
	</div>

    <div class="form-group row">
	    <label for="pulso1" class="col-md-2 col-form-label">Pulso</label>
		<div class="col-sm-1">
             <input type="number" name="pulso1" access="false" id="pulso1" class="form-control"
			required min="0" max="300"
			oninvalid="this.setCustomValidity('Pulso obrigatório')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($pulso_bpm)?>">
		</div>

		<label for="parterialsist1" class="col-md-1 col-form-label">PA Sist</label>
		<div class="col-sm-1">
            <input type="number" name="parterialsist1" access="false" id="parterialsist1" class="form-control"
			required min="0" max="400"
			oninvalid="this.setCustomValidity('PA Sistólica obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($pa_sist)?>">
		</div>

		<label for="parterialdiast1" class="col-md-1 col-form-label">PA Diast</label>
		<div class="col-sm-1">
             <input type="number" name="parterialdiast1" access="false" id="parterialdiast1" class="form-control"
			required min="0" max="200"
			oninvalid="this.setCustomValidity('PA Diastólica obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($pa_diast)?>">
		</div>

		<label for="pele1" class="col-md-1 col-form-label">Pele</label>
		<div class="col-sm-1">
			 <select name="pele1" id="pele1" class="form-control">
				<option value="option-1" id="pele-1"></option>
				<option value="option-300" id="pele-300" <?php if ($id_pele == 300) { echo "selected"; } ?>>Normal</option>
				<option value="option-301" id="pele-301" <?php if ($id_pele == 301) { echo "selected"; } ?>>Pele Pálida</option>
				<option value="option-302" id="pele-302" <?php if ($id_pele == 302) { echo "selected"; } ?>>Pele Marmoreada</option>
				<option value="option-303" id="pele-303" <?php if ($id_pele == 303) { echo "selected"; } ?>>Cianose</option>
				<option value="option-304" id="pele-304" <?php if ($id_pele == 304) { echo "selected"; } ?>>Outro</option> 
			</select>
		</div>

		<label for="temperatura1" class="col-md-1 col-form-label">Temp.</label>
		<div class="col-sm-1">
			<input type="number" name="temperatura1" access="false" id="temperatura1" class="form-control"
			required min="0" max="60" step=".1"
			oninvalid="this.setCustomValidity('Temperatura obrigatória')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($temp)?>">
		</div>
	</div>

    <div class="form-group row">		
		<label for="avds1" class="col-md-2 col-form-label">AVDS</label>
        <div class="col-sm-1">
			<select name="avds1" id="avds1" class="form-control">
				<option value="option-1"   id="avds-1"></option>
				<option value="option-100" id="avds-100" <?php if ($id_avds == 100) { echo "selected"; } ?>>A</option>
				<option value="option-101" id="avds-101" <?php if ($id_avds == 101) { echo "selected"; } ?>>V</option>
				<option value="option-102" id="avds-102" <?php if ($id_avds == 102) { echo "selected"; } ?>>D</option>
				<option value="option-103" id="avds-103" <?php if ($id_avds == 103) { echo "selected"; } ?>>S</option>
			</select>
		</div>

        <label for="dor1" class="col-md-1 col-form-label">Dor</label>
		<div class="col-sm-1">
            <input type="number" id="dor1" name="dor1" min="0" max="10" class="form-control"
			required
			oninvalid="this.setCustomValidity('Nível de dor obrigatório')" 
			oninput="this.setCustomValidity('')"
			value="<?php echo htmlspecialchars($dor)?>">
		</div>

		<label for="ecg1" class="col-md-1 col-form-label">ECG</label>
		<div class="col-sm-1">
			<select name="ecg1" id="ecg1" class="form-control">
				<option value="option-1"   id="ecg-1"></option>
				<option value="option-200" id="ecg-200" <?php if ($id_ecg == 200) { echo "selected"; } ?>>ASS</option>
				<option value="option-201" id="ecg-201" <?php if ($id_ecg == 201) { echo "selected"; } ?>>AV1</option>
				<option value="option-202" id="ecg-202" <?php if ($id_ecg == 202) { echo "selected"; } ?>>AV2</option>
				<option value="option-203" id="ecg-203" <?php if ($id_ecg == 203) { echo "selected"; } ?>>AV3</option>
				<option value="option-204" id="ecg-204" <?php if ($id_ecg == 204) { echo "selected"; } ?>>BRD</option>
				<option value="option-205" id="ecg-205" <?php if ($id_ecg == 205) { echo "selected"; } ?>>BRE</option>
				<option value="option-206" id="ecg-206" <?php if ($id_ecg == 206) { echo "selected"; } ?>>ESV</option>
				<option value="option-207" id="ecg-207" <?php if ($id_ecg == 207) { echo "selected"; } ?>>EV</option>
				<option value="option-208" id="ecg-208" <?php if ($id_ecg == 208) { echo "selected"; } ?>>FA</option>
				<option value="option-209" id="ecg-209" <?php if ($id_ecg == 209) { echo "selected"; } ?>>FLA</option>
				<option value="option-210" id="ecg-210" <?php if ($id_ecg == 210) { echo "selected"; } ?>>FV</option>
				<option value="option-211" id="ecg-211" <?php if ($id_ecg == 211) { echo "selected"; } ?>>IST</option>
				<option value="option-212" id="ecg-212" <?php if ($id_ecg == 212) { echo "selected"; } ?>>RJ</option>
				<option value="option-213" id="ecg-213" <?php if ($id_ecg == 213) { echo "selected"; } ?>>RI</option>
				<option value="option-214" id="ecg-214" <?php if ($id_ecg == 214) { echo "selected"; } ?>>RS</option>
				<option value="option-215" id="ecg-215" <?php if ($id_ecg == 215) { echo "selected"; } ?>>SST</option>
				<option value="option-216" id="ecg-216" <?php if ($id_ecg == 216) { echo "selected"; } ?>>TSV</option>
				<option value="option-217" id="ecg-217" <?php if ($id_ecg == 217) { echo "selected"; } ?>>TV</option>
			</select>
		</div>
	</div>

    <div class="form-group row">
		<label for="dpupilas1" class="col-md-2 col-form-label">Diâmetro Pupilas</label>	
		<div class="col-sm-2">
			<select name="dpupilas1" id="dpupilas1" class="form-control">
				<option value="option-1"   id="dpupilas-1"></option>
				<option value="option-400" id="dpupilas-400" <?php if ($id_pupilas_diametro == 400) { echo "selected"; } ?>>Normal</option>
				<option value="option-401" id="dpupilas-401" <?php if ($id_pupilas_diametro == 401) { echo "selected"; } ?>>Miose (contraída)</option>
				<option value="option-402" id="dpupilas-402" <?php if ($id_pupilas_diametro == 402) { echo "selected"; } ?>>Midríase (dilatada)</option>
			</select>
		</div>
		
		<label for="rpupilas1" class="col-md-2 col-form-label">Reflexo Pupilas</label>
		<div class="col-sm-2">
			 <select name="rpupilas1" id="rpupilas1" class="form-control">
				<option value="option-1"   id="rpupilas-1"></option>
				<option value="option-410" id="rpupilas-410" <?php if ($id_pupilas_reflexos == 410) { echo "selected"; } ?>>Conservado</option>
				<option value="option-411" id="rpupilas-411" <?php if ($id_pupilas_reflexos == 411) { echo "selected"; } ?>>Abolido</option>
			</select>
		</div>
		
		<label for="spupilas1" class="col-md-2 col-form-label">Simetria Pupilas</label>
		<div class="col-sm-2">
			 <select name="spupilas1" id="spupilas1" class="form-control">
				<option value="option-1"   id="spupilas-1"></option>
				<option value="option-420" id="spupilas-420" <?php if ($id_pupilas_simetria == 420) { echo "selected"; } ?>>Isocóricas (simétricas)</option>
				<option value="option-421" id="spupilas-421" <?php if ($id_pupilas_simetria == 421) { echo "selected"; } ?>>Anisocóricas (assimétricas)</option>
			</select>
		</div>
	</div>
	
	<h3>2º Avaliação</h3>
	<p>A sair na próxima versão em caso de aprovação do cliente.</p>


	<h3>3º Avaliação</h3>
	<p>A sair na próxima versão em caso de aprovação do cliente.</p>


</div>
