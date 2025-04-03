<!--   
    Este ficheiro controla a secção do verbete relacionado com o exame à vitima.
	Inclui a biblioteca Annotorious para o desenho de anotações.
-->

<!-- Annotorious CSS stylesheet -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@recogito/annotorious@2.7.6/dist/annotorious.min.css">
<!-- Annotorious Lib -->
<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious@2.7.6/dist/annotorious.min.js"></script>
<!-- Annotorious Selector Pack plugin -->
<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious-shape-labels@latest/dist/annotorious-shape-labels.min.js"></script>
<!-- Annotorious Shape Labels plugin -->
<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious-selector-pack@latest/dist/annotorious-selector-pack.min.js"></script>
<!-- Annotorious Toolbar plugin -->
<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious-toolbar@latest/dist/annotorious-toolbar.min.js"></script>

<div class="row">

	<div id="content">
		<div id="toolbar-container"></div>
		<img id="background-dummies" src="../sata/images/dummies.png">
	</div>
	
	<script>
		const config = {
			image: 'background-dummies',
			formatter: Annotorious.ShapeLabelsFormatter(),
			locale: 'PT'
		}
	
		// Inicializar o Annotorious
		var anno = Annotorious.init(config);
	
		// Inicializar os plugins
        Annotorious.SelectorPack(anno);
		//Annotorious.Toolbar(anno, document.getElementById('toolbar-container'));
		
		// Definir ferramenta de desenho inicial 
        anno.setDrawingTool('rectangle');
	</script>
</div>