<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Home </title>
	<?php require_once('dependencies.html'); ?>
	<script type="text/javascript" src="personnalisation.js"></script> 
</head>

<body>
<div class="wrapper">

	<?php require_once('menu.html'); ?>


	 <div class="container-fluid" >
	    	<div class="wrapper bg-light">
	    		<!-- Bouton pour activer/désactiver la sidebar -->
		    	<a id="sidebarCollapse" class="btn my-auto">
					<i class="fas fa-align-left"></i>
				</a>
			</div>


			<!-- Bloc "Présentation de l'application" -->
			<section class="bg-light text-center" id="presentation">
				<!-- Les deux divisions suivantes permettent de centrer verticalement les éléments sur bootstrap -->
				<div class="row h-100">
					<div class="col-sm-12 my-auto">
						<h1 class="my-auto"> Kajika </h1>
						<p> <i class="fab fa-github"></i> </p>
						<p class="lead"> With Kajika, display dynamic, automatically organised, customizable network views. </p>
						<p class="lead"> Track the attributes of your entities.</p>
						<a href="doc.php">
							<button type="button" class="btn btn-outline-dark">Get started</button>
						</a>
						<br>
						<a href="https://github.com/Azouu/Kajika">
							<button type="button" class="btn btn-light border-dark mt-1">
								<img class="little-icon" src="assets/img/github.svg"> Github
							</button>
						</a>
					</div>
				</div>

				
			</section>

			
			<!-- Bloc "A propos" -->
			<section class="text-center mt-3" id="about">
				<div class="container-fluid">
					<p class="small"> App made during the internship of <a href="https://www.linkedin.com/in/ines-louahadj-89ba51151/"> Inès Louahadj </a> under the supervision of <a href="https://www.irit.fr/spip.php?page=annuaire&code=12015"> Tanguy Esteoule </a> and <a href="https://www.irit.fr/~Carole.Bernon/"> Carole Bernon </a>, team members of SMAC (IRIT). </p>
					<a href="https://www.irit.fr"> 
						<img class="icon" src="assets/img/irit.jpg"> 
					</a>
					<a href="https://www.irit.fr/smac/">
						<img class=" icon" src="assets/img/smac.png">
					</a>
					<a href="http://www.univ-tlse3.fr/">
						<img class="icon-with-writing" src="assets/img/logo_UT3">			
					</a>
					<a href="http://iut.ups-tlse.fr/">		
						<img class="icon" src="assets/img/iut.jpg">	
					</a>
					<p class="small"> IRIT-SMAC 2019 </p>
	
				</div>
			</section>
			


			
	</div>
	


<script type="text/javascript">

	$('#optionsCollapse').on('click', function () {
	    $('#options-panel').toggleClass('active');
	});




</script>
</body>
</html>