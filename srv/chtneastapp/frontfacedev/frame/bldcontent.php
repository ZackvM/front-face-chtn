<?php

class bldcontent {

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

    function biospecimenservices ( $rqst ) {
      $yr = date('Y');
      $tt = treeTop;
      $dt = dataTree;
      $faget = json_decode(callrestapi("GET","{$dt}/chtneast-fee-schedule"),true);
      $farr = $faget['DATA'];
      $f = json_encode( $farr );
      $effDate = "";
      $header = ""; 
      foreach ($farr as $ky => $vl) { 
        $effDate = $vl['pagesource'];
        if ($header !== $vl['menuvalue']) { 
          $dspFeeSched .= "<div class=serviceHead>{$vl['menuvalue']}</div>";
          $dspFeeSched .= "<div class=serviceColHeader>&nbsp;</div><div class=serviceColHeader>Academic</div><div class=serviceColHeader>Commercial</div><div class=serviceColHeader>Outside U.S.*</div>";
          $header = $vl['menuvalue'];
        }
        $dspFeeSched .= "<div class=serviceDesc>{$vl['dspvalue']} {$vl['addinfo']}</div><div class=amtDsp><div class=sideColHead>Aca</div><div>&dollar;{$vl['academicvalue']}</div></div><div class=amtDsp><div class=sideColHead>Com</div><div>&dollar;{$vl['commercialvalue']}</div></div><div class=amtDsp><div class=sideColHead>Out</div><div>&dollar;{$vl['outsideinvestigatorvalue']}</div></div>";
      }
      $dspFeeSched .= "<div id=procFeeFoot>* Investigators outside of North America may be asked to prepay before samples are shipped. </div>";
   
      $procTbl = <<<PROCTBL
    <div class=title>Fee Schedule for Processing Services (Effective {$effDate})</div>
    <div class=explainer>The CHTN charges a processing fee for each sample to offset the costs of collecting, handling and preparing the specimens in accordance with the detailed requirements of the investigator. A sample is defined as one processed piece of a specimen, regardless of the sample size or type of processing. Investigators are responsible for all shipping costs. This charge will vary according to the weight and priority status of the shipment.
<p>
Several of the CHTN divisions offer additional specialized services. For a complete listing, CHTN Eastern investigators should contact the CHTN Eastern for more information on these services. Investigators may request chart reviews for a minimal fee. However, the following services can also be provided to investigators upon request. </div>

<div id=feeHolder>{$dspFeeSched}</div>
<div class=title>Specialized Services</div>
<div class=explainer>Primary divisions should be contacted if an investigator requests any specialized service. Specialized services can be provided for a minimum fee of $25 or $25/hour.</div>
<div class=title>Specialized services</div><div class=explainer>Including but are not limited to the following:<p><ul><li>Resending archived pathology reports/chart reviews previously sent to the investigator</li><li>Requirement to complete additional paperwork for procurement</li><li>Specialized packaging (other than standard CHTN protocol)</li><li>Specialized procurement (other than standard CHTN protocol)</li></ul></div>
      
PROCTBL;

      $rtnthis = <<<RTNTHIS
<div id=mainPageTitle>Biospecimen Service Fees</div>
{$procTbl}
<div id=copyrightdsp>&copy; 2009-{$yr} CHTNEastern/Trustees of the University of Pennsylvania </div>
RTNTHIS;
      return $rtnthis;
    }

