<?php

/************************************************************

  Firteen Electronic Content Engine System

  PHP LIBRARY

  CODE v.100207

************************************************************/


// Is magic quotes on?
if (get_magic_quotes_gpc()) {
  // Yes? Strip the added slashes
  function stripslashes_array(&$array) {
    return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
  }
  $_COOKIE = stripslashes_array($_COOKIE);
  $_GET = stripslashes_array($_GET);
  $_POST = stripslashes_array($_POST);
  $_REQUEST = stripslashes_array($_REQUEST);
  #$_FILES = stripslashes_array($_FILES);
}

define('CR', "\r\n");
define('BR', "<br>");



function a($url,$text='',$title='',$classes='')  {
  if ($text=='') $text = $url;
  if ($title!='') $a = ' title="'.$title.'"';
  if ($classes!='') $a .= ' class="'.$classes.'"';
  return '<a href="'.$url.'"'.$a.'>'.$text.'</a>';
}

function Unu($url) {
  $url = 'http://u.nu/unu-api-simple?url='.urlencode($url);
  $a = curl_init();
  $timeout = 5;
  curl_setopt($a,CURLOPT_URL,$url);
  curl_setopt($a,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($a,CURLOPT_CONNECTTIMEOUT,$timeout);
  $url = curl_exec($a);
  curl_close($a);
  return trim($url);
}

function Gravatar($email,$size=80,$def='') {
  //$def = "/style/avatar_defualt.jpg";
  $a = 'http://www.gravatar.com/avatar/'.md5(strtolower($email)).'.jpg?size='.$size;
  if ($def!='')
    $a .= '&d='.urlencode($def);
  return $a;
}


function error($msg) {
   ?><html><head><script language="JavaScript">
   <!--
       alert("<?=$msg?>");
       history.back();
   //-->
   </script></head><body></body></html><?
   exit();
}


function ParseThisURL($url='') {
  if ($url=='') {
	$url = urldecode($_SERVER['REQUEST_URI']);
	$chunked = explode('/',$url);
	array_shift($chunked);
  }
  else {
  	$chunked = explode('/',$url);
  }
  poke('calls[]',$url);
  return $chunked;
}


function Encrypt($string) {
  $crypted = substr(crypt(md5($string), md5($string)),0,20);
  return $crypted;
}

function EncryptFilename($string) {
  $crypted = str_replace('/','',Encrypt($string));
  return $crypted;
}

function Now()  {
  return date('Y-m-d H:i:s');
}

function Today()  {
  return date('Y-m-d');
}

function Yesterday()  {
  return date('Y-m-d', time()-86400);
}

function DayName($val)  {
  $weekdays = array(
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Fakeday' );
  return $weekdays[$val];
}

function SplitDate($date) {
  $a = array();
  $a['y'] = substr($date,0,4);
  $a['m'] = substr($date,5,2);
  $a['d'] = substr($date,8,2);
  return $a;
}

function SplitTimestamp($timestamp) {
  $a = array();
  $a['y'] = substr($timestamp,0,4);
  $a['m'] = substr($timestamp,5,2);
  $a['d'] = substr($timestamp,8,2);
  $a['h'] = substr($timestamp,11,2);
  $a['i'] = substr($timestamp,14,2);
  $a['s'] = substr($timestamp,17,2);
  return $a;
}

function yymmddReturnTimestamp($string) {
  return GetTimeStamp($string.' 00:00:00');
}

function GetTimestamp($datetime)  {
  // Converts string into integer value.
  return mktime(substr($datetime,11,2), substr($datetime,14,2), substr($datetime,17,2), substr($datetime,5,2),substr($datetime,8,2),substr($datetime,0,4));
}

function TimeLeft($datetime)  {
  // Returns number of seconds left until $datetime.
  if (is_numeric($datetime))
    $wait = $datetime - time();
  else
    $wait = GetTimestamp($datetime) - time();
  if ($wait>0) return $wait;
  if ($wait<=0) return false;
}

function CountdownTime($datetime) {
  $wait = TimeLeft($datetime);
  $d = floor($wait/86400);
  if ($d) $a = $d.'d ';
  $h = floor(($wait-$d*86400)/3600);
  if ($h) $a .= $h.'h ';
  $m = floor(($wait-$d*86400-$h*3600)/60);
  if ($m) $a .= $m.'m ';
  $s = $wait-$d*86400-$h*3600-$m*60;
  if ($s) $a .= $s.'s ';
  return $a;
}

function TimestampDateTime($timestamp)  {
  return date('Y-m-d H:i:s',$timestamp);
}

function RssTimeDate($datetime) {
  return date("D, j M Y ", mktime(0, 0, 0, substr($datetime,5,2), substr($datetime,8,2), substr($datetime,0,4))).substr($datetime,11,8).date(" O");
}

function HandsomeTimeAgo($tm,$rcs=0) {
  $cur_tm = time();
  $dif = $cur_tm-$tm;
  $pds = array('second','minute','hour','day','week','month','year','decade');
  $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
  for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--);
  if($v < 0) $v = 0;
  $_tm = $cur_tm-($dif%$lngh[$v]);
  $no = floor($no);
  if($no <> 1) $pds[$v] .='s';
  $x=sprintf("%d&nbsp;%s",$no,$pds[$v]);
  if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
  return $x;
}

function HandsomeDay($datetime) {
  return date("l, F jS", GetTimestamp($datetime));
}

function HandsomeDayYear($datetime) {
  return date("M j 'y", GetTimestamp($datetime));
}

function HandsomeDate($datetime) {
  return date("y.m.d", GetTimestamp($datetime));
}

function HandsomeTime($datetime) {
  return date("g:ia", GetTimestamp($datetime));
}

function HandsomeTimeDate($datetime) {
  return date("g:ia m/d/y", GetTimestamp($datetime));
}

function HandsomeTimeDayYear($datetime) {
  return date("l, F jS Y g:ia", GetTimestamp($datetime));
}

function Plural($count,$y='') {
  if ($y=='y')
    return ($count==1?'y':'ies');
  else
    return ($count==1?'':'s');
}

function Capture($eval) {
  ob_start();
  eval($eval.';');
  $a = ob_get_contents();
  ob_end_clean();
  return $a;
}

function LineBreaksHTML2FORM($text) {
  return str_replace('<br/>',"\r\n",$text);
}

function RemoveHTMLbreaks($text) {
  return str_replace('<br/>',"",$text);
}

// deprecate with nl2br(string[,is_xhtml=true])
function InferHTMLbreaks($text) {
  return str_replace("\n", "<br>\n", $text);
}

function LazyLinks($text,$str='',$attr='')  {
	// this filter breaks if there is a > character before the protocal
	// make sure line breaks <br> go before newline characters \n
  $filter = '/((\s|^)(ht|f)tps?+:\/\/[\w-?&;#~=%\+\.\/\@]+)/i';
  if ($str=='')
    return preg_replace($filter,"<b><a href=\"$1\" ".$attr.">$1</a></b>",$text);
  else
    return preg_replace($filter,"<b><a href=\"$1\" ".$attr.">".$str."</a></b>", $text);
}

function RipDomain($url)  {
  if(strpos($url, '/', 8))
    return substr($url, 0, strpos($url, '/', 8));
  else
    return $url;
}

function KillHTML($text)  {
  return strip_tags($text);
}

function h($text) {
  return str_replace("\n", "\n<br/>", htmlspecialchars($text));
}

function titleURLfriendly($URL)  {
  $input  = array("&", "?", ";", ":", "@", "=", "/", "\\", "%", "#", ",");
  return str_replace($input,"_",$URL);

  $URL = str_replace(array("&", "'",  '"',  "?",  " ",".","/","\\","#"),
                    array("and","%27","%34","_", "_","_","_","_", "_"),$URL);
  return $URL;
}

function URLfriendly($URL)  {
  $input  = array("#",  "%",  "&",  "/",  ":",  ";",  "=",  "?",  "@",  "[",  "\\", "]",  " ", ",");
  $output = array("%35","%37","%38","%47","%58","%59","%61","%63","%64","%91","%92","%93","%20", "%2C");
  return str_replace($input,$output,$URL);

  $URL = str_replace(array("&",  "'",  "?",  "=", " ",  "/",    "\\", "#", "¿"),
                    array("%26","%27","%3F","%3D","%20","%252F","%5C","%23","%BF"),$URL);
  return $URL;
}

function UTF2ISO($string) {
  return iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $string);
}

