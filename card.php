<?
// Ecriture du referrer
/*
$filename = 'referrers.txt';
$ref = trim($_SERVER["HTTP_REFERER"]);
if(strlen($ref) > 0)
{
	$known_hosts = array('www.glop.org', 'www.bakec.com');
	$url = parse_url($ref);
	$host = $url['host'];
	if(!in_array($host, $known_hosts))
	{
		$handle = fopen($filename, 'a');
		fwrite($handle, $ref."\n");
		fclose($handle);
	}
}
*/

/*
 * On recupere:
 *   - $gamertag
 *   - $format
 *   - $options[]
 */
$get = explode('/', $_SERVER['REQUEST_URI']);
$filename_complete = explode('.', $get[count($get) - 1]);
$format = array_pop($filename_complete);
$file_name = array_pop($filename_complete);
$file_name_array = explode('-', $file_name);
$gamertag = urldecode(array_shift($file_name_array));
$options = $file_name_array;

// Recup du format
if(!( ($format == 'gif') || ($format == 'jpg')))
{
	$format = 'png';
}

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
if(in_array($options[0], $valid_modes))
{
	$mode = array_shift($options);
}
else
{
	$mode = 'original';
}

// Parcours des autres options
while (count($options) > 0)
{
	switch($options[0])
	{
		case 'clantag':
			array_shift($options);
			$clantag = array_shift($options);
			if(strlen($clantag) > 4)
			{
				$clantag = substr($clantag, 0, 4);
			}
			break;
		case 'refresh':
			array_shift($options);
			define('REFRESH', true);
			break;
		case 'debug':
			array_shift($options);
			define('DEBUG', true);
			break;
		default:
			array_shift($options);
	}
}

// Recup du gamertag
$gamertag = strtolower(trim($gamertag));
$gamertag = preg_replace('/[^a-z0-9 ]/','',$gamertag);
$gamertag = urlencode($gamertag);

if(strlen($gamertag) == 0)
	$gamertag = 'analogue';

// Check du cache
$cachefile = 'cache/'.$gamertag.'-'.$mode;
if($clantag)
{
	$cachefile .= '-clantag-'.$clantag;
}
$cachefile .= '.'.$format;

if(file_exists($cachefile))
{
	$mtime = filemtime($cachefile);
	$difftime = time() - $mtime;
	if (($difftime < 600) && !defined('REFRESH'))
	{
		switch($format)
		{
			case 'gif':
				$img = @imagecreatefromgif($cachefile);
				header("Content-type: image/gif");
				imageGif($img); 
				break;
			case 'jpg':
				$img = @imagecreatefromjpeg($cachefile);
				header("Content-type: image/jpeg");
				imageJpeg($img); 
				break;
			default:
				$img = @imagecreatefrompng($cachefile);
				header("Content-type: image/png");
				imagePng($img); 
		}
		imageDestroy($img);
		exit();
	}
}

$url = 'http://gamercard.xbox.com/'.urlencode($gamertag).'.card';
$font_verdana = 'data/fonts/verdana.ttf';
$font_verdana_b = 'data/fonts/verdanab.ttf';
$font_visitor1 = 'data/fonts/visitor1.ttf';
$font_visitor2 = 'data/fonts/visitor2.ttf';
$font_x360 = 'data/fonts/x360.ttf';

function myimagecreatefromjpeg($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$fichier = curl_exec($ch);
	curl_close($ch);

	$tmpfname = tempnam('tmp', 'jpg');
	file_put_contents($tmpfname, $fichier);
	$image = imagecreatefromjpeg($tmpfname);
	unlink($tmpfname);

	return $image;
}

function myimagecreatefrompng($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$fichier = curl_exec($ch);
	curl_close($ch);

	$tmpfname = tempnam('tmp', 'png');
	file_put_contents($tmpfname, $fichier);
	$image = imagecreatefrompng($tmpfname);
	unlink($tmpfname);

	return $image;
}