    function posterspapersabstracts ( $rqst ) {
      $tt = treeTop;
      $thisyear = date('Y');
      $at = genAppFiles;
      $upenn = base64file("{$at}/publicobj/graphics/psom_logo_white.png","SOMALogo","png",true);
      $nci = base64file("{$at}/publicobj/graphics/nci-logo-full.png","NCILogo","png",true);
      $dt = dataTree; 

      $publocs = json_decode(callrestapi("GET","{$dt}/chtneast-publication-listing/publications"), true);
      $publications .= "<div class=publications><div class=pubHeader>Publications</div>";
      foreach ($publocs['DATA'] as $pkey => $pval) { 
        $urldsp = "";
        if (trim($pval['urldsp']) !== "") { 
          $urldsp = "<a href=\"{$pval['urldsp']}\" target=\"_new\"><i class=\"material-icons\">open_in_new</i></a>";
        } 
        $publications .= "<div class=pubReference><span class=authors>{$pval['authors']}</span>: <span class=pubTitle>{$pval['title']}</span>. <span class=journalName>{$pval['journal']}</span>. {$pval['bibliographytag']}. {$urldsp}</div>";
      }
      $publications .= "</div>";

      $pubas = json_decode(callrestapi("GET","{$dt}/chtneast-publication-listing/publishedabstracts"), true);
      //$pubalist = json_decode($pubas['datareturn'],true);
      $publications .= "<div class=publications><div class=pubHeader>Published Abstracts</div>";
      foreach ($pubas['DATA'] as $pkey => $pval) {
        $urldsp = "";
        if (trim($pval['urldsp']) !== "") { 
           $urldsp = "<a href=\"{$pval['urldsp']}\" target=\"_new\"><i class=\"material-icons\" >open_in_new</i></a>";
        } 
        $publications .= "<div class=pubReference><span class=authors>{$pval['authors']}</span>: <span class=pubTitle>{$pval['title']}</span>. <span class=journalName>{$pval['journal']}</span>. {$pval['bibliographytag']}. {$urldsp}</div>";
      }
      $publications .= "</div>";
 
      $pubpos = json_decode(callrestapi("GET","{$dt}/chtneast-publication-listing/abstracts"),true);
      //$pubposlist = json_decode($pubpos['datareturn'],true);
      $publications .= "<div class=publications><div class=pubHeader>Abstracts</div>";
      foreach ($pubpos['DATA'] as $pkey => $pval) {
        $urldsp = "";
        if (trim($pval['urldsp']) !== "") {
          $urldsp = "< href=\"{$pval['urldsp']}\" target=\"_new\"<i class=\"material-icons\">open_in_new</i></a>";
        }
        $publications .= "<div class=pubReference><span class=authors>{$pval['authors']}</span>: <span class=pubTitle>{$pval['title']}</span>. <span class=journalName>{$pval['journal']}</span>. {$pval['bibliographytag']}. {$urldsp}</div>";
      }
      $publications .= "</div>";


      $rtnthis = <<<PGERTN
<div id=publicationdivholder>
<div id=titleHead>CHTN Eastern's Publications &amp; Abstracts</div>
{$publications}
  <div id=copyrightdsp> &#9400; Copyright Code and Content - CHTN Eastern Division/Perelman School of Medicine, University of Pennsylvania 2007-{$thisyear} </div>
</div>

<div id=pgeFooter>
  <div id=allMasterLinks>
   <a href="{$tt}">CHTNEastern</a>
   <a href="https://scienceserver.chtneast.org" target="_new">CHTNEastern ScienceServer</a>
   <a href="https://transient.chtneast.org" target="_new">CHTNEastern Transient Inventory Search</a>
   <a href="{$tt}">CHTNEastern Services</a>
   <a href="{$tt}">Pay Processing Fee Invoice</a>
   <a href="{$tt}">Contact CHTNEastern</a>
   <a href="{$tt}/posters-papers-abstracts">Papers, Publications &amp; Talks</a>
   <a href="{$tt}">Meet the Staff</a>
   <a href="{$tt}">CHTNMid-Atlantic</a>
   <a href="{$tt}">CHTNMid-Western</a>
   <a href="{$tt}">CHTNPediatric</a>
   <a href="{$tt}">CHTNSouthern</a>
   <a href="{$tt}">CHTNWestern</a>
   <a href="{$tt}">CHTNNetwork</a>
   <a href="{$tt}">CHTNTwitter</a>
   <a href="https://www.chtn.org/d/chtn-application.pdf" target="_new">Download CHTN Application</a>
   <a href="{$tt}">National Cancer Institute (NCI)</a>
   <a href="{$tt}">Perelman School of Medicine / University of Pennsylvania</a>
   <a href="{$tt}">Pathology Feasibility Review Panel (PFRP) / University of Pennsylvania</a>

  </div>
  <div id=allMasterLogos align=right>
   <div>{$upenn}</div>
   <div>{$nci}</div>
  </div>
</div>

PGERTN;
      return $rtnthis;
    }