function FixWebsiteURL($URL,$encode=TRUE) {
  if ($URL!='http://'&&$URL!='') {
    if ($encode)
      $URL = urlencode($URL);
    if (substr($URL, 0, 7) == 'http://' ||
        substr($URL, 0, 6) == 'ftp://')
      return $URL;
    else
      return 'http://'.$URL;
  }
  else
    return FALSE;
}

function XMLfriendly($XML)  {
  $XML = str_replace(array('"',"&","'","<",">"),array("&#x0022;","&#x0026;","&#x0027;","&#x003c;","&#x003e;"),$XML);
  return $XML;
}

function DOSfriendly($filename)  {
  $filename = str_replace(array("\\","/",":","*","?",'"',"<",">","|"),
                          "_",$filename);
  return $filename;
}

function DeUnderscore($str) {
  return str_replace('_',' ',$str);
}

function ReturnFileExtension($filename) {
  return substr($filename,strrpos($filename,'.')+1);
}

function NiceFileSize($size) {
  if ($size==='')
    return '';
  $units = array('&nbsp;bytes','kb','mb','gb','tb');
  $unit = 0;
  $k = 1024;
  while ($size>$k) {
    $size = $size / $k;
    $unit++;
  }
  if ($unit==0||is_int($size))
    return number_format($size, 0).$units[$unit];
  else
    return number_format($size, 1, '.', '').$units[$unit];
}

