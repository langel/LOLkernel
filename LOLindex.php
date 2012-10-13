<?php

# define helpful things
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');

require('kernel/head.php');

LOL::POKE('kernel|api_call',substr($_SERVER['REQUEST_URI'],1));

require('kernel/app/boot.php');

LOL::POKE('PAGE_BUILD',LOL::Render(LOL::PEEK('kernel|api_call')));

require('kernel/app/done.php');

echo LOL::PEEK('PAGE_BUILD');

?>