function GetGamertile($data)
{
	preg_match("@<img class=\"XbcgcGamertile\" height=\"64\" width=\"64\" src=\"(.*?)\" alt=\".*?\" title=\".*?\" />@", $data, $matches);
	$gamertile = $matches[1];
	if(strpos($gamertile, 'http://') === false)
		$gamertile = 'http://gamercard.xbox.com'.$gamertile;
	return $gamertile;
}

function GetGamertag($data)
{
	preg_match("@\<h3 class=\"XbcGamertag.*?\"><a href=\"http://live.xbox.com/member/.*?\"><span class=\"XbcFLAL\">(.*?)</span></a></h3>@", $data, $matches);
	$gamertag = $matches[1];
	return $gamertag;
}

function GetSub($data)
{
	preg_match("@\<h3 class=\"XbcGamertag(.*?)\"><a href=\"http://live.xbox.com/member/.*?\"><span class=\"XbcFLAL\">.*?</span></a></h3>@", $data, $matches);
	$sub = $matches[1];
	return $sub;
}

function GetRep($data)
{
	$to_find = '/xweb/lib/images/gc_repstars_external_';
	$pos = strpos($data, $to_find) + strlen($to_find);
	$end = strpos($data, '.', $pos);
	$rep = substr($data, $pos, $end - $pos);
	return $rep;
}

function GetScore($data)
{
	preg_match("@<span class=\"XbcFLAL\"><img alt=\".*?\" src=\".*?\" /></span><span class=\"XbcFRAR\">(.*?)</span>@", $data, $matches);
	$score = $matches[1];
	return $score;
}

function GetZone($data)
{
	preg_match("@<span class=\"XbcFLAL\">Zone</span><span class=\"XbcFRAR\">(.*?)</span>@", $data, $matches);
	$zone = $matches[1];
	return $zone;
}

/* function GetGames($data)
{
	preg_match_all("@<img height=\"32\" width=\"32\" title=\".*?\" alt=\"\" src=\"(.*?)\" />@", $data, $matches);
	$games = $matches[1];
	return $games;
} */

function GetGames($data)
{
	preg_match("#<div class=\"XbcgcGames\">(.+?)</div>#", $data, $matches);
	$html_games = $matches[1];
	preg_match_all("#<a href=\"(.+?)\"><img height=\"32\" width=\"32\" title=\"(.+?)\" alt=\"\" src=\"(.+?)\" /></a>#", $html_games, $matches);
	//print_r($matches);
	unset($games);
	$i = 0;
	while($matches[1][$i] && $matches[1][$i] && $matches[1][$i])
	{
			$games[$i]['url'] = $matches[1][$i];
			$games[$i]['title'] = $matches[2][$i];
			$games[$i]['img'] = $matches[3][$i];
			$i++;
	}
	return $games;
}

// Récupération des données
// $fichier = file_get_contents($url);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$fichier = curl_exec($ch);
curl_close($ch);

// Tri des infos récupérées
$user['gamertile'] = GetGamertile($fichier);
$user['gamertag'] = GetGamertag($fichier);
$user['sub'] = GetSub($fichier);
$user['rep'] = GetRep($fichier);
$user['score'] = GetScore($fichier);
$user['zone'] = GetZone($fichier);
if(($mode == 'original') || ($mode == 'games') || ($mode == 'forumlarge') || ($mode == 'forumgames') || ($mode == 'cod2') || ($mode == 'doa4') || ($mode == 'doacard') || ($mode == 'graw') || ($mode == 'pgr3') || ($mode == 'newo'))
{
	$user['games'] = GetGames($fichier);
}

