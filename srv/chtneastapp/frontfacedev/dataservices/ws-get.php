<?php

class objgetter { 

  public $responseCode = 503;
  public $rtnMessage = "";
  public $rtnData = "";

  function __construct() { 
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 
    if (trim($args[0]) === "") { 
    } else { 
      $request = explode("/", $args[0]); 
      if (trim($request[2]) === "") { 
        $this->responseCode = 400; 
        $this->rtnMessage = "DATA NAME MISSING";
        $this->rtnData = "";
      } else { 

        $dg = new endfunctions(); 
        if (method_exists($dg, $request[2])) { 
          $funcName = trim($request[2]); 
          $dataReturned = $dg->$funcName($args[0]);
          $this->responseCode = $dataReturned['statusCode']; 
          $this->rtnMessage = "IN FUNCTION";
          $this->rtnData = json_encode($dataReturned['data']);
        } else { 
          $this->responseCode = 404; 
          $this->rtnMessage = "END-POINT FUNCTION NOT FOUND " . $request[1];
          $this->rtnData = "";
        }

      }
    }
  }

} 

class endfunctions { 

/* BASIC RETURN DEFINITION 
 *
 *   $rows = array(); 
 *   $rows['statusCode'] = 200;
 *   $rows['data'] = canBeArrayOrJSONString
 *
 */
    
    public $responseCode = 400;
    public $msg = "BAD REQUEST";
    public $countUser = 0;
    public $rtnData = array();

    function chtneastfeeschedule ( $request ) { 
        require(genAppFiles . "/dataconn/sspdo.zck"); 
        $sql = "SELECT pagesource, menuvalue, dspvalue, ifnull(additionalinformation,'') as addinfo, ifnull(academvalue,0) as academicvalue, ifnull(commercialvalue,0) as commercialvalue, ifnull(outsideinvestvalue,0) as outsideinvestigatorvalue FROM four.sys_master_menus where menu = 'PROCFEE' and dspind = 1 order by dsporder";
        $rs = $conn->prepare($sql);
        $rs->execute();
        $this->countUser = $rs->rowCount();
            if ($this->countUser > 0) { 
              while ($r = $rs->fetch(PDO::FETCH_ASSOC)) { 
                 $this->rtnData[] = $r;
              }
              $this->responseCode = 200; 
              $this->msg = $rqst[2];
            } else { 
              $this->responseCode = 404; 
              $this->msg = "NO RECORDS FOUND MATCHING YOUR QUERY PARAMETERS";                
            }
       $rows['statusCode'] = $this->responseCode; 
       $rows['data'] = array("MESSAGE" => $this->msg, "ITEMSFOUND" => $this->countUser, "DATA" => $this->rtnData);
       return $rows;                        
    }

    function chtneastpublicationlisting($request) {    
       $rqst = explode("/", $request);
       if (trim($rqst[3]) !== "" ) {      
        require(genAppFiles . "/dataconn/sspdo.zck"); 
        $sql = "SELECT ifnull(authors,'') as authors, ifnull(title,'') as title, ifnull(journal,'') as journal, ifnull(bibliographyTag,'') as bibliographytag, ifnull(urldsp,'') as urldsp FROM four.sys_publicationlisting where pubtype = :pubtype order by dspOrderInType";
        $rs = $conn->prepare($sql);
        $rs->execute(array(':pubtype' => trim($rqst[3])));
        $this->countUser = $rs->rowCount();
            if ($this->countUser > 0) { 
              while ($r = $rs->fetch(PDO::FETCH_ASSOC)) { 
            $this->rtnData[] = $r;
              }
              $this->responseCode = 200; 
              $this->msg = $rqst[2];
            } else { 
              $this->responseCode = 404; 
              $this->msg = "NO RECORDS FOUND MATCHING YOUR QUERY PARAMETERS";                
            }
       } else {
        $this->responseCode = 503; 
        $this->msg = "REQUEST MALFORMED";
       }      
       $rows['statusCode'] = $this->responseCode; 
       $rows['data'] = array("MESSAGE" => $this->msg, "ITEMSFOUND" => $this->countUser, "DATA" => $this->rtnData);
       return $rows;                        
    }


}