    function root ( $rqst ) { 
      $tt = treeTop;
      $thisyear = date('Y');
      $at = genAppFiles; 
      $upenn = base64file("{$at}/publicobj/graphics/psom_logo_white.png","SOMALogo","png",true);
      $nci = base64file("{$at}/publicobj/graphics/nci-logo-full.png","NCILogo","png",true);

      $rtnthis = <<<PGERTN
  
<div id=chtnIntroHold>
   <div id=chtnIntroText>
     <div class=headerLine>Human Biospecimens For Research</div>
     <div class=headerSubLine>Serving the Research Community with Quality Biospecimens for more than 30 years!</div>
     <div class=readMoreBtnHold><a href="{$tt}#about">Read More</a></div>
   </div> 
</div>

<div id=moreLinks>
 
<a href="https://www.chtn.org" target="_blank">
   <div class="moreLinkLink borderright">
     <div class=moreLinkIconHolder><i class="material-icons">link</i></div>
     <div class=moreLinkTitle>CHTN Network</div>
     <div class=moreLinkText>We have operations at six academic centers throughout the US.  Find out more here.</div>   
   </div>
</a>

<a href="{$tt}/posters-papers-abstracts">   
  <div  class="moreLinkLink borderright">
     <div class=moreLinkIconHolder><i class="material-icons">menu_book</i></div>
     <div class=moreLinkTitle>CHTN Eastern Publications</div>
     <div class=moreLinkText>We often share our work in biorepository science at conferences, lectures, posters and papers.  Find our list here.</div>   
  </div>
</a>
                                             

<a href="{$tt}/collaborations">   
  <div  class="moreLinkLink">
     <div class=moreLinkIconHolder><i class="material-icons">extension</i></div>
     <div class=moreLinkTitle>Collaborations</div>
     <div class=moreLinkText>Here are some links to our funding partners and team collaborations.</div>   
  </div>
</a>


</div>

<a id="about">
<div id=aboutWriteup>
<div class=divlines></div>
<div id=abtTitle>About CHTN Eastern Division</div>
<div class=divlines></div>
<div class=headerText>The Eastern Division of the Cooperative Human Tissue Network (CHTN), which has been in existence since 1987, continues its work of providing well-characterized biospecimens to investigators working in the fields of neoplasia, autoimmune disease, degenerative diseases and aging. Research projects include utilization of techniques of molecular biology, immunobiology, proteomics, genomics, and investigations of mutations, nucleic acid chemistry, and microRNA in disease. We are adapting our services to provide more specialized but still high-quality specimens to fill researcher needs by expanding our research base in the region.
   <p><b>Support at CHTN Eastern</b><br>Having trouble with our page? Check out our <a href="https://scienceserver.chtneast.org" target="_new">Investigator Gateway</a> or <a href="{$tt}">contact support</a> and we'll help you sort it out. Need a <a href="https://www.chtn.org/d/chtn-application.pdf" target="_new">CHTN application</a> or another <a href="{$tt}/contact-us">CHTN Division</a>? You can find all that information here as well.
     </div> 
<div class=twoColumn>
The Cooperative Human Tissue Network is an organization sponsored by the National Cancer Institute (NCI) that proactively procures bio-specimens to support the research community.
<p>
The Cooperative Human Tissue Network (CHTN) was initiated by the Cancer Diagnosis Program of the National Cancer Institute (NCI) in 1987 to provide increased access to human cancer tissue for basic and applied scientist from academia and industry to accelerate the advancement of discoveries in cancer diagnosis and treatment.
<p>
The CHTN provides prospective investigator-defined procurement of malignant, benign, diseased and uninvolved (normal adjacent) tissues. The investigator can also choose from several methods to fix the specimen such as fresh, frozen, or chemically fixed. The CHTN also produces tissue microarrays (TMA) representing multiple tissue types to disease-specific blocks. Recently, the CHTN has approved the development within the divisions to isolate and distribute the raw nucleic acid to expand resources and to more readily serve investigator's interest. Tissues are annotated with patient demographics including gender, age, and race. Additional patient information can be requested where applicable.
<p>
Quality control assessments of tissues are provided by the CHTN principal investigators (PIs) who are actively involved in the practice of anatomic pathology. The CHTN PIs are responsible for proper histopathological characterization, participate in research and understand the importance of quality control in the tissues provided.
<p>
Specimens are shipped to investigators using IATA guidelines and under conditions supporting the fixation of the tissue. A tissue processing fee and the cost of shipping are paid by the investigator.
<p>
Since its establishment, the CHTN has provided more than 700,000 high quality specimens from a wide variety of organ sites to over a thousand investigators. Human tissues provided to investigators by the institutions involved in the CHTN have been utilized in a wide variety of research projects. To date more than 2500 peer-reviewed articles have been published many of these in high impact journals with significant contribution to cancer research. Publications have ranged from reporting mutations of protooncogenes in human tumors using mRNA to a wide range of other studies in the following areas: growth factors, isoenzymes, development of monoclonal antibodies and cell lines, studies of subcellular organelles, gene isolation/gene deletion, flow cytometry, and DNA hybridization.
<p>
Requirements for collection, storage and distribution vary depending on the type of research and type of tissue. Some studies of mRNA and labile proteins should be conducted on tissues from surgical resection which are fresh or are snap-frozen and stored at ultra-low temperatures. Other studies of more stable biological molecules can easily be investigated using tissues obtained from autopsy; these tissues can also be used in a wide range of studies including the establishment of viable tissue cultures and cell lines. The CHTN encourages all investigators to consider the necessary tissue requirements for their individual research projects. Such information provides investigators with the broadest possible number and range of research specimens. </div>
<p>
</div> 
</div>
</a>

<div id=copyrightdsp> &#9400; Copyright Code and Content - CHTN Eastern Division/Perelman School of Medicine, University of Pennsylvania 2007-{$thisyear} </div>

<div id=pgeFooter>
  <div id=allMasterLinks>
   <a href="{$tt}" target="_blank">CHTNEastern</a>
   <a href="https://scienceserver.chtneast.org" target="_blank">CHTNEastern ScienceServer</a>
   <a href="https://transient.chtneast.org" target="_blank">CHTNEastern Transient Inventory Search</a>
   <a href="{$tt}/biospecimen-services">CHTNEastern Services</a>
   <a href="{$tt}">Pay Processing Fee Invoice</a>
   <a href="{$tt}">Contact CHTNEastern</a>
   <a href="{$tt}/posters-papers-abstracts">Papers, Publications &amp; Talks</a>
   <a href="{$tt}">Meet the Staff</a>
   <a href="{$tt}">CHTNMid-Atlantic</a>
   <a href="{$tt}">CHTNMid-Western</a>
   <a href="{$tt}">CHTNPediatric</a>
   <a href="{$tt}">CHTNSouthern</a>
   <a href="{$tt}">CHTNWestern</a>
   <a href="{$tt}">CHTNNetwork</a>
   <a href="{$tt}">CHTNTwitter</a>
   <a href="https://www.chtn.org/d/chtn-application.pdf" target="_new">Download CHTN Application</a>
   <a href="{$tt}">National Cancer Institute (NCI)</a>
   <a href="{$tt}">Perelman School of Medicine / University of Pennsylvania</a>
   <a href="{$tt}">Pathology Feasibility Review Panel (PFRP) / University of Pennsylvania</a>

  </div>
  <div id=allMasterLogos align=right>
   <div>{$upenn}</div>
   <div>{$nci}</div>
  </div>
</div>

PGERTN;
      return $rtnthis;
    }    
    
    
}