function Redir($url)  {
 header("Location: $url");
 exit;
}

function JavaRedir($url) {
   ?><script type="text/javascript">
  <!--
    window.location = "<?=$url?>"
  //-->
  </script>
   <?
  exit;
}

function MetaRedir($url,$seconds=0) {
  //echo "<meta http-equiv=\"Refresh\" content=\"N;url=$url\">";
  //print$url.$seconds;
  return '<meta http-equiv="Refresh" content="'.$seconds.'; url='.$url.'">';
}

function LinkConfirm($URL, $confirmation, $text)  {
  return '<a href="'.$URL.'" onClick="javascript:return confirm(\''.$confirmation.'\')">'.$text.'</a>';
}

function CheckFile($file,$caller='N/A') {
  if (is_file($file)) return $file;
  else ErrorOut('Missing or Lost File :: '.$file.'<br/>called from :: '.$caller);
}

function ErrorOut($string)  {
  //trigger_error('##|SANTYX ERROR<br/>?'.$string,E_USER_ERROR);
  if (function_exists('DoFourOhFour'))  {
    DoFourOhFour($string);
  }
  else  {
    header('HTTP/1.0 404 Not Found');
    print '##|SANTYX ERROR<br/>?'.$string;
    die();
  }
}

function GetBackTrace() {
  ob_start();
  debug_print_backtrace();
  $lol = ob_get_contents();
  ob_end_clean();
  return $lol;
}

function ReturnBacktrace()  {
  $backtrace = debug_backtrace();
  array_shift($backtrace);
  //array_shift($backtrace);
  //array_shift($backtrace);
  foreach ($backtrace as $call) {
    $file = substr($call['file'],strlen(LOCAL_DIR)-2);
    $err .= '<p>line #<b>'.$call['line'].' &nbsp; &nbsp ';
    if ($call['class'])
      $err .= $call['class'];
    if ($call['type'])
      $err .= $call['type'];
    if ($call['function'])
      $err .= $call['function'];
    $args = array();
    if ($call['args'])  {
      foreach ($call['args'] as $arg) {
        switch (gettype($arg))  {
          case 'object':
            $args[] = 'object';
            break;
          case 'array':
            $args[] = 'array';
            break;
          default:
            $args[] = "'$arg'";
            break;
        }
      }
    }
    $err .= '('.implode(',',$args).');'.CR;
    $err .= '<br/></b>'.$file.'</p>'.CR;
  }
  return $err;
}

function ucTitle($string) {
  $string = strtolower($string);
  $string = join(" ", array_map('ucwords', explode(" ", $string)));
  $string = join(".", array_map('ucwords', explode(".", $string)));
  $string = join("(", array_map('ucwords', explode("(", $string)));
  $string = join("[", array_map('ucwords', explode("[", $string)));
  $string = join("/", array_map('ucwords', explode("/", $string)));
  $string = join("-", array_map('ucwords', explode("-", $string)));
  $string = join("Mac", array_map('ucwords', explode("Mac", $string)));
  $string = join("Mc", array_map('ucwords', explode("Mc", $string)));
  return trim($string);
}

