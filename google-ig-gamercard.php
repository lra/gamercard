<?
$gamertag = urlencode(strtolower(trim($_GET['up_gamertag'])));

if(strlen($gamertag) == 0)
	$gamertag = 'analogue';

$url = 'http://gamercard.xbox.com/'.urlencode($gamertag).'.card';
$template_path = 'data/template-google.html';

function GetGamertile($data)
{
	preg_match("@<img class=\"XbcgcGamertile\" height=\"64\" width=\"64\" src=\"(.*?)\" />@", $data, $matches);
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

function PutHTMLGame($game)
{
	if($game['url'] && $game['title'] && $game['img'])
	{
		$html  = '<a href="'.$game['url'].'">';
		$html .= '<img height="32" width="32" title="'.$game['title'].'" alt="" src="'.$game['img'].'" />';
		$html .= '</a>';
	}
	return $html;
}

// Récupération des données
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
$user['games'] = GetGames($fichier);

// Affichage en mode debug
if(isset($_GET['debug']))
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
	print_r($user['games']);
	echo "\n";

	echo "</pre>\n";
}
// Affichage en mode release (image)
else
{
	$template = file_get_contents($template_path);
	$search = array('%SUB%',
		'%GAMERTAG_HTML%',
		'%GAMERTAG%',
		'%GAMERTILE%',
		'%REP%',
		'%SCORE%',
		'%ZONE%',
		'%GAME_1%',
		'%GAME_2%',
		'%GAME_3%',
		'%GAME_4%',
		'%GAME_5%');
	$replace = array($user['sub'],
		urlencode($user['gamertag']),
		$user['gamertag'],
		$user['gamertile'],
		$user['rep'],
		$user['score'],
		$user['zone'],
		PutHTMLGame($user['games'][0]),
		PutHTMLGame($user['games'][1]),
		PutHTMLGame($user['games'][2]),
		PutHTMLGame($user['games'][3]),
		PutHTMLGame($user['games'][4]));
	$html = str_replace($search, $replace, $template);
	echo($html);
}
?>
