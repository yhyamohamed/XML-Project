<?php
session_start();
$xmldoc = new DOMDocument();
$xmldoc->load('smple.xml', LIBXML_NOBLANKS);
$rootElement = $xmldoc->getElementsByTagName("person")[0];
$tagsCount = $rootElement->childElementCount;

if (isset($_POST['op']) && $_POST['op'] == 'next') {
    if ($_POST['ele']) {
        $ele = $xmldoc->getElementsByTagName($_POST['ele'])[0];
        $_SESSION['node-index']++;
        
    } else {
        $ele = $xmldoc->getElementsByTagName('p1')[0];
        $_SESSION['node-index']=1;
    }
   
    if($_SESSION['node-index']>$tagsCount)$_SESSION['node-index']=1; 
    $data =  $ele->nodeName." ".$_SESSION['node-index'];

}else if (isset($_POST['op']) && $_POST['op'] == 'prev') {
    if ($_POST['ele']) {
        $ele = $xmldoc->getElementsByTagName($_POST['ele'])[0];
        $_SESSION['node-index']--;
        
    } else {
        $ele = $xmldoc->getElementsByTagName("p".$tagsCount)[0];
       
        $_SESSION['node-index']=$tagsCount;
    }
   
    if($_SESSION['node-index']<=0 )$_SESSION['node-index']=$tagsCount; 
    $data =  $ele->nodeName."lll ".$_SESSION['node-index'];
}
header('Content-Type: application/json');

exit(json_encode($data));
