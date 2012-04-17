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
					<a href="settings.php" class="btn btn-info"><?php echo $lang_topbtn['settings'][DCRM_LANG]; ?></a>
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
						if (file_exists("Release")) {
							$release_file = file("Release");
							$release = array();
							foreach ($release_file as $line) {
								if(preg_match("#^Origin|Label|Suite|Version|Codename|Architectures|Components|Description#", $line)) {
									$release[trim(preg_replace("#^(.+): (.+)#","$1", $line))] = trim(preg_replace("#^(.+): (.+)#","$2", $line));
								}
							}
						}
				?>
				<h2><?php echo $lang_release['title'][DCRM_LANG]; ?></h2>
				<br>
				<form class="form-horizontal" method="POST" action="release.php?action=set">
					<fieldset>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_release['origin'][DCRM_LANG]; ?></label>
							<div class="controls">
								<input type="text" value="<?php if (!empty($release['Origin'])) {echo $release['Origin'];} ?>"/>
								<p class="help-block">Supporting help text</p>
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