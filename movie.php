<!DOCTYPE html>
<html>

	<?php
		$movie = $_REQUEST["film"];
		$moviePath = "moviefiles/$movie";
		list($title, $year, $overall) = file("$moviePath/info.txt", FILE_IGNORE_NEW_LINES);
		$sidebarInfo = file("$moviePath/overview.txt", FILE_IGNORE_NEW_LINES);
		$reviewFiles = glob("$moviePath/review*.txt");
		$reviewCount = count($reviewFiles);
		
		//return sidebar info organized using dt and dd tags
		function getSidebar() {
			global $sidebarInfo;
			$sidebar = "";
			foreach ($sidebarInfo as $line) {
				list($section, $info) = explode(":", $line);
				$sidebar .= "<dt>$section</dt><dd>$info</dd>";
			}
			return $sidebar;
		}
		
		//prints out the critic reviews
		function printReviews() {
			global $reviewFiles;
			global $reviewCount;
			print("<div class='reviews-column'>");
			for ($i = 0; $i < $reviewCount; $i++) {
				list($quote, $status, $name, $company) = file($reviewFiles[$i], FILE_IGNORE_NEW_LINES);
				$img = getReviewImg($status);
				if ($i == floor(($reviewCount+1)/2)) {
					print("</div><div class='reviews-column'>");
				}
				print("
					<p>
						$img
						<q>$quote</q>
					</p>
					<p>
						<img src='critic.gif' alt='Critic' />
						$name<br />
						<em>$company</em>
					</p>
				");
			}
			print("</div>");
		}
		
		//returns the html for the review image (rotten or fresh)
		function getReviewImg($status) {
			if ($status == "ROTTEN") {
				return "<img src='rotten.gif' alt='Rotten' />";
			} else {
				return "<img src='fresh.gif' alt='Fresh' />";
			}
		}
		
		//returns the html img of fresh if the overall is over 60%, rotten otherwise
		function printReviewsHeader() {
			global $overall;
			if ($overall < 60) {
				return "<img src='rottenbig.png' alt='Rotten' />$overall%";
			} else {
				return "<img src='freshbig.png' alt='Fresh' />$overall%";
			}

		}

	?>

	<head>
		<title>Rancid Tomatoes</title>
		<link rel="icon" href="rotten.gif" />
		<meta charset="utf-8" />
		<link href="movie.css" type="text/css" rel="stylesheet" />
	</head>

	<body>

		<div id="image-banner">
			<img src="banner.png" alt="Rancid Tomatoes" />
		</div>
		<h1>
			<?=$title?> (<?=$year?>)
		</h1>
		
		<div id="content">
			<div id="overview">
				<div>
					<img src="<?="moviefiles/$movie/overview.png"?>" alt="general overview" />
				</div>

				<dl>
					<?=getSidebar()?>
				</dl>
			</div>

			<div id="reviews">
				<div id="reviews-header">
					<?=printReviewsHeader()?>
				</div>
				<?=printReviews()?>
			</div>

			<p id="content-footer"><?="(1-$reviewCount) of $reviewCount"?></p>
		</div>

		<div id="validators">
			<a href="https://webster.cs.washington.edu/validate-html.php"><img src="http://webster.cs.washington.edu/w3c-html.png" alt="Valid HTML5" /></a> <br />
			<a href="https://webster.cs.washington.edu/validate-css.php"><img src="http://webster.cs.washington.edu/w3c-css.png" alt="Valid CSS" /></a>
		</div>
	</body>
</html>