function ucFirstPos($string) {
  for ($a = 0; $a <= strlen($string); $a++) {
    $c = substr($string,$a,1);
    if (ord($c)>=65&&ord($c)<=90) return $a;
  }
  return FALSE;
}

function OrdinalSuffix($number) {     // returns English ordinal suffix
  if ($number % 100 > 10 && $number %100 < 14) $suffix = "th";
  else  {
    switch($number % 10) {
      case 0:
        $suffix = "th";
        break;

      case 1:
        $suffix = "st";
        break;

      case 2:
        $suffix = "nd";
        break;
      case 3:
        $suffix = "rd";
        break;
      default:
        $suffix = "th";
        break;
     }
  }
  return $suffix;
}

function PlacedSuffix($n) {   // adds suffix to a number
  if ($n=='') return $n;
}

function CheckEmail($email) {
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
// Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
      return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}


function LeadingZeros($str,$len=8) {
  return str_pad($str,$len,"0",STR_PAD_LEFT);
}

function ZeroPad($str,$len=5) {
  return LeadingZeros($str,$len);
}


function FlatFile2HTML($file) {
  if (is_file($file))  {
    $f = fopen($file,"r");
    $HTML = InferHTMLbreaks(fread($f, filesize($file)));
    fclose($f);
    return $HTML;
  }
}


function deldir($d) {
  $OS = getenv("SERVER_SOFTWARE");
  if (strstr($OS, "Win32"))
    $d = str_replace('/','\\',$d);
  if (is_dir($d)) {
    rmdir($d);
    return TRUE;
  }
  else
    return FALSE;
}

function deltree($d) {
  if (is_dir($d)) {
    foreach(glob($d.'/*') as $f) {
      if (is_dir($f) && !is_link($f)) {
        deltree($f);
      } else {
        unlink($f);
      }
    }
  }
  deldir($d);
}

function colorHEX($r,$g,$b) {                     // returns hex HTML from bytes $r $g $b
  $r = dechex($r);
  if (strlen($r)==1) $r = '0'.$r;
  $g = dechex($g);
  if (strlen($g)==1) $g = '0'.$g;
  $b = dechex($b);
  if (strlen($b)==1) $b = '0'.$b;
  return '#'.$r.$g.$b;
}

function GetMimeType($filename) {
  $fext = ReturnFileExtension($filename);
  switch ($fext)  {
    case 'js':
      return 'application/x-javascript';
    case 'json':
      return 'application/json';
    case 'jpeg':
    case 'jpg':
      return 'image/jpg';
    case 'png':
      return 'image/png';
    case 'gif':
      return 'image/gif';
    case 'bmp':
      return 'image/bmp';
    case 'tiff':
      return 'image/tiff';
    case 'css':
      return 'text/css';
    case 'xml':
      return 'application/xml';
  }
}


function AddLog($log,$txt)  {
  $f = fopen($log,'a');
  fwrite($f,$txt);
  fclose($f);
}



function GetRealIP()  {
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = $ip[0];
  }
  else
    $ip = $_SERVER['REMOTE_ADDR'];
  return $ip;
}


function CheckMobile()  {
  // this code borrowed from http://www.brainhandles.com/techno-thoughts/detecting-mobile-browsers

  if (isset($_SERVER["HTTP_X_WAP_PROFILE"]))
    return true;

  if (preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"]))
    return true;

  if (preg_match("/iphone/i",$_SERVER["HTTP_USER_AGENT"]))
    return false;

  if (isset($_SERVER["HTTP_USER_AGENT"])) {
    // Quick Array to kill out matches in the user agent
    // that might cause false positives
    $badmatches = array("Creative\ AutoUpdate","OfficeLiveConnector","MSIE\ 8\.0","OptimizedIE8","MSN\ Optimized","Creative\ AutoUpdate","Swapper");
    foreach($badmatches as $badstring)  {
      if(preg_match("/".$badstring."/i",$_SERVER["HTTP_USER_AGENT"]))
        return false;
    }

    // Now we'll go for positive matches
    $uamatches = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
    foreach($uamatches as $uastring)  {
      if (preg_match("/".$uastring."/i",$_SERVER["HTTP_USER_AGENT"]))
        return true;
    }
  }
  return false;
}

?>
