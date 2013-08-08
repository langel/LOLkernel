<?php

/************************************************************

  Firteen Electronic Content Engine System

  SLOT SWATCHER

  CODE v.100215

************************************************************/



class css {
  function Attach($name,$URL='') {
    if ($URL=='')
      $URL = $name;
    poke('css|'.$name,$URL);
  }

  function Add($code) {
    poke('css_embed',peek('css_embed').$code);
  }

  function Clear()  {
    poke('css',NULL);
    poke('css_embed',NULL);
  }
}


class js  {
  function Attach($script)  {
    if (substr($script,0,1)!='/')
      $script = '/js/'.$script;
    poke('js|'.$script,'<script type="text/javascript" src="'.$script.'"></script>'.CR);
  }

  function Add($code)  {
    poke('js_embed',peek('js_embed').$code);
  }

  function Clear()  {
    poke('js',NULL);
    poke('js_embed',NULL);
  }

  function jQueryInit() {
    poke('js_jQuery_enabled',TRUE);
  }

  function Get()  {
    return peek('js_embed');
  }
}


class jQuery  {
  function Add($code) {
    poke('jQuery',peek('jQuery').$code);
  }
  function Get()  {
    if (peek('jQuery')!='')
      return '$(document).ready(function(){'.peek('jQuery').'});';
  }
}




class meta  {

  function Attach($markup)  {
    poke('meta[]',$markup);
  }


  function Set($name,$content)  {
    poke('meta|'.$name,'<meta name="'.$name.'" content="'.$content.'"/>');
  }


  function SetTags(&$obj) {

    $t = str_replace("\r\n",' ',KillHTML($obj->meta['description']));
    if (strlen($t)>190)
      $t = substr($t,0,190).' ...';
    if ($t=='')
      meta::Set('description',$obj->meta['name'].' has no description.');
    else
      meta::Set('description',$t);

    $k = KillHTML($obj->keywords);
    if ($k!='')
      meta::Set('keywords',$obj->meta['keywords']);
  }


  function Redir($url,$seconds) {
    meta::Attach(MetaRedir($url,$seconds));
  }

}



class template  {
  function RenderString($str)  {
    $str = str_replace('<o ','<?php echo $obj->',$str);
    $str = str_replace('<: ','<?php echo ',$str);
    $str = str_replace('<;','<?php ',$str);
    return $str;
  }
  function Render($file,$error='TEMPLATE RENDERER')  {
    CheckFile($file,$error);
    return template::RenderString(file_get_contents($file));
  }
}


class slot {

  function GetSwatch($what,$name,$caller) {
    $address = 'swatch|'.$what.'|'.$name;
    if (peek($address!=''))
      return peek($address);
    else  {
      $f = LOCAL_DIR.'whats/'.$what.'/'.$what.'Swatch'.ucfirst($name).'.php';
      $s = template::Render(CheckFile($f,'SWATCHLOADER slot::'.$what.'Swatch'.ucfirst($name)));
      poke($address,$s);
      return $s;
    }
  }

  function BuildSingle($what,$name,$obj)  {
    if (!is_object($obj))
      return 'slotBuild('.$what.','.$name.') missing $obj';
    $swatch = slot::GetSwatch($what,$name,'BuildSingle');
    $stack->mem['swatch'][$what.$name] = $swatch;
    GLOBAL $WHAT, $ACT, $PARAM1, $PARAM2, $PARAM3, $Guy;
    $extra_globals = STACK::GetExtraGlobals();
    foreach ($extra_globals as $g)
      eval('GLOBAL '.$g.';');
    ob_start();
    eval(' ?>'.$swatch.'<?php ');
    $slot = ob_get_contents();
    ob_end_clean();
    return $slot;
  }

  function Build($what,$name,$obj_list,$glue='') {
    if (!is_array($obj_list))
      return $what.' returned empty set';
    $stack = STACK::Handshake();
    $swatch = slot::GetSwatch($what,$name,'BuildSingle');
    $stack->mem['swatch'][$what.$name] = $swatch;
    GLOBAL $WHAT, $ACT, $PARAM1, $PARAM2, $PARAM3, $Guy;
    $extra_globals = STACK::GetExtraGlobals();
    foreach ($extra_globals as $g)
      eval('GLOBAL '.$g.';');
    if (substr($glue,0,1)=='$')
      eval('GLOBAL '.$glue.'; $glue = '.$glue.';');
    ob_start();
    $slots = array();
    foreach ($obj_list as $obj) {
      eval(' ?>'.$swatch.'<?php ');
      $slots[] = ob_get_contents();
      ob_clean();
    }
    ob_end_clean();
    $slot = implode($glue,$slots);
    //print $slot;
    return $slot;
  }

}


class swatch  {

  function Load($what,$name)  {
    $stack = STACK::Handshake();
    $f = LOCAL_DIR.'whats/'.$what.'/'.$what.'Swatch'.ucfirst($name).'.php';
    if (is_file($f))
      $stack->mem['swatch'][$what.$name] = template::Render($f);
    else
      $stack->mem['swatch'][$what.$name] = chr(0);
    return $stack->mem['swatch'][$what.$name];
  }

}


/*

look in kernel/CACHEARM.php for teh real class!!! =)

class cache {


  function Fetch($swatch,&$obj) {

    $filename = 'cache/'.$swatch.'/'.LeadingZeros($obj->id);

    //if (!is_file($filename))
      cache::Render($swatch,$obj);

    return readfile($filename);
  }


  function Save($swatch,&$obj,$data)  {

    if (!is_dir('cache/'.$swatch))  mkdir('cache/'.$swatch);

    $filename = 'cache/'.$swatch.'/'.LeadingZeros($obj->id);
    $f = fopen($filename,'w');
    fwrite($f,$data);
    fclose($f);
  }


  function Render($swatch,&$obj) {

    $a = 'Cache render fail.  :(';

    if (is_object($obj))
      $a = slot::BuildSingle($obj->table_name,$swatch,$obj);

    else if (is_array($obj))
      $a = slot::Build($obj[0]->table_name,$swatch,$obj);

    else
      $a = slot::Build($obj->table_name,$swatch,$obj);

    cache::Save($swatch,$obj,$a);
    return $a;
  }

}

*/

?>