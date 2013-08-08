<?php
/**********************************************************

  Firteen Electronic Content Engine System

  CACHE HAND of DILIGENCE

  CODE v.110214

**********************************************************/

class CACHEHAND {

  const   ROOT = 'disk/cache/';
  const   EXTENSION = '.cak';

  function __construct($name,$shelfLife=15) {
    $this->file = self::ROOT.$name.self::EXTENSION;
    if (is_file($this->file))  {
      $cachePurgeTime = filemtime($this->file) + $shelfLife;
      if (time() > $cachePurgeTime) {
        unlink($this->file);
      } else  {
        $this->content = file_get_contents($this->file);
      }
    }
    return $this;
  }

  function Delete($name)  {
    $a = new CACHEHAND($name);
    $a->Clear();
  }


  function IsEmpty()  {
    return ($this->content=='') ? true : false;
  }


  function Save() {
    $file = fopen($this->file,'w');
    fwrite($file,$this->content);
    fclose($file);
  }


  function RecordStart()  {
    ob_start();
  }


  function RecordStop() {
    $this->content = ob_get_contents();
    ob_end_clean();
    $this->Save();
  }


  function SetContent($content) {
    $this->content = $content;
    $this->Save();
  }


  function Cache() {
    return $this->content;
  }


  function Dump() {
    echo $this->content;
  }

  function Clear()  {
    if (is_file($this->file)) {
      $this->content = '';
      unlink ($this->file);
    }
  }


}

?>