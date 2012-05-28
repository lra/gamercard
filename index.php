<? echo '<?xml version="1.0" encoding="iso-8859-1"?>'."\n"; ?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=iso-8859-1" />
	<title>Xbox Live Gamercard Generator</title>
	<meta name="Description" content="Generate your Xbox Live Gamercard and display it anywhere" />
	<meta name="Keywords" content="xbox live 360 gamercard generator gif jpg jpeg png" />
	<link href="css/1.css" rel="stylesheet" type="text/css" />
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
		_uacct = "UA-107561-2";
		urchinTracker();
	</script>
</head>

<body>
<a name="top" id="top"></a>
<center>
	<div id="menu">
		<a href="#introduction">Introduction</a>
		<a href="#generate">Generate your Gamercard</a>
		<a href="#desktop">Put it on your desktop</a>
		<a href="#google">Use it on Google</a>
		<a href="http://www.glop.org/forum/viewforum.php?id=12">Official Forum</a>
	</div>
	
	<?
	/*
	$nb_generated_cards = -2; // fast method to count out '.' and '..'
	if ($handle = opendir('cache')) {
		while (false !== ($file = readdir($handle))) {
			$nb_generated_cards++;
		}
		closedir($handle);
	}
	*/
	?>

	<div id="header">
		<h1>Xbox Live Gamercard Generator</h1>
		<h2>Already 100,000+ unique Gamercards generated !</h2>
	</div>
		
	<div id="content">

		<img src="images/xbox-controller.png" alt="Logo" class="logo" />
		
		<p class="introduction">
			The gamer card is, as the name implies, an at-a-glance look at your player history on and off Xbox Live.
			In short, it's the public face you show on Xbox Live. Other gamers can see all of this information at the
			touch of a button, including your gamertag, your gamerscore, your reputation, the Gamer Zone you prefer,
			and your achievements.
		</p>
		
		<div id="sidebar">
			<h1>Menu</h1>
			<div class="submenu">
				<a href="#introduction">Introduction</a>
				<a href="#generate">Generate your Gamercard</a>
				<a href="#desktop">Put it on your desktop</a>
				<a href="#google">Use it on Google</a>
				<a href="http://www.glop.org/forum/viewforum.php?id=12">Official Forum</a>
			</div>
				
			<p>
				Feel free to use one of those similar tools, but keep in mind that I made mine because they all lacked
				something in the end (stability, price, choice, finition).
			</p>

			<h1>Similar tools</h1>

			<div class="submenu">
				<a href="http://www.3deurope.com/Wiki/default.aspx/ZoneWiki.GamerCards">3DEurope's Gamercards</a>
				<a href="http://www.liveinsanantonio.net/xbox/">Gamertag Generator</a>
				<a href="http://www.icp4ever.com/FinalSite/GamerCard/">Icp4ever Gamer Card</a>
				<a href="http://www.livecard.net/">LiveCard</a>
				<a href="http://www.mygamercard.net/">MyGamerCard</a>
				<a href="http://www.consti.de/external/gamercard/">PHP Gamercard Extractor</a>
				<a href="http://www.x360central.com/?section=gamercard">x360Central Gamercard</a>
				<a href="http://www.xbox-corner.com/gamercard.php">Xbox Corner</a>
			</div>

			<p>
				Choose the one you want, competition is what brings improvments !
			</p>

			<div class="support">
				<br />
				<script type="text/javascript"><!--
				google_ad_client = "pub-9935615233494380";
				google_ad_width = 160;
				google_ad_height = 600;
				google_ad_format = "160x600_as";
				google_ad_type = "text_image";
				google_ad_channel ="";
				google_color_border = "A8DDA0";
				google_color_bg = "EBFFED";
				google_color_link = "0000CC";
				google_color_url = "008000";
				google_color_text = "6F6F6F";
				//--></script>
				<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="analogue@glop.org">
					<input type="hidden" name="item_name" value="Gamercard Generator">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="tax" value="0">
					<input type="hidden" name="bn" value="PP-DonationsBF">
					<input type="image" src="https://www.paypal.com/fr_FR/i/btn/x-click-but04.gif" border="0" name="submit" alt="Effectuez vos paiements via PayPal : une solution rapide, gratuite et sécurisée !">
				</form>
				<script type="text/javascript">
					var flattr_url = 'http://www.glop.org/gamercard/';
					</script>
					<script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>
			</div>

		</div>
				
		<div id="mainbar">
			<h1><a name="introduction" id="introduction"></a>Introduction</h1>
			
			<img src="images/xbox-theme.jpg" alt="Xbox Theme" class="articleimg" />
				
			<p>
				By creating the concept of Gamercard, the Xbox Live team has given the gamer the opportunity to materialize its
				gaming experience. But, as with some features of the Xbox 360 (Very limited file format support, inexistant video
				support), the concept of Gamercard isn't as polished as we expected. Hence the arrival of some independant projects
				trying to fill the hole left by Microsoft. But neither of them plainly satisfied me, so I decided to build mine.
			</p>
						
			<?
			// Recup du mode
			$valid_modes = array(
				'original',
				'small',
				'games',
				'forum',
				'forumwide',
				'forumlarge',
				'forumgames',
				'userbar',
				'userbargreen',
				'cod2',
				'doa4',
				'doacard',
				'graw',
				'pgr3',
				'newo');
			if(!in_array($_GET['mode'], $valid_modes))
			{
				$mode = 'original';
			}
			else
			{
				$mode = $_GET['mode'];
			}
			// Recup du format
			$valid_formats = array(
				'gif',
				'jpg',
				'png');
			if(!in_array($_GET['format'], $valid_formats))
			{
				$format = 'png';
			}
			else
			{
				$format = $_GET['format'];
			}
			?>

			<? if($_GET['submit']): ?>

			<h1>Your Gamercard</h1>

			<?
			// Recup du gamertag
			$gamertag = $_GET['gamertag'];
			$gamertag = strtolower(trim($gamertag));
			$gamertag = preg_replace('/[^a-z0-9 ]/','',$gamertag);
			$gamertag = urlencode($gamertag);
			if(strlen($gamertag) == 0)
				$gamertag = 'analogue';
			// Recup du clantag
			$clantag = trim($_GET['clantag']);
			$clantag = preg_replace('/[^A-Za-z0-9]/','',$clantag);
			$clantag = urlencode($clantag);
			if(strlen($clantag) > 4)
			{
				$clantag = substr($clantag, 0, 4);
			}

			// Création URL gamercard
			$url = 'http://www.glop.org/gamercard/card/'.$gamertag;
			if($mode != 'original')
			{
				$url .= '-'.$mode;
			}
			if($clantag)
			{
				$url .= '-clantag-'.$clantag;
			}
			$url .= '.'.$format;

			?>

			<p>
				<img class="gamercard" src="<?=$url?>" alt="<?=urldecode($gamertag)?>'s Gamercard" /><br />
				<br />
				Here's your own Gamercard. From now on, it will be dynamically generated and wherever you may put it, it will always
				display up to date information relative to your account.
			</p>

			<h2>Raw URL of your new Gamercard</h2>

			<p>
				<input class="wide" type="text" value="<?=$url?>" />
			</p>

			<h2>HTML code (for blogs and websites)</h2>

			<p>