// Affichage en mode debug
if(defined('DEBUG'))
{
	echo "<pre>\n";
	echo "MODE DEBUG\n\n";

	echo 'Gamertile: '.$user['gamertile'];
	echo "\n";
	echo 'Subscription: '.$user['sub'];
	echo "\n";
	echo 'Gamertag: '.$user['gamertag'];
	echo "\n";
	echo 'Rep: '.$user['rep'];
	echo "\n";
	echo 'Score: '.$user['score'];
	echo "\n";
	echo 'Zone: '.$user['zone'];
	echo "\n";
	echo 'Games: ';
	echo "\n";
	print_r($user['games']);

	echo "</pre>\n";
}
// Affichage en mode release (image)
else
{
	// Lecture du background
	if($mode == 'small')
	{
		$img = @imagecreatefrompng('data/background-small.png');
	}
	elseif($mode == 'games')
	{
		$img = @imagecreatefrompng('data/background-games.png');
	}
	elseif($mode == 'forum')
	{
		$img = @imagecreatefrompng('data/background-forum.png');
	}
	elseif($mode == 'forumwide')
	{
		$img = @imagecreatefrompng('data/background-forumwide.png');
	}
	elseif($mode == 'forumlarge')
	{
		$img = @imagecreatefrompng('data/background-forumlarge.png');
	}
	elseif($mode == 'forumgames')
	{
		$img = @imagecreatefrompng('data/background-forumgames.png');
	}
	elseif($mode =='userbar')
	{
		$img = @imagecreatefrompng('data/background-userbar.png');
	}
	elseif($mode =='userbargreen')
	{
		$img = @imagecreatefrompng('data/background-userbargreen.png');
	}
	elseif($mode =='cod2')
	{
		$img = @imagecreatefrompng('data/background-cod2.png');
	}
	elseif($mode =='doa4')
	{
		$img = @imagecreatefrompng('data/background-doa4.png');
	}
	elseif($mode =='doacard')
	{
		$img = @imagecreatefrompng('data/background-doacard.png');
	}
	elseif($mode =='graw')
	{
		$img = @imagecreatefrompng('data/background-graw.png');
	}
	elseif($mode =='pgr3')
	{
		$img = @imagecreatefrompng('data/background-pgr3.png');
	}
	elseif($mode =='newo')
	{
		$img = @imagecreatefrompng('data/background-newo.png');
	}
	else
	{
		$img = @imagecreatefrompng('data/background-original.png');
	}

	// Création de quelques couleurs
	$white = imagecolorallocate($img, 255, 255, 255);
	$grey = imagecolorallocate($img, 81, 81, 81);
	$black = imagecolorallocate($img, 0, 0, 0);
	$green = imagecolorallocate($img, 127, 173, 42);
	$yellow = imagecolorallocate($img, 255, 212, 9);

	// Ajout de l'abonnement (gold, silver)
	if (($mode == 'original') || ($mode == 'small') || ($mode == 'games'))
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/title-silver.png');
			imagecopy($img, $iSub, 3, 3, 0, 0, 197, 18);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'cod2')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-cod2-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 320, 12);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'doa4')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-doa4-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 320, 12);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'doacard')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-doacard-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 197, 12);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'graw')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-graw-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 197, 12);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'pgr3')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-pgr3-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 197, 12);
			imageDestroy($iSub);
		}
	}
	elseif ($mode == 'newo')
	{
		if($user['sub'] == 'Silver')
		{
			$iSub = @imagecreatefrompng('data/background-newo-title-silver.png');
			imagecopy($img, $iSub, 1, 1, 0, 0, 193, 17);
			imageDestroy($iSub);
		}
	}

	// Inclusion du clantag
	if($clantag && ($mode != 'cod2') && ($mode != 'doa4'))
	{
		$user['gamertag'] = '['.$clantag.'] '.$user['gamertag'];
	}

	// Ajout du gamertag
	if (($mode == 'forum') || ($mode == 'forumwide'))
	{
		imagettftext($img, 8, 0, 4, 12, $green, $font_verdana_b, $user['gamertag']);
	}
	elseif($mode == 'forumlarge')
	{
		imagettftext($img, 8.5, 0, 5, 13, $green, $font_verdana_b, $user['gamertag']);
		/*
		imagettftext($img, 8.5, 0, 48, 36, $grey, $font_verdana, 'Reputation:');
		imagettftext($img, 8.5, 0, 48, 47, $grey, $font_verdana, 'Gamerscore:');
		imagettftext($img, 8.5, 0, 48, 58, $grey, $font_verdana, 'Last game played:');
		*/
	}
	elseif($mode == 'forumgames')
	{
		imagettftext($img, 8.5, 0, 5, 13, $green, $font_verdana_b, $user['gamertag']);
		// imagettftext($img, 8.5, 0, 48, 58, $grey, $font_verdana, 'Zone used:');
		// imagettftext($img, 8.5, 0, 331, 15, $grey, $font_verdana, 'Last games played:');
	}
	elseif($mode == 'userbar')
	{
		imagettftext($img, 20, 0, 25, 15, -($grey), $font_visitor2, $user['gamertag']);
	}
	elseif($mode == 'userbargreen')
	{
		imagettftext($img, 10, 0, 4, 14, $white, $font_x360, $user['gamertag']);
	}
	elseif(($mode == 'cod2') || ($mode == 'doa4') || ($mode == 'doacard') || ($mode == 'graw') || ($mode == 'pgr3'))
	{
		imagettftext($img, 8, 0, 7, 11, $black, $font_x360, $user['gamertag']);
	}
	elseif($mode == 'newo')
	{
		imagettftext($img, 10, 0, 8, 14, $black, $font_verdana_b, $user['gamertag']);
	}
	else
	{
		imagettftext($img, 10, 0, 11, 17, $black, $font_verdana_b, $user['gamertag']);
	}

	if (($mode == 'original') || ($mode == 'small'))
	{
		// Ajout du Gamertile
		$iGamertile = myimagecreatefrompng($user['gamertile']);
		imagecopy($img, $iGamertile, 11, 25, 0, 0, 64, 64);
		imageDestroy($iGamertile);

		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/gc_repstars_external_'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 130, 34, 0, 0, 65, 10);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(8, 0, $font_verdana, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8, 0, 194 - $textwidth, 63, $white, $font_verdana, $user['score']);

		// Ajout de la zone
		$bbox = imageftbbox(8, 0, $font_verdana, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8, 0, 194 - $textwidth, 82, $white, $font_verdana, $user['zone']);
	}
	elseif (($mode == 'forum') || ($mode == 'forumwide'))
	{
		if($mode == 'forum')
		{
			$text_offset = 0;
		}
		else
		{
			$text_offset = 96;
		}
		// Ajout du Gamertile
		$iGamertile = @myimagecreatefrompng($user['gamertile']);
		imagecopyresized($img, $iGamertile, 3, 22, 0, 0, 32, 32, 64, 64);
		imageDestroy($iGamertile);

		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/grey-'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 42 + $text_offset, 22, 0, 0, 63, 9);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(8, 0, $font_verdana, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8, 0, 104 - $textwidth + $text_offset, 43, $grey, $font_verdana, $user['score']);

		// Ajout de la zone
		$bbox = imageftbbox(7, 0, $font_verdana, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 7, 0, 104 - $textwidth + $text_offset, 54, $grey, $font_verdana, $user['zone']);
	}
	elseif ($mode == 'forumlarge')
	{
		// Ajout du Gamertile
		$iGamertile = @myimagecreatefrompng($user['gamertile']);
		imagecopyresized($img, $iGamertile, 5, 27, 0, 0, 32, 32, 64, 64);
		imageDestroy($iGamertile);

		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/grey-'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 252, 28, 0, 0, 63, 9);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(8.5, 0, $font_verdana, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8.5, 0, 313 - $textwidth, 47, $grey, $font_verdana, $user['score']);

		// Ajout du dernier jeu joué
		if($user['games'][0]['title'])
		{
			$lastgame = $user['games'][0]['title'];
		}
		else
		{
			$lastgame = '--';
		}
		$bbox = imageftbbox(8.5, 0, $font_verdana, $lastgame);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8.5, 0, 315 - $textwidth, 58, $grey, $font_verdana, $lastgame);
	}
	elseif ($mode == 'forumgames')
	{
		// Ajout du Gamertile
		$iGamertile = @myimagecreatefrompng($user['gamertile']);
		imagecopyresized($img, $iGamertile, 5, 27, 0, 0, 32, 32, 64, 64);
		imageDestroy($iGamertile);

		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/grey-'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 132, 28, 0, 0, 63, 9);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(8.5, 0, $font_verdana, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8.5, 0, 193 - $textwidth, 47, $grey, $font_verdana, $user['score']);

		// Ajout de la zone
		$bbox = imageftbbox(8.5, 0, $font_verdana, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8.5, 0, 193 - $textwidth, 58, $grey, $font_verdana, $user['zone']);
	}
	elseif ($mode == 'userbar')
	{
		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/gc_repstars_external_'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 281 , 5, 0, 0, 65, 10);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(20, 0, $font_visitor2, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 20, 0, 265 - $textwidth , 15, -($white), $font_visitor2, $user['score']);
	}
	elseif ($mode == 'userbargreen')
	{
		$fontsize = 7.5;
		$fontname = $font_visitor1;
		// Ajout de la zone
		$bbox = imageftbbox($fontsize, 0, $fontname, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		$rightindent = $textwidth + 2;
		imagettftext($img, $fontsize, 0, 350 - $rightindent, 12, -($white), $fontname, $user['zone']);

		// Ajout du logo gamerscore
		$iRep = @imagecreatefromgif('data/logo-gamerscore-green.gif');
		$rightindent = $rightindent + 15;
		imagecopy($img, $iRep, 350 - $rightindent , 3, 0, 0, 13, 12);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox($fontsize, 0, $fontname, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		$rightindent = $rightindent + $textwidth;
		imagettftext($img, $fontsize, 0, 350 - $rightindent, 12, -($white), $fontname, $user['score']);
	}
	elseif (($mode == 'cod2') || ($mode == 'doa4'))
	{
		$fontsize = 8;
		// Ajout de la zone
		$bbox = imageftbbox($fontsize, 0, $font_x360, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 320 - $textwidth, 25, $white, $font_x360, $user['zone']);

		// Ajout du score
		$bbox = imageftbbox($fontsize, 0, $font_x360, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 320 - $textwidth, 37, $white, $font_x360, $user['score']);

			// Ajout du dernier jeu joué
		if($user['games'][0]['title'])
		{
			$lastgame = $user['games'][0]['title'];
		}
		else
		{
			$lastgame = '--';
		}
		$bbox = imageftbbox($fontsize, 0, $font_x360, $lastgame);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 320 - $textwidth, 49, $white, $font_x360, $lastgame);

		// Ajout du tag de clan
		if(strlen($clantag) == 0)
		{
			$clantag = '--';
		}
		$bbox = imageftbbox($fontsize, 0, $font_x360, $clantag);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 320 - $textwidth, 61, $white, $font_x360, $clantag);
	}
	elseif (($mode == 'doacard') || ($mode == 'graw') || ($mode == 'pgr3'))
	{
		$fontsize = 8;
		// couleur
		if ($mode == 'graw')
		{
			$current_color = $yellow;
		}
		else
		{
			$current_color = $white;
		}
		// Ajout de la zone
		$bbox = imageftbbox($fontsize, 0, $font_x360, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 195 - $textwidth, 85, $current_color, $font_x360, $user['zone']);

		// Ajout du score
		$bbox = imageftbbox($fontsize, 0, $font_x360, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 195 - $textwidth, 51, $current_color, $font_x360, $user['score']);

		// Ajout du dernier jeu joué
		if($user['games'][0]['title'])
		{
			$lastgame = $user['games'][0]['title'];
		}
		else
		{
			$lastgame = '--';
		}
		$bbox = imageftbbox($fontsize, 0, $font_x360, $lastgame);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, $fontsize, 0, 195 - $textwidth, 124, $current_color, $font_x360, $lastgame);
	}
	elseif ($mode == 'newo')
	{
		// Ajout du Gamertile
		$iGamertile = @myimagecreatefrompng($user['gamertile']);
		imagecopy($img, $iGamertile, 5, 22, 0, 0, 64, 64);
		imageDestroy($iGamertile);

		// Ajout de la réputation
		$iRep = @imagecreatefromgif('data/stars/gc_repstars_external_'.$user['rep'].'.gif');
		imagecopy($img, $iRep, 125, 22, 0, 0, 65, 10);
		imageDestroy($iRep);

		// Ajout du score
		$bbox = imageftbbox(8, 0, $font_verdana, $user['score']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8, 0, 189 - $textwidth, 46, $black, $font_verdana, $user['score']);

		// Ajout de la zone
		$bbox = imageftbbox(8, 0, $font_verdana, $user['zone']);
		$textwidth = abs($bbox[0]) + abs($bbox[2]);
		imagettftext($img, 8, 0, 189 - $textwidth, 63, $black, $font_verdana, $user['zone']);
	}

	// Ajout des tiles des derniers jeux joués
	if(($mode == 'original') || ($mode == 'games') || ($mode == 'forumgames'))
	{
		if ($mode == 'original')
		{
			$games_top = 102;
			$games_left = 10;
			$games_space = 6;
		}
		elseif ($mode == 'games')
		{
			$games_top = 24;
			$games_left = 10;
			$games_space = 6;
		}
		elseif ($mode == 'forumgames')
		{
			$games_top = 27;
			$games_left = 203;
			$games_space = 4;
		}

		// Ajout du premier jeu
		if($user['games'][0]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][0]['img']);
			imagecopy($img, $iGame, $games_left, $games_top, 0, 0, 32, 32);
			imageDestroy($iGame);
		}

		// Ajout du second jeu
		if($user['games'][1]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][1]['img']);
			imagecopy($img, $iGame, $games_left+(32+$games_space)*1, $games_top, 0, 0, 32, 32);
			imageDestroy($iGame);
		}

		// Ajout du troisieme jeu
		if($user['games'][2]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][2]['img']);
			imagecopy($img, $iGame, $games_left+(32+$games_space)*2, $games_top, 0, 0, 32, 32);
			imageDestroy($iGame);
		}

		// Ajout du quatrieme jeu
		if($user['games'][3]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][3]['img']);
			imagecopy($img, $iGame, $games_left+(32+$games_space)*3, $games_top, 0, 0, 32, 32);
			imageDestroy($iGame);
		}

		// Ajout du cinquieme jeu
		if($user['games'][4]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][4]['img']);
			imagecopy($img, $iGame, $games_left+(32+$games_space)*4, $games_top, 0, 0, 32, 32);
			imageDestroy($iGame);
		}
	}
	elseif ($mode == 'newo')
	{
		// Ajout du premier jeu
		if($user['games'][0]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][0]['img']);
			imagecopyresized($img, $iGame, 149, 70, 0, 0, 16, 16, 32, 32);
			imageDestroy($iGame);
		}

		// Ajout du second jeu
		if($user['games'][1]['img'])
		{
			$iGame = @myimagecreatefromjpeg($user['games'][1]['img']);
			imagecopyresized($img, $iGame, 171, 70, 0, 0, 16, 16, 32, 32);
			imageDestroy($iGame);
		}
	}

	/* Output image to browser */
	switch($format)
	{
		case 'gif':
			header("Content-type: image/gif");
			imageGif($img, $cachefile);
			imageGif($img);
			break;
		case 'jpg':
			header("Content-type: image/jpg");
			imageJpeg($img, $cachefile);
			imageJpeg($img);
			break;
		default:
			header("Content-type: image/png");
			imagePng($img, $cachefile);
			imagePng($img);
	}
	imageDestroy($img);
}