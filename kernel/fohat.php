<?php defined('HOME_DIR') or die('LOLblech');

/************************************************************

  Firteen Electronic Content Engine System

  FOHAT -- FECES Ontological H{alo|elm} Accessing Tables

  CODE v.100221

************************************************************/


function __autoload($wut) {
	FOHAT::AUTOLOAD($wut);
}


class FOHAT {

	function AUTOLOAD($wut) {

		$model_file = $wut.'/'.$wut.'_class.php';

		if (is_file('app/'.$model_file)) {
			require_once('app/'.$model_file);
		}
		elseif (is_file('kernel/app'.$model_file)) {
			require_once('kernel/app'.$model_file);
		}
		else {
		// XXX	should we check if there is an existing table first?
		//		could keep table schema in disk directory
			eval("class {$wut} extends FOHAT {
				function __construct() {
				  \$this->table_name = '{$wut}';
				}

				function Pop(\$id) {
				  \$a = new {$wut};
				  \$a->Load(\$id);
				  return \$a;
				}
			}");
		}
	}
	

  function Insert() {
    // used when `id` is manually set before first Save()
    $table = STACK::TableInfo($this->table_name);
    foreach ($table['fields'] as $fieldInfo)  {
      $field = $fieldInfo['Field'];
      $val = $this->$field;
      if (isset($this->$field))
        $set .= "`$field` = '".mysql_real_escape_string($this->$field)."', ";
    }
    $set = substr($set,0,-2);
      //print "INSERT INTO `$this->table_name` SET $set;";
      $qr = mysql_query("INSERT INTO `$this->table_name` SET $set;");
      STACK::INC('FOHAT|INSERT');
      return $this->id;
  }


  function Save() {
    $table = STACK::TableInfo($this->table_name);
    foreach ($table['fields'] as $fieldInfo)  {
      $field = $fieldInfo['Field'];
      $val = $this->$field;
      if (is_array($val))
        $val = json_encode($val);
      if ($field!='id'&&isset($val))
        $set .= "`$field` = '".mysql_real_escape_string($val)."', ";
    }
    $set = substr($set,0,-2);
    // FIND TEH BATTLE MAKER!!  =O

    if ($this->table_name=='battle'&&$this->botbr_id=='')
      AddLog('battleERR.log',ReturnBacktrace().STACK::TextDump());

    // END FINDER
    if (isset($this->id)) {
      //print "UPDATE `$this->table_name` SET $set WHERE `id` = '$this->id' LIMIT 1;";
      $qr = mysql_query("UPDATE `$this->table_name` SET $set WHERE `id` = '$this->id' LIMIT 1;");
      if ($qr) {
        STACK::INC('FOHAT|SAVE_UPDATE');
        return $this->id;
      }
    }
    else  {
      //print "INSERT INTO `$this->table_name` SET $set;";
      $qr = mysql_query("INSERT INTO `$this->table_name` SET $set;");
      $this->id = mysql_insert_id();
      if ($this->id)
        STACK::INC('FOHAT|SAVE_NEW');
      else
        STACK::INC('FOHAT|SAVE_NEW_FAIL');
      return $this->id;
    }
    STACK::INC('FOHAT|SAVE_FAIL');
    return FALSE;
  }


  function Load($id)  {
    // check for method by $id type
    if (is_array($id)) {
      foreach ($id as $key => $val)
        $this->$key = $val;
      return $this->id;
    }
    $qr = mysql_query("SELECT `$this->table_name`.* FROM `$this->table_name` WHERE id = '$id' LIMIT 1;");
    STACK::INC('FOHAT|LOAD');
    // assoc the fields to vars
    if (@mysql_num_rows($qr)) {
      $m = mysql_fetch_assoc($qr);
      foreach ($m as $key => $val)
        $this->$key = $val;
      return $this->id;
    }
    return FALSE;
  }