<textarea class="wide" class="green" wrap="off" rows="2" readonly="readonly" scrollbars="off">
<a href="http://live.xbox.com/member/<?=$gamertag?>"><img src="<?=$url?>" alt="<?=urldecode($gamertag)?>'s Gamercard" border="0" /></a>
<br />Get your own <a href="http://www.glop.org/gamercard/">Gamercard Sig</a>.
</textarea>
			</p>

			<h2>BB code (for forum signatures)</h2>

			<p>
<textarea class="wide" class="green" wrap="off" rows="2" readonly="readonly" scrollbars="off">
[url=http://live.xbox.com/member/<?=$gamertag?>][img]<?=$url?>[/img][/url]
Get your own [url=http://www.glop.org/gamercard/]Gamercard Sig[/url].
</textarea>
			</p>
			<? endif; ?>

			<h1><a name="generate" id="generate"></a>Generate your Gamercard</h1>

			<form class="generate" method="get" action="<?=basename($PHP_SELF)?>">
			<table>
				<tr>
					<td class="label" colspan="2"><label for="gamertag">Gamertag:</label></td>
				</tr>
				<tr>
					<td colspan="2"><input type="text" name="gamertag" value="<?=urldecode($gamertag)?>" size="32" /></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td class="label" colspan="2"><label for="clantag">Clan Tag <i>(optional, 4 chars max, eg. <b>BkF</b>)</i>:</label></td>
				</tr>
				<tr>
					<td colspan="2"><input type="text" name="clantag" value="<?=urldecode($clantag)?>" size="4" maxlength="4" /></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td class="label" colspan="2"><label for="mode">Gamercard:</label></td>
				</tr>
				<tr>
					<? if($mode == 'original'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_original" value="original" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_original" value="original"/></td>
					<? endif; ?>
					<td><label for="mode_original"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue.png" alt="Original Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'small'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_small" value="small" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_small" value="small" /></td>
					<? endif; ?>
					<td><label for="mode_small"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-small.png" alt="Small Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'games'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_games" value="games" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_games" value="games" /></td>
					<? endif; ?>
					<td><label for="mode_games"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-games.png" alt="Games Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'forum'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forum" value="forum" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forum" value="forum" /></td>
					<? endif; ?>
					<td><label for="mode_forum"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-forum.png" alt="Forum Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'forumwide'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumwide" value="forumwide" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumwide" value="forumwide" /></td>
					<? endif; ?>
					<td><label for="mode_forumwide"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-forumwide.png" alt="Wide Forum Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'forumlarge'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumlarge" value="forumlarge" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumlarge" value="forumlarge" /></td>
					<? endif; ?>
					<td><label for="mode_forumlarge"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-forumlarge.png>" alt="Large Forum Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'forumgames'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumgames" value="forumgames" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_forumgames" value="forumgames" /></td>
					<? endif; ?>
					<td><label for="mode_forumgames"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-forumgames.png" alt="Large Forum Gamercard with Games" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'userbar'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_userbar" value="userbar" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_userbar" value="userbar" /></td>
					<? endif; ?>
					<td><label for="mode_userbar"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-userbar.png" alt="Userbar Gamercard" /></label></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td class="label" colspan="2"><label for="mode">Designs provided by <a href="http://www.shiftgame.com/">Shiftgame</a>:</label></td>
				</tr>
				<tr>
					<? if($mode == 'userbargreen'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_userbargreen" value="userbargreen" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_userbargreen" value="userbargreen" /></td>
					<? endif; ?>
					<td><label for="mode_userbargreen"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-userbargreen.png" alt="Green Userbar Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'cod2'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_cod2" value="cod2" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_cod2" value="cod2" /></td>
					<? endif; ?>
					<td><label for="mode_cod2"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-cod2.png" alt="CoD2 Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'doa4'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_doa4" value="doa4" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_doa4" value="doa4" /></td>
					<? endif; ?>
					<td><label for="mode_doa4"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-doa4.png" alt="DOA4 Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'doacard'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_doacard" value="doacard" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_doacard" value="doacard" /></td>
					<? endif; ?>
					<td><label for="mode_doacard"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-doacard.png" alt="DOA Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'graw'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_graw" value="graw" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_graw" value="graw" /></td>
					<? endif; ?>
					<td><label for="mode_graw"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-graw.png" alt="GRAW Gamercard" /></label></td>
				</tr>
				<tr>
					<? if($mode == 'pgr3'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_pgr3" value="pgr3" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_pgr3" value="pgr3" /></td>
					<? endif; ?>
					<td><label for="mode_pgr3"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-pgr3.png" alt="PGR3 Gamercard" /></label></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td class="label" colspan="2"><label for="mode">Design provided by <a href="http://www.xenon-360.fr/">neWo</a>:</label></td>
				</tr>
				<tr>
					<? if($mode == 'newo'): ?>
						<td><input class="radio" type="radio" name="mode" id="mode_newo" value="newo" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="mode" id="mode_newo" value="newo" /></td>
					<? endif; ?>
					<td><label for="mode_newo"><img class="gamercard" src="http://www.glop.org/gamercard/card/analogue-newo.png" alt="neWo's Gamercard" /></label></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td class="label" colspan="2"><label for="mode">Format:</label></td>
				</tr>
				<tr>
					<? if($format == 'gif'): ?>
						<td><input class="radio" type="radio" name="format" id="format_gif" value="gif" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="format" id="format_gif" value="gif" /></td>
					<? endif; ?>
					<td><label for="format_gif">GIF <i>(256 colors)</i></label></td>
				</tr>
				<tr>
					<? if($format == 'jpg'): ?>
						<td><input class="radio" type="radio" name="format" id="format_jpg" value="jpg" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="format" id="format_jpg" value="jpg" /></td>
					<? endif; ?>
					<td><label for="format_jpg">Jpeg <i>(Lossy compression)</i></label></td>
				</tr>
				<tr>
					<? if($format == 'png'): ?>
						<td><input class="radio" type="radio" name="format" id="format_png" value="png" checked="checked" /></td>
					<? else: ?>
						<td><input class="radio" type="radio" name="format" id="format_png" value="png" /></td>
					<? endif; ?>
					<td><label for="format_png">PNG <i>(Recommended !)</i></label></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Generate" />
					</td>
				</tr>
			</table>
			</form>

			<h1><a name="desktop" id="desktop"></a>Put it on your desktop</h1>

			<p>
				Some people don't like the way Microsoft choose to allow them to put their Gamercard on their desktop, added to the
				fact that they can't choose anything else than the default Gamercard style. Here's an easy and clean way to do it:
			</p>

			<ul>
				<li>Rightclick on your desktop and select <b>Properties</b>.</li>
				<li>Go to the <b>Desktop</b> tab, then click on <b>Customize Desktop</b>.</li>
				<li>Go to the <b>Web</b> tab, then click on <b>New...</b> and enter the URL of your the Gamercard picture you generated
					here when asked for a location. For example: <i>http://www.glop.org/gamercard/card/analogue-small.png</i></li>
				<li>Done !</li>
			</ul>
												
			<h1><a name="google" id="google"></a>Use it on Google</h1>

			<img src="images/google-logo.gif" alt="Google Logo" class="articleimg" />
				
			<p>
				Recently, Google has released a customizable homepage to the public. The personalized homepage brings together Google
				functionality and content from across the web on a single page, and saves you a few clicks in the process. If you are
				using the <a href="http://www.google.com/ig">Google Personalized Homepage</a>, you can now include a Gamercard on it.
				To do so, simply add this <a href="google-gamercard.xml">XML File</a> by using the <i>Add Content &gt; Create a Section</i>
				menu, and in the preference pane of the added module, choose the Gamertag you want to be displayed.
			</p>
		</div>

	</div>
  <div id="footer">
		Page design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.<br />
		&copy; 2005 <a href="http://www.glop.org/">Laurent Raufaste</a>.
	</div>
</center>
</body>
</html>
