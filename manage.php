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
							<li class="active"><a href="#"><?php echo $lang_sidebar['manage_package'][DCRM_LANG]; ?></a></li>
						<li class="nav-header"><?php echo $lang_sidebar['source'][DCRM_LANG]; ?></li>
							<li><a href="release.php"><?php echo $lang_sidebar['source_settings'][DCRM_LANG]; ?></a></li>
					</ul>
				</div>
			</div>
			<div class="span10">
				<?php
					if (!isset($_GET['action'])) {
				?>
				<h1><?php echo $lang_manage['manage'][DCRM_LANG]; ?></h1>
				<?php
					$folder = opendir("deb");
					$files = array();
					while ($element = readdir($folder)) {
						if (preg_match("#.\.deb#", $element)) {
							$files[] = $element;
						}
					}
					if (empty($files)) {
						echo '<br>'.$lang_manage['nopackages'][DCRM_LANG];
					}
					else {
						sort($files);
						echo '<table class="table"><thead><tr><th class="span1"></th><th>'.$lang_manage['name'][DCRM_LANG].'</th><th>'.$lang_manage['size'][DCRM_LANG].'</th></tr></thead><tbody>';
						foreach ($files as $file) {
							$filesize = filesize("deb/".$file);
							$filesize_withext = ($filesize < 1048576) ? round($filesize/1024,2).' KiB' : round($filesize/1048576,2).' MiB';
							echo '<tr><td><a href="manage.php?action=delete_confirmation&token='.$_SESSION['token'].'&file='.urlencode($file).'" class="close" style="line-height: 12px;">&times;</a></td><td>'.$file.'</td><td>'.$filesize_withext.'</td></tr>';	
						}
						echo '</tbody></table>';
					}
					}
					elseif ($_GET['action'] == "delete_confirmation" AND !empty($_GET['file']) AND isset($_GET['token']) AND $_GET['token'] == $_SESSION['token'] AND file_exists("deb/".urldecode($_GET['file']))) {
				?>
				<h2><?php echo $lang_manage['delete_confirmation'][DCRM_LANG].' '.urldecode($_GET['file']).' ?'; ?></h2>
				<a class="btn btn-warning" href="manage.php?action=delete&token=<?php echo $_SESSION['token']; ?>&file=<?php echo urlencode($_GET['file']); ?>"><?php echo $lang_manage['yes'][DCRM_LANG]; ?></a>
				<a class="btn btn-success" href="manage.php"><?php echo $lang_manage['no'][DCRM_LANG]; ?></a>
				<?php
					}
					elseif ($_GET['action'] == "delete" AND !empty($_GET['file']) AND isset($_GET['token']) AND $_GET['token'] == $_SESSION['token'] AND file_exists("deb/".urldecode($_GET['file']))) {
						if (is_writable("deb/".urldecode($_GET['file']))) {
							unlink("deb/".urldecode($_GET['file']));
				?>
				<h2><?php echo urldecode($_GET['file']).' '.$lang_manage['delete_message'][DCRM_LANG]; ?></h2>
				<?php
						}
						else {
				?>
				<h2><?php echo $lang_manage['hasntpermissions'][DCRM_LANG] ?></h2>
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