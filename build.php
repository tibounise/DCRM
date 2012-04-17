<?php
	session_start();
	define("DCRM",true);
	require_once("config.inc.php");
	require_once("langs.inc.php");

	class phpAr {
		private $file;
		private $init;

		function __construct($file)
		{
			if (!empty($file) AND file_exists($file)) {
				$this->file = $file;
			}
			else {
				echo "File opening failed.";
			}
		}
		function listfiles() {
			$handle = fopen($this->file,"rb");
			if (fread($handle, 7) == "!<arch>") {
				fread($handle, 1);
				$filesize = filesize($this->file);
				$list_files = array();
				while (ftell($handle) < $filesize-1) {
					$list_files[] = trim(fread($handle, 16));
					fread($handle, 32);
					$size = trim(fread($handle, 10));
					fread($handle, 2);
					fseek($handle, ftell($handle) + $size);
				}
				return $list_files;
			}
			else {
				return false;
			}
			fclose($handle);
		}
		function getfile($name) {
			$handle = fopen($this->file,"rb");
			if (fread($handle, 7) == "!<arch>") {
				fread($handle, 1);
				$filesize = filesize($this->file);
				$file_output = array();
				while (ftell($handle) < $filesize-1) {
					$filename = trim(fread($handle, 16));
					if ($filename == $name) {
						$timestamp = trim(fread($handle, 12));
						$owner_id = trim(fread($handle, 6));
						$group_id = trim(fread($handle, 6));
						$file_mode = trim(fread($handle, 8));
						$size = trim(fread($handle, 10));
						fread($handle, 2);
						$content = fread($handle, $size);
						$file_output[] = array($name,$timestamp,$owner_id,$group_id,$file_mode,$size,$content);
					}
					else {
						fread($handle, 32);
						$size = trim(fread($handle, 10));
						fread($handle, 2);
						fseek($handle, ftell($handle)+$size);
					}
				}
				return $file_output;
			}
			else {
				return false;
			}
			fclose($handle);
		} 
	}

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
								if (!is_dir("tmp")) {
									mkdir("tmp");
								}
								elseif (file_exists("tmp/control.tar.gz")) {
								 	unlink("tmp/control.tar.gz");
								}
								$debfile = new phpAr("deb/".$file);
								$control_file = $debfile->getfile('control.tar.gz');
								if ($control_file != false) {
									$control_handle = fopen("tmp/control.tar.gz", "w");
									fputs($control_handle,$control_file[0][6]);
									fclose($control_handle);
									$filexec = gzfile("tmp/control.tar.gz");
									foreach ($filexec as $line) {
										$line = trim($line);
										if(preg_match("#^Package|Source|Version|Priority|Section|Essential|Maintainer|Pre-Depends|Depends|Recommends|Suggests|Conflicts|Provides|Replaces|Enhances|Architecture|Filename|Size|Installed-Size|Description|Origin|Bugs|Name|Author|Homepage|Website|Depiction|Icon#", $line)) {
											$package[trim(preg_replace("#^(.+): (.+)#","$1", $line))] = trim(preg_replace("#^(.+): (.+)#","$2", $line));
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
								else {
									$error_log .= str_replace("//PACKAGE//",$file,$lang_build['missing_control.tar.gz'][DCRM_LANG])."\n";
								}
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
					<ul class="breadcrumb"><i class="icon" id="triangle_errors" onclick="wrapper('triangle_errors','item_errors'); return false;">&#9658;</i>&nbsp;Errors</ul>
					<div class="item" style="display:none;" id="item_errors">
						<?php
							if (!empty($error_log)) {echo nl2br($error_log);}
							else {echo $lang_build['no_error'][DCRM_LANG];}
						?>
					</div>
					<ul class="breadcrumb" onclick="return false;"><i class="icon" id="triangle_packages" onclick="wrapper('triangle_packages','item_packages'); return false;">&#9658;</i>&nbsp;Packages file</ul>
					<div class="item" style="display:none;" id="item_packages">
						<?php echo nl2br($package_list); ?>
					</div>
				</div>
				<h3><?php echo str_replace("//ERRORS//", substr_count($error_log, "\n"), $lang_build['conclusion'][DCRM_LANG]) ?></h3>
			</div>
		</div>
	</div>
	<script>
		function wrapper(triangleitem, item) {
			var triangle = document.getElementById(triangleitem);
			var elementitem = document.getElementById(item);
			if (elementitem.style.display == "none") {
				triangle.style.mozTransform = "rotate(90deg)";
				triangle.style.webkitTransform = "rotate(90deg)";
				triangle.style.oTransform = "rotate(90deg)";
				triangle.style.transform = "rotate(90deg)";
				elementitem.style.display = "block";
			}
			else {
				elementitem.style.display = "none";
				triangle.style.mozTransform = "rotate(0deg)";
				triangle.style.webkitTransform = "rotate(0deg)";
				triangle.style.oTransform = "rotate(0deg)";
				triangle.style.transform = "rotate(0deg)";
			}
		}
	</script>
</body>
</html>
<?php
	}
	else {
		header("Location: login.php");
	}
?>