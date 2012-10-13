<form method="post" action="/LOLkernel/MySQLsetup">
<; foreach ($settings as $s) { 
	echo $s.BR.'<input name="'.$s.'"/>'.BR.CR;
} ?>
	<input type="submit" name="submit" val="submit">
</form>
