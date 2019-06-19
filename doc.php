<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Documentation </title>
	<?php require_once('dependencies.html'); ?>
	<script type="text/javascript" src="personnalisation.js"></script> 
</head>

<body>
<div class="wrapper">

	<?php require_once('menu.html'); ?>

	 <div class="container-fluid" id="main">
	    	<div class="wrapper">
	    		<!-- Bouton pour activer/désactiver la sidebar -->
		    	<a id="sidebarCollapse" class="btn my-auto">
					<i class="fas fa-align-left"></i>
				</a>
			</div>
	</div>
	
<script type="text/javascript">

	$('#optionsCollapse').on('click', function () {
	    $('#options-panel').toggleClass('active');
	});




</script>
</body>
</html>