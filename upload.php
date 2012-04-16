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
							<li class="active"><a href="#"><?php echo $lang_sidebar['add_package'][DCRM_LANG]; ?></a></li>
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
				<h2><?php echo $lang_upload['upload_title'][DCRM_LANG]; ?></h2>
				<br />
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="upload.php?action=upload">
					<fieldset>
						<div class="group-control">
							<label class="control-label"><?php echo $lang_upload['legend'][DCRM_LANG]; ?></label>
							<div class="controls">
								<input type="file" class="span6" name="deb">
							</div>
						</div>
						<br>
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn btn-success"><?php echo $lang_upload['upload_btn'][DCRM_LANG]; ?></button>
							</div>
						</div>
					</fieldset>
				</form>
				<?php
					}
					elseif ($_GET['action'] == "upload" AND !empty($_FILES)) {
						$deb = $_FILES['deb'];
						$ext = strtolower(pathinfo($_FILES['deb']['name'],PATHINFO_EXTENSION));
						if (preg_match("#^deb$#", $ext) AND !file_exists("deb/".$deb['name'])) {
							move_uploaded_file($deb['tmp_name'], "deb/".$deb['name'])
				?>
					<h2><?php echo $lang_upload['upload_successfull'][DCRM_LANG]; ?></h2>
				<?php
						}
						else {
				?>
					<h2><?php echo $lang_upload['upload_unsuccessfull'][DCRM_LANG]; ?></h2>
					<p><?php echo $lang_upload['upload_unsuccessfull_reason'][DCRM_LANG]; ?></p>
				<?php
						}
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