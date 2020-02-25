<?php 

/* FRONT-FACING  CHTN MAIN INDEX */

/*
 * REQUIRED SERVER DIRECTORY STRUCTURE
 * /srv/chtneastapp/devss = applicationTree - Can be changed if application files are moved
 *    +----accessfiles (Public/private key hold)
 *    +----appsupport (files/functions that do things to support all application frames) 
 *    +----frame (build/application files)
 *    +----dataconn (directory for data connection strings - Only to be used by PHP files under the applicationTree)
 *    +----tmp (application generated temporary files)
 *    +----publicobj (physical objects to pull 
 * 
 */

//START SESSSION FOR ALL TRACKING 
session_start(); 
//DEFINE APPLICATION PATH PARAMETERS
define("uriPath","facedev.chtneast.org");
define("TagVersion","v2.0.0");
define("ownerTree","https://www.chtneast.org");
define("treeTop","https://facedev.chtneast.org");
define("dataTree","https://facedev.chtneast.org/data-services");
define("applicationTree","/srv/chtneastapp/frontfacedev/frame");
define("genAppFiles","/srv/chtneastapp/frontfacedev");
define("serverkeys","/srv/chtneastapp/frontfacedev/dataconn");

//MODULUS HAS BEEN CHANGED TO DEV.CHTNEAST.ORG
define("eModulus","C7D2CD63A61A810F7A220477B584415CABCF740E4FA567D0B606488D3D5C30BAE359CA3EAA45348A4DC28E8CA6E5BCEC3C37A429AB3145D70100EE3BB494B60DA522CA4762FC2519EEF6FFEE30484FB0EC537C3A88A8B2E8571AA2FC35ABBB701BA82B3CD0B2942010DECF20083A420395EF4D40E964FA447C9D5BED0E91FC35F12748BB0715572B74C01C791675AF024E961548CE4AA7F7D15610D4468C9AC961E7D6D88A6B0A61D2AD183A9DFE2E542A50C1C5E593B40EC62F8C16970017C68D2044004F608E101CD30B69310A5EE550681AB411802806409D04F2BBB3C49B1483C9B9E977FCEBA6F4C8A3CB5F53AE734FC293871DCE95F40AD7B9774F4DD3");
define("eExponent","10001");

//Include functions file
require(genAppFiles . "/appsupport/generalfunctions.php");
require(serverkeys . "/serverid.zck");

define("serverIdent",$serverid);
define("servertrupw", $serverpw);
define("serverpw", cryptservice($serverpw) );

//DEFINE THE REQUEST PARAMETERS
$requesterIP = $_SERVER['REMOTE_ADDR']; 
$method = $_SERVER['REQUEST_METHOD'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$host = $_SERVER['HTTP_HOST'];
$https = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$originalRequest = str_replace("-","", strtolower($_SERVER['REQUEST_URI']));
$request = explode("/",str_replace("-","", strtolower($_SERVER['REQUEST_URI']))); 

switch ($request[1]) {
  case 'dataservices': 
  //BOTH GET AND POSTS GO HERE
  $responseCode = 400;
  $data = "";
  switch ($method) { 
    case 'POST':
      $authuser = $_SERVER['PHP_AUTH_USER']; 
      $authpw = $_SERVER['PHP_AUTH_PW']; 
      if ((int)checkPostingUser($authuser, $authpw) === 200) { 
        //CONTINUE WITH POST REQUEST
        require(genAppFiles . "/dataservices/ws-post.php"); 
        $postedData = file_get_contents('php://input');
        $passedPayLoad = "";
         if (trim($postedData) !== "") { 
           $passedPayLoad = trim($postedData);
         } 
         $doer = new dataposters($originalRequest, $passedPayLoad);
         $responseCode = $doer->responseCode; 
         $data = $doer->rtnData;
      } else { 
        $responseCode = 401;
        $data = "USER NOT FOUND";
      } 
      break;
      case 'GET':
        //make array of allowed services  
        $responseCode = 400;
        $data = "";
         require(genAppFiles . "/dataservices/ws-get.php");  
         $obj = new objgetter($originalRequest);
         $responseCode = $obj->responseCode;
         $data = $obj->rtnData;  
         break;
       default: 
         echo "ONLY GET/POST are allowed under this end point!"; 
         $responseCode = 401;
   }
   header('Content-type: application/json; charset=utf8');
   header('Access-Control-Allow-Origin: *'); 
   header('Access-Control-Allow-Header: Origin, X-Requested-With, Content-Type, Accept');
   header('Access-Control-Max-Age: 3628800'); 
   header('Access-Control-Allow-Methods: GET, POST');              
   http_response_code($responseCode);
   echo $data;
   break;
   case 'printobj': 
      //PRINT OBJECT - GET ONLY  
      //frame/objprinter.php
    break;
    default: 
    //make array of allowed pages    
    if ($method === "GET") {
        session_start();        
        //CHECK USER
        require(applicationTree . "/bldfrontpage.php"); 
    
        $rt = "THIS IS WHERE THE PAGE GOES";
        echo $rt;
        
    }    
}