  function Update($var,$val='') {
    if (!isset($this->id)) return FALSE;
    $this->$var = $val;
    if (is_array($val))
      $val = json_encode($val);
    $val = mysql_real_escape_string($val);
    (strtoupper($val)!='NULL') ? $v = "'$val'" : $v = "NULL";
    $qr = mysql_query("UPDATE `$this->table_name` SET `$var` = $v WHERE `id` = '$this->id' LIMIT 1;");
    if ($qr) {
      STACK::INC('FOHAT|UPDATE');
      return $this->id;
    }
    else  {
      STACK::INC('FOHAT|UPDATE_FAIL');
      return FALSE;
    }
  }


  function FetchList($where='') {
    $qr = mysql_query("SELECT `$this->table_name`.* FROM `$this->table_name` $where;");
    STACK::INC('FOHAT|FETCH_LIST');
    $results = array();
    while ($a = @mysql_fetch_assoc($qr))
      eval('$results[] =& '.$this->table_name.'::Pop($a);');
    return $results;
  }


  function Delete($id=0) {
    if ($id<1) $id = $this->id;
    $qr = mysql_query("DELETE FROM `$this->table_name` WHERE id = '$id' LIMIT 1;");
    STACK::INC('FOHAT|DELETE');
    if ($qr) return $this->id;
  }

  /*vibes of confusations; frustrations for celebrations.  change of focus; hocus pocus.  new inventions attract attentions.  never mention the flying locust; it's bogus -- your logic is opinion.  the minions swallow cinnamon.  it's a beginning of of a degrade -- a push in into a new age.  intellect wades and wanes into a cascade.  play the arcade and let your brain jade.--b00daW*/
/* one man bands ftw  lol  */  //tshirt idea
}


class uHAT  {
  ## the "u" stands for uninstantiated!! =D/

  function Count($table,$where='',$distinct='') {
    if ($table=='')
      return 'Table not set for uHAT::Count()';
    STACK::INC('FOHAT|uCOUNT');
    if ($distinct=='')  {
      $query = "SELECT COUNT(*) FROM `$table` $where;";
      $qr = mysql_query($query);
      return ($qr) ? mysql_result($qr,0) : 0;
    }
    else  {
      $query = "SELECT DISTINCT `$distinct`, COUNT(DISTINCT `$distinct`) FROM `$table` $where GROUP BY `$distinct`";
      $qr = mysql_query($query);
      return mysql_num_rows($qr);
    }
    echo $query.CR;
  }

  function Sum($table,$field,$where='') {
    if ($table=='')
      return 'Table not set for uHAT::Sum()';
    $qr = mysql_query("SELECT SUM(`$field`) FROM `$table` $where;");
    STACK::INC('FOHAT|uSUM');
    return ($qr) ? mysql_result($qr,0) : 0;
  }

  function Avg($table,$field,$where='',$group='') {
    ## add groupings!!! =o
    ## $query = "SELECT type, MIN(price) FROM products GROUP BY type";
    if ($table=='')
      return 'Table not set for uHAT::Average()';
    $qr = mysql_query("SELECT AVG(`$field`) FROM `$table` $where;");
    STACK::INC('FOHAT|uAVG');
    return ($qr) ? mysql_result($qr,0) : 0;
  }

  function Min($table,$field,$where='',$group='') {
    ## add groupings!!! =o
    if ($table=='')
      return 'Table not set for uHAT::Min()';
    $qr = mysql_query("SELECT MIN(`$field`) FROM `$table` $where;");
    STACK::INC('FOHAT|uMIN');
    return mysql_result($qr,0);
  }

  function Max($table,$field,$where='',$group='') {
    ## add groupings!!! =o
    if ($table=='')
      return 'Table not set for uHAT::Min()';
    $qr = mysql_query("SELECT MAX(`$field`) FROM `$table` $where;");
    STACK::INC('FOHAT|uMAX');
    return mysql_result($qr,0);
  }

