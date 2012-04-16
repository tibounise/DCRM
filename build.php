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
					<a href="#" class="btn btn-inverse disabled"><?php echo $lang_topbtn['build'][DCRM_LANG]; ?> !</a>
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
								if ((!empty($package['MD5Sum']) AND $package['MD5Sum'] != md5_file("deb/".$file)) OR (!empty($package['Size']) AND $package['Size'] != filesize("deb/".$file))) {
									
									$error_log .= str_replace("//PACKAGE//",$file,$lang_build['corrupted_informations'][DCRM_LANG])."\n";
								}
								else {
									if (empty($package['MD5Sum'])) {
										$package['MD5Sum'] = md5_file("deb/".$file);
									}
									if (empty($package['Size'])) {
										$package['Size'] = filesize("deb/".$file);
									}
									foreach ($package as $field => $content) {
										$package_list .= $field.": ".$content."\n";
									}
								}
								$package_list .= "\n";
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
				<div class="wrapper">
					<div class="item">
						<?php
							if (!empty($error_log)) {echo $error_log;}
							else {echo }
						?>
					</div>
				</div>
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