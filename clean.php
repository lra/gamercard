<pre>
<?php
$d = dir('cache');
while (false !== ($entry = $d->read())) {
	if($entry == '.' || $entry == '..') {
		continue;
	}
	echo 'Deleting '.$entry;
	unlink('cache/'.$entry);
	echo ".\n";
	ob_flush();
	flush();
}
$d->close();
?>
</pre>
