<?php
/*================================================

  -==Firteen Electronic Content Engine System==-

                   v0.73.090419

================================================*/

define('LOCAL_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');     ## DEFINE LOCAL PATHS
define('LOCAL_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

require('config.php');                                          ## SERVER CONFIGURATION

require('kernel/_FECES_lib.php');                               ## FECES FUNCTIONAL LIBRARY
require('kernel/_KERNELWRAP.php');                              ## FECES KERNEL ROUTINE
require('kernel/_STACKMEM.php');                                ## FECES MEMORY MANAGER
require('kernel/_FOHAT.php');                                   ## FECES OBJECT HANDLER
require('kernel/_SLOTSWATCH.php');                              ## FECES DISPLAY HALPER
require('kernel/_CACHEHAND.php');                               ## FECES RENDER SPEEDER

FECES::BOOTUP();                                                ## BOOTUP
require_once(CheckFile('whats/boot.php','BOOT LOADER'));

require_once(CheckFile(peek('control'),'CONTROL LOADER'));      ## AGGREGATE
STACK::UpdateGlobals();

ob_start();
eval(' ?>'.template::Render(peek('view')).'<?php ');            ## RENDER VIEW
$MainOutput = ob_get_contents();
ob_end_clean();
                                                                ## OUTPUT PAGE
eval(' ?>'.template::Render(CheckFile(peek('header'),'HEADER')).'<?php ');
echo $MainOutput;
flush();

require_once(CheckFile('whats/unwind.php','POST PAGE LOGIC'));
eval(' ?>'.template::Render(CheckFile(peek('footer'),'FOOTER')).'<?php ');


# STACK::VisualDump();

FECES::SHUTDOWN();                                              ## night night ^^

?>
