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
		header("Location: index.php");
	}

	elseif (isset($_SESSION['connected'])) {
		header("Location: index.php");
	} 

	elseif (!empty($_POST['username']) AND !empty($_POST['password'])) {
		if ($_POST['username'] == DCRM_USERNAME AND sha1($_POST['password']) == DCRM_PASSWORD) {
			$_SESSION['connected'] = true;
			$_SESSION['token'] = sha1(time()*rand(140,320));
			$_SESSION['try'] = 0;
			header("Location: index.php");
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
			<style type="text/css" media="screen">
				body {
					margin: 100px;
					background-color: #CCC;
					font-family: Arial,Helvetica,sans-serif;
					font-size: 10pt;
				}
				#form {
					-webkit-border-bottom-left-radius: 25px;
					-moz-border-radius-bottomleft: 25px;
					border-bottom-left-radius: 25px;
					background-color: #507e8c;
					padding: 50px;
					width: 320px;
					text-align: center;
					margin: auto;
				}
				#form p {
					color: white;
				}
				#caption {
					-webkit-border-top-right-radius: 25px;
					-moz-border-radius-topright: 25px;
					border-top-right-radius: 25px;
					width: 418px;
					text-align: center;
					margin: auto;
					padding: 1px;
					background-color: #EEE;
				}
				.error {
					color: red;
				}
			</style>
		</head>
		<body>
			<?php
				if (!isset($_SESSION['try']) OR $_SESSION['try'] <= DCRM_MAXLOGINFAIL) {
			?>
			<div id="caption">
			<p>
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
			</p>
			</div>
			<form action="login.php" method="POST">
				<div id="form">
					<p><?php echo $lang_login['username'][DCRM_LANG]; ?> : <input type="text" name="username" /></p>
					<p><?php echo $lang_login['password'][DCRM_LANG]; ?> : <input type="password" name="password" /></p>
					<input type="submit" name="submit" value="<?php echo $lang_login['login'][DCRM_LANG]; ?> !" />
				</div>
			</form>
			<?php
				}
				else {
			?>
			<div id="caption" class="error"><p><?php echo $lang_login['error'][DCRM_LANG]; ?></p></div>
			<div id="form"><p class="alert"><?php echo $lang_login['too_much'][DCRM_LANG]; ?>.</p></form>
			<?php
				}
			?>
		</body>
	</html>
<?php
	}
?>