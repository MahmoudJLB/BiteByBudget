<?php
session_start();
unset($_SESSION['username']);
session_destroy();
require_once "Views/Shared/populate-page.php";

require_once "Views/about-us-views.php";

?>

<html>

	<head>
		<!-- Title Bar -->
		<title>BBB | Meet The Team</title>
		<link href="../Images/Icons/favicon-enhanced-no-bg.ico" rel="icon" type="image/ico">
		<!-- Linked Styles -->
		<link href="../Styles/Shared/reset.css" rel="stylesheet" type="text/css">
		<link href="../Styles/Shared/header.css" rel="stylesheet" type="text/css">
		<link href="../Styles/Shared/footer.css" rel="stylesheet" type="text/css">
		<link href="../Styles/about-us.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<!-- Loader -->
		<?php populateLoaderView(); ?>
		<!-- Menu Nav Bar -->
		<?php populateHeaderView(); ?>
		<!-- Main -->
		<div id="main">
			<h2>Meet The Team</h2>
			<div id="team-wrapper">
				<?php
					$members = populateMembers();
					$team_size = count($members);
					for ($i = 0; $i < $team_size; $i++) {
						populateTeamMemberView($members[$i]);
					}
				?>
			</div>
		</div>
		<!-- Footer -->
		<?php populateFooterView(); ?>
	</body>

	<!-- Linked Scripts -->
	<?php
		animateLoaderScript();
		toggleHeaderScript();
	?>

</html>