  function FetchArray($table,$where='') {
    $qr = mysql_query("SELECT `$table`.* FROM `$table` $where;");
    STACK::INC('FOHAT|uFETCH_ARRAY');
    $results = array();
    //print "SELECT `$table`.* FROM `$table` $where;";
    while ($a = @mysql_fetch_assoc($qr))
      $results[] = $a;
    return $results;
  }

  function QueryArray($query) {
    // for more complicated queries with JOINS and DISTINCT....
    // does not mysql_real_escape_string();
    $qr = mysql_query($query);
    STACK::INC('FOHAT|uQUERY_ARRAY');
    $results = array();
    while ($a = @mysql_fetch_assoc($qr))
      $results[] = $a;
    return $results;
  }

  function FetchList($table,$where='') {
    // returns object list but not stored in STACK
    $qr = mysql_query("SELECT `$table`.* FROM `$table` $where;");
    STACK::INC('FOHAT|uFETCH_LIST');
    $results = array();
    while ($a = @mysql_fetch_assoc($qr))
      eval('$results[] =& '.$table.'::Pop($a);');
    return $results;
  }

  function Fetch($table,$id) {
    // returns object but not stored in STACK
    eval('$a = '.$table.'::Pop('.$id.');');
    STACK::INC('FOHAT|uFETCH');
    return $a;
  }

  function Find($table,$where='')  {
    if (substr($where,-7)=='LIMIT 1')
      $where = (substr($where,0,-7));
    $qr = mysql_query("SELECT `$table`.* FROM `$table` $where LIMIT 1;");
    STACK::INC('FOHAT|uFIND');
    //print "SELECT `id` FROM `$table` $where LIMIT 1;";
    if (@mysql_num_rows($qr)) {
      $qr = mysql_fetch_assoc($qr);
      eval('$a = new '.$table.';');
      eval('$a = '.$table.'::Pop($qr);');
      return $a;
    }
    else return FALSE;
  }

  function Random($table,$where='',$limit=1)  {
    $qr = mysql_query("SELECT `$table`.* FROM `$table` $where ORDER BY RAND() LIMIT $limit;");
    STACK::INC('FOHAT|uRANDOM');
    if (@mysql_num_rows($qr)) {
      eval('$a = '.$table.'::Pop(mysql_result($qr,0));');
      return $a;
    }
    else return FALSE;
  }


  function Delete($table,$id) {
    $qr = mysql_query("DELETE FROM `$table` WHERE id = '$id' LIMIT 1;");
    STACK::INC('FOHAT|uDELETE');
    if ($qr) return $id;
  }

  function MultiDelete($table,$where) {
    // $where must be set to protect from retard codez  o__O
    @mysql_query("DELETE FROM `$table` $where;");
    STACK::INC('FOHAT|uMULTI_DELETE');
    return mysql_affected_rows();
  }

  function FetchTableInfo($table) {
    ##  FUNKTIONS FOR STACK MEMORIES GUY
    ##  Let's keep as mushe SQL in one place as possible.
    //return mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table';"));
    if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table';")))
      return false;
    STACK::INC('FOHAT|uFETCH_TABLE_INFOs');
    $a = array();
    $a['table_name'] = $table;
    $qr = mysql_query("SELECT COUNT(*) FROM `$table`;");
    $a['count'] = @mysql_result($qr,0,0);
    $qr = mysql_query("SHOW COLUMNS FROM `$table`;");
    $c = @mysql_num_rows($qr);
    for ($i=0; $i<$c; $i++) {
      $d = mysql_fetch_assoc($qr);
      $a['fields'][] = $d;
    }
    return $a;
  }

  function FetchTableFields($table)  {
    STACK::INC('FOHAT|uFETCH_TABLE_FIELDs');
    $a = array('table_name'=>$table);
    $qr = mysql_query("SHOW COLUMNS FROM `$table`;");
    $c = @mysql_num_rows($qr);
    if (!$c) return false;
    for ($i=0; $i<$c; $i++) {
      $a['fields'][] = mysql_fetch_assoc($qr);
    }
    return $a;
  }

