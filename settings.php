<?php
	session_start();
	define("DCRM",true);
	require_once("config.inc.php");
	require_once("langs.inc.php");

	if (isset($_SESSION['connected'])) {
?>
<!doctype html>
<html lang="fr">
<head>
	<title>DCRM - Package management</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span6" id="logo">
				<p class="title">DCRM</p>
				<h6 class="underline">Dumb Cydia Repository Manager</h6>
			</div>
			<div class="span6">
				<div class="btn-group pull-right">
					<a href="build.php" class="btn btn-inverse"><?php echo $lang_topbtn['build'][DCRM_LANG]; ?> !</a>
					<a href="#" class="btn btn-info disabled"><?php echo $lang_topbtn['settings'][DCRM_LANG]; ?></a>
					<a href="login.php?action=logout&token=<?php echo $_SESSION['token']; ?>" class="btn btn-info"><?php echo $lang_topbtn['logout'][DCRM_LANG]; ?></a>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="span2.5" style="margin-left:0!important;">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						<li class="nav-header"><?php echo $lang_sidebar['packages'][DCRM_LANG]; ?></li>
							<li><a href="upload.php"><?php echo $lang_sidebar['add_package'][DCRM_LANG]; ?></a></li>
							<li><a href="manage.php"><?php echo $lang_sidebar['manage_package'][DCRM_LANG]; ?></a></li>
						<li class="nav-header"><?php echo $lang_sidebar['source'][DCRM_LANG]; ?></li>
							<li><a href="release.php"><?php echo $lang_sidebar['source_settings'][DCRM_LANG]; ?></a></li>
					</ul>
				</div>
			</div>
			<div class="span10">
				<?php
					if (!isset($_GET['action'])) {
				?>
				<h2><?php echo $lang_release['title'][DCRM_LANG]; ?></h2>
				<br>
				<form class="form-horizontal" method="POST" action="settings.php?action=set&token=<?php echo $_SESSION['token']; ?>">
					<fieldset>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_settings['username'][DCRM_LANG]; ?></label>
							<div class="controls">
								<input type="text" required="required" name="username" value="<?php echo DCRM_USERNAME; ?>"/>
							</div>
						</div>
						<br>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_settings['password'][DCRM_LANG]; ?></label>
							<div class="controls">
								<input type="text" name="password"/>
								<p class="help-block"><?php echo $lang_settings['change_password'][DCRM_LANG]; ?></p>
							</div>
						</div>
						<br>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_settings['trials'][DCRM_LANG]; ?></label>
							<div class="controls">
								<input type="text" required="required" name="trials" value="<?php echo DCRM_MAXLOGINFAIL; ?>"/>
							</div>
						</div>
						<br>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_settings['language'][DCRM_LANG] ?></label>
							<div class="controls">
								<select name="lang">
									<?php
										foreach ($langs as $lang_id => $lang_value) {
											if ($lang_id == DCRM_LANG) {
												echo "<option value=\"".$lang_id."\" selected=\"selected\">".$lang_value."</option>";
											}
											else {
												echo "<option value=\"".$lang_id."\">".$lang_value."</option>";
											}
										}
									?>
								</select>
							</div>
						</div>
						<br>
						<div class="form-actions">
							<div class="controls">
								<button type="submit" class="btn btn-success"><?php echo $lang_release['save'][DCRM_LANG]; ?></button>
							</div>
						</div>
					</fieldset>
				</form>
				<?php
					}
					elseif (!empty($_GET['action']) AND $_GET['action'] == "set" AND $_GET['token'] == $_SESSION["token"]) {
						$config_text = "<?php\n\tif (!defined(\"DCRM\")) {\n\t\texit;\n\t}\n";
						$config_text .= "\tdefine(\"DCRM_USERNAME\",\"".$_POST['username']."\");\n";
						if (!empty($_POST['password'])) {
							$config_text .= "\tdefine(\"DCRM_PASSWORD\",\"".sha1($_POST['password'])."\");\n";
						}
						else {
							$config_text .= "\tdefine(\"DCRM_PASSWORD\",\"".DCRM_PASSWORD."\");\n";
						}
						$config_text .= "\tdefine(\"DCRM_MAXLOGINFAIL\",\"".$_POST['trials']."\");\n";
						$config_text .= "\tdefine(\"DCRM_LANG\",\"".$_POST['lang']."\");\n";
						$config_handle = fopen("config.inc.php", "w");
						fputs($config_handle,stripslashes($config_text));
						fclose($config_handle);
						echo "<h2>".$lang_release['changes_applied'][DCRM_LANG]."</h2>";
					}
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php
	}
	else {
		header("Location: login.php");
	}
?>