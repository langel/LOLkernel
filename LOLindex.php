<?php

require('kernel/head.php');

require('kernel/app/boot.php');

LOL::POKE('PAGE_BUILD',LOL::Render($_GET['LOLquery']));

require('kernel/app/done.php');

echo LOL::PEEK('PAGE_BUILD');

?>
