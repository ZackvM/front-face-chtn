<?php

class mastercontroldevices { 

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

    function mastertopmenu ( $whichpage , $rqststr )  { 
      $at = genAppFiles; 
      $tt = treeTop;
      $at = genAppFiles;
      $chtnlogo = base64file("{$at}/publicobj/graphics/chtn_trans.png", "chtntoplogo", "img", true);
      //$r = json_encode( $rqststr );
      //UNDERLINE $WHICHPAGE

      $rtnthis = <<<TOPMENU
<div id=universalTopBarHolder>
  <div id=menuItems>
        <a href="{$tt}" class="logoholder">{$chtnlogo}</a>
        <a href="{$tt}/biospecimen-services" class="menuLink">Services</a>     
        <a href="{$tt}/process-fee-payment" class="menuLink">Pay Invoice</a>
        <a href="{$tt}/transient-inventory-search" class="menuLink">Search</a>     
        <a href="https://scienceserver.chtneast.org" class="menuLink">ScienceServer</a>     
        <a href="{$tt}/contact-us" class="menuLink">Contact</a>     
  </div>
  <div id=menuSidePanel align=right>
        <a href="https://twitter.com/chtn_network" class="menuLinkSide" target="_new"><i class="fa fa-twitter"></i></a>     
  </div>
</div>
TOPMENU;
  
      return $rtnthis;
    }

}

class defaultpageelements {

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

function modalbackbuilder ( $whichpage ) { 
$rtnthis = <<<RTNTHIS
<div id=universalbacker></div>
RTNTHIS;
return $rtnthis;
} 

function faviconBldr($whichpage) { 
  $at = genAppFiles;
  $favi = base64file("{$at}/publicobj/graphics/icons/chtnblue.ico", "favicon", "favicon", true);
  return $favi;
}

function pagetabs($whichpage, $rqststr) { 
  $dp = dataPath;  
  switch($whichpage) { 
    case 'root':
      $thisTab = "Cooperative Human Tissue Network : Eastern Division (Home Page)";
      break;
//    case 'blog':
//        if ( trim( $rqststr[2] ) === "" ) {
//          $thisTab = "Blog Listing [Zack von Menchhofen - The Choirmaster]";
//        } else {
//          $titleDta = json_decode( callrestapi_anon ( "GET" , $dp . "/blog-title/{$rqststr[2]}"), true); 
//          if ( (int)$titleDta['RESPONSECODE'] === 200 ) { 
//            $thisTab = "{$titleDta['DATA']['blogtitle']} [Zack von Menchhofen - The Choirmaster]";
//          } else {
//            $thisTab = "Blog Entry [Zack von Menchhofen - The Choirmaster]";
//          } 
//        }
//    break;    
    default:        
      $thisTab = "Cooperative Human Tissue Network : Eastern Division";        
    break; 
  }
  return $thisTab;
}

}