  function ExportRecordsCSV($table,$where='')  {
    $qr = mysql_query("SELECT * FROM `$table` $where;");
    while ($a = @mysql_fetch_assoc($qr))
      $output .= implode(',',$a).CR;
    return $output;
  }

}

  /*Fohat is that Occult, electric, vital power, which, under the Will of the Creative Logos, unites and brings together all forms, giving them the first impulse which becomes in time law. . . . Fohat becomes the propelling force, the active Power which causes the One to become Two and Three . . . then Fohat is transformed into that force which brings together the elemental atoms and makes them aggregate and combine.

      - Blavatsky


This can be understood.  Every form of planetary systems, of Cosmic Electricity, and becomes a concrete form.  This can be reproduced as a baby which the dynamic energy of Dzyan it wakes up of perfectibility" of the plane of inert Substance, Fohat is objectivized as the universal propelling force.  What brings about calm and grosser and quiet, and guides its primary differentiations on page 649 of the energic force. The Secret Doctrine. It is usually considered in the ethical and made up the body is interesting to the plane of a concrete form.

This indicates how it serve, like the body is the inward impulse in Fohat, the spiritual to the student to activity, and molecules together, what Fohat is taking place. What brings atoms and attains to create a simple illustration—that of Fohat. This can only does it is thus the constructive power that some thinkers have, unconsciously to another, but it wakes up of Fohat—the great Transformer. Thus, an earthen pot. He may have an ideal image formed in Fohat, the Fifth Stanza from its two opposite aspects? Heat and the body is needed. When the Infinite. This illustration should also be reproduced as the Infinite. This something, at various stages of the mind passive, hands moulds and his own mind. He may have a concrete form.  This illustration should also has the "laws of that which translates or Cosmic Ideation, comes our consciousness; while Fohat, rich with his hands and matter, subject to re-create once more.

This something, at various manifestations, is rather difficult to another, but the Dhyan Chohans,* the former -- from within without. The Secret Doctrine:
Fohat becomes the several vehicles in tune with the individual for another aspect of the second verse of Cosmic Energy (Fohat). Thrilling through the "Thought Divine" transmitted and Matter, the Supreme Spirit. It is the first impulse commences with the "Thought Divine" transmitted and attains to change and expansion, all forms, giving them the waking to and expansion, all the semi-ethereal, until gross forms at various manifestations, is Fohat is the play his part, to self -- consciousness; while Fohat, in the bridge between Mind and colourless. It is individualised and makes them aggregate and guides its undifferentiated pralayic state. Then, absolute wisdom mirrors itself in the Fifth Stanza from one place to understand; therefore, side with the manvantaric vehicle of Divine Substance. It is made up of Nature exist because of matter expressing a gradual transformation takes place to Western speculation, is looked upon as a germ into existence. Primordial matter expressing a foetus in a baby which the universal, archetypal ideas into the Supreme Spirit. It is transformed into various worlds of Cosmic Substance the connecting link between the "Thought Divine" transmitted and Matter, the play his part, to dance or another day of dispersion both spring from its undifferentiated pralayic state. Then, absolute wisdom mirrors itself upon as explained in the two is called by side by the steed, as a germ into Fohat.

    - Markov/Blavatsky
  */

class SQLHAT  {
  ## For use in SysOp WHAT only!!  =0

  function Query($query)  {
    ## mysql_query only handles single queries
    $q = explode(';',$query);
    foreach ($q as $query)
      mysql_query($query);
      STACK::INC('FOHAT|sQUERY');
    return TRUE;
  }

  function TableExists($table)  {
    $a = mysql_query("ANALYZE TABLE `$table`;");
    STACK::INC('FOHAT|sTABLE_EXISTS');
    $a = mysql_fetch_assoc($a);
    if ($a['Msg_type']=='status')
      return TRUE;
    if ($a['Msg_type']=='error')
      return FALSE;
  }
}
