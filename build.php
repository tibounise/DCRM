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
				<div class="btn-group" id="build">
					<a href="#" class="btn btn-inverse disabled"><?php echo $lang_topbtn['build'][DCRM_LANG]; ?> !</a>
					<a href="settings.php" class="btn btn-info"><?php echo $lang_topbtn['settings'][DCRM_LANG]; ?></a>
					<a href="login.php?action=logout" class="btn btn-info"><?php echo $lang_topbtn['logout'][DCRM_LANG]; ?></a>
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
				<h2><?php echo $lang_build['title'][DCRM_LANG]; ?></h2>
				<br />
					<?php
						$folder = opendir("deb");
						$files = array();
						while ($element = readdir($folder)) {
							if(preg_match("#.\.deb#", $element)) {
								$files[] = $element;
							}
						}
						if(empty($files)) {
							echo "Y U NO UPLOAD FILES ?";
						}
						else {
							$package_list = "";
							$error_log = "";
							foreach ($files as $file) {
								$package = "";
								exec("dpkg-deb -I deb/".$file." control",$filexec);
								foreach ($filexec as $line) {
									if(preg_match("#^Package|Source|Version|Priority|Section|Essential|Maintainer|Pre-Depends|Depends|Recommends|Suggests|Conflicts|Provides|Replaces|Enhances|Architecture|Filename|Size|Installed-Size|Description|Origin|Bugs|Name|Author|Homepage|Website|Depiction|Icon#", $line)) {
										$package[preg_replace("#^(.+): (.+)#","$1", $line)] = preg_replace("#^(.+): (.+)#","$2", $line);
									}
								}
								print_r($package);
								$package_list .= "\n";
								unset($md5);
							}
							if (file_exists("Packages")) {
								unlink("Packages");
							}
							$file = fopen("Packages", "a+");
							fputs($file,$package_list);
							fclose($file);
							if (file_exists("Packages.bz2")) {
								unlink("Packages.bz2");
							}
							$file = fopen("Packages.bz2","a+");
							fputs($file,bzcompress($package_list));
							fclose($file);
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