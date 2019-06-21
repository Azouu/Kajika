<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> My experiments </title>
    <?php require_once("dependencies.html") ?>

</head>


<body>
	
<div class="wrapper">

	<?php 
		require_once("menu.html");
		require_once("lib.php");
	?>

	<div class="container-fluid" id="main">
	    <div class="wrapper">
	    	<!-- Bouton pour activer/désactiver la sidebar -->
		    <a id="sidebarCollapse" class="btn my-auto">
				<i class="fas fa-align-left"></i>
			</a>
 
			<h1 class="mx-auto mt-5">My experiments </h1>
		</div>

		<!-- Liste des expériences -->
		<div class="list-group mt-5" id="experiments-list">
			<?php
				foreach ($database->listCollections() as $collectionInfo) {
					$colName = $collectionInfo['name'];
					if($database->$colName->count() > 0) {
						$expName = $database->$colName->findOne()->experimentName;
						echo "<a href='network.php?col=" . $colName . "' class='list-group-item list-group-item-action'>" . $expName . "</a>";
					}
				}
			?>
		</div>

	</div>

</div>

</body>
</html>