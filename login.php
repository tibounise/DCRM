<?php
	session_start();
	define("DCRM",true);
	require_once("config.inc.php");
	require_once("langs.inc.php");

	if (!isset($_SESSION['try'])) {
		$_SESSION['try'] = 0;
	}
	
	if (!empty($_SESSION['connected']) AND isset($_GET['action']) AND $_GET['action'] == "logout" AND $_GET['token'] == $_SESSION['token']) {
		session_destroy();
		header("Location: login.php");
	}

	elseif (isset($_SESSION['connected'])) {
		header("Location: manage.php");
	} 

	elseif (!empty($_POST['username']) AND !empty($_POST['password'])) {
		if ($_POST['username'] == DCRM_USERNAME AND sha1($_POST['password']) == DCRM_PASSWORD) {
			$_SESSION['connected'] = true;
			$_SESSION['token'] = sha1(time()*rand(140,320));
			$_SESSION['try'] = 0;
			header("Location: manage.php");
		}
		else {
			$_SESSION['try'] = $_SESSION['try'] + 1;
			header("Location: login.php?error=badlogin");
		}

	}

	elseif (isset($_POST['submit']) AND (empty($_POST['username']) OR empty($_POST['password']))) {
		header("Location: login.php?error=notenough");
	}

	else {
?>
<!doctype html>
	<html>
		<head>
			<title>DCRM - Login</title>
			<link rel="stylesheet" href="bootstrap.min.css">
			<meta charset="utf-8">
			<style type="text/css" media="screen">
				body {
					margin: 100px;
					background: #ffffff;
					background: -moz-radial-gradient(center, ellipse cover, #ffffff 0%, #e5e5e5 100%);
					background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5));
					background: -webkit-radial-gradient(center, ellipse cover, #ffffff 0%,#e5e5e5 100%);
					background: -o-radial-gradient(center, ellipse cover, #ffffff 0%,#e5e5e5 100%);
					background: -ms-radial-gradient(center, ellipse cover, #ffffff 0%,#e5e5e5 100%);
					background: radial-gradient(center, ellipse cover, #ffffff 0%,#e5e5e5 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=1 );
					font-family: Arial,Helvetica,sans-serif;
					font-size: 10pt;
				}
				.well {
					margin-left: auto;
					margin-right: auto;
					width: 400px;
					text-align: center;
				}
			</style>
		</head>
		<body>
			<?php
				if (!isset($_SESSION['try']) OR $_SESSION['try'] <= DCRM_MAXLOGINFAIL) {
			?>
			<form class="well" action="login.php" method="POST">
				<?php
					if (isset($_GET['error']) AND $_GET["error"] == "notenough") {
						echo $lang_login['not_enough'][DCRM_LANG];
					}
					elseif (isset($_GET['error']) AND $_GET["error"] == "badlogin") {
						echo $lang_login['wrong_login'][DCRM_LANG];
					}
					elseif (isset($_GET['error']) AND $_GET["error"] == "bear") {
						echo "Your face is bear.";
					}
					else {
						echo $lang_login['login_message'][DCRM_LANG];
					}
				?>
				<hr>
				<p><input type="text" name="username" required="required" placeholder="<?php echo $lang_login['username'][DCRM_LANG]; ?>" /></p>
				<p><input type="password" name="password" placeholder="<?php echo $lang_login['password'][DCRM_LANG]; ?> " required="required" /></p>
					<input type="submit" class="btn btn-success" name="submit" value="<?php echo $lang_login['login'][DCRM_LANG]; ?> !" />
			</form>
			<?php
				}
				else {
			?>
			<div class="well">
				<?php echo $lang_login['error'][DCRM_LANG]; ?><hr>
				<?php echo $lang_login['too_much'][DCRM_LANG]; ?>.
			</div>
			<?php
				}
			?>
		</body>
	</html>
<?php
	}
?>