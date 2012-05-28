<form>
<input type="text" name="input_str" value="<?=$input_str?>" />
<input type="submit" />
</form>
<? if($input_str): ?>
<pre>
<?
$input_str = strtolower(trim($input_str));
$input_str = preg_replace('/[^a-z0-9 ]/','',$input_str);
$input_str = urlencode($input_str);
echo '|'.$input_str."|\n";
?>
</pre>
<? endif; ?>
