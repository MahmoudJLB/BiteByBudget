<?php

require_once "Views/Shared/populate-page.php";
require_once "Views/Shared/scripts.php";
require_once "Views/home-page-views.php";
require_once "Views/browse-recipes-page-view.php";


?>

<html>

		<head>
			<!-- Title Bar -->
			<title>BBB | Browse Recipes</title>
			<!-- Linked Styles -->
			<link href="../Styles/Shared/reset.css" rel="stylesheet" type="text/css">
			<link href="../Styles/Shared/icons.css" rel="stylesheet" type="text/css">
			<link href="../Styles/Shared/header.css" rel="stylesheet" type="text/css">
			<link href="../Styles/Shared/footer.css" rel="stylesheet" type="text/css">
            <script src="../Libraries/jquery.min.js"></script>
            <?php echo loadScripts() ?>
			<!-- Linked Scripts -->

            
			<?php
				toggleHeaderScript();
			?>
		</head>

		<body>
			<!-- Menu Nav Bar -->
			<?php populateHeaderView(); ?>
			<!-- Main -->
			<div id="main">
                <div id="step-1"> </div>
                <div id="step-2"> </div>
                <div id="step-3"> </div>
                <div id="step-4"> </div>
                <div id="step-5"> </div>
			</div>
			<!-- Footer -->
			<?php populateFooterView(); ?>
		</body>

	</html>
