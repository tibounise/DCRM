<?php
	define("DCRM",true);
	require_once("config.inc.php");
	require_once("langs.inc.php");
	$release = file("Release");
	$release_origin = "Unnamed DCRM repo";
	foreach ($release as $line) {
		if(preg_match("#^Origin#", $line)) {
			$release_origin = trim(preg_replace("#^(.+): (.+)#","$2", $line));
		}
	}
?>
<!doctype html>
	<html>
		<head>
			<title><?php echo $release_origin; ?></title>
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
			<div class="well">
				<?php
					echo $release_origin;
					echo "<hr>";
					echo str_replace("//URL//", "<code>".base64_decode(DCRM_REPOURL)."</code>", $lang_homepage['pleaseadd'][DCRM_LANG]);
					if (DCRM_SHOWLIST == 1) {
						echo "<br><br><div class=\"wrapper\">";
						echo "<ul class=\"breadcrumb\"><i class=\"icon\" id=\"source_triangle\" onclick=\"wrapper('source_triangle','item_source'); return false;\">&#9658;</i>&nbsp;".$lang_homepage['package'][DCRM_LANG]."s</ul>";
						echo '<table class="table" id="item_source" style="display: none;"><thead><tr><th class="span5">'.$lang_homepage['package'][DCRM_LANG].'</th></tr></thead><tbody>';
						$folder = opendir("deb");
						while ($element = readdir($folder)) {
							if (preg_match("#.\.deb#", $element)) {
								echo "<tr><td><a href=\"deb/".$element."\">".$element."</a></td></tr>";
							}
						}
						echo '</tbody></table>';
						echo "</div>";
						echo "</div>";
					}
				?>
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