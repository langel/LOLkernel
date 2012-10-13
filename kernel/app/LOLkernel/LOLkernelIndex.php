<h1>LOLkernel can has render page</h1>

<p>
LOLkernel is quick and dirty.<br>
LOLkernel is a toy.<br>
LOLkernel will get your crap idea of a website off the ground.
</p>

<p>
... scanning all wuts ...
</p>

<h2>app wuts</h2>
<p>
<: $app_links ?>
</p>

<h2>kernel wuts</h2>
<p>
<: $kernel_links ?>
</p>

<; if ($has_db) { ?>
<h2>db hooked up</h2>
<; } else { ?>
<h2><a href="/LOLkernel/MySQLsetup">Hurry up and setup your database connection.</a></h2>
<; } ?>
