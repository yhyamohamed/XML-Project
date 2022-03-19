<?php
session_start();
$xmldoc = new DOMDocument();
$xmldoc->load('smple.xml', LIBXML_NOBLANKS);
$rootElement = $xmldoc->getElementsByTagName("person")[0];
$tagsCount = $rootElement->childElementCount;
if (isset($_POST['op']) && $_POST['op'] == 'insert') {
    if ($_POST['data']) {
       //create top level tag 
      $P = $xmldoc->createElement("p".$tagsCount+1,'');
 
       $name = $xmldoc->createElement('name',$_POST['data']['name']);
       $P->appendChild($name);
       $phone = $xmldoc->createElement('phone',$_POST['data']['phone']);
       $P->appendChild($phone );
       $address = $xmldoc->createElement('address',$_POST['data']['address']);
       $P->appendChild($address);
      $email = $xmldoc->createElement('email',$_POST['data']['email']);
      $P->appendChild($email);
      $rootElement->appendChild($P);
      $xmldoc->formatOutput = true;
      $data =  $xmldoc->save('smple.xml');
      $_SESSION['node-index']=$tagsCount+1;
      $_SESSION['op'] = 'iserted';
    } else {
      $data = "no valu added";
      $_SESSION['op'] = 'no valu added';
    }
   
  
}
header('Content-Type: application/json');

exit(json_encode($data));
