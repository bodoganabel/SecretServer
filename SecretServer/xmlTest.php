<?php

//This file converts the array "$finalResult"to XML. $finalResult was made previously. 
//solution from: https://www.codexworld.com/convert-array-to-xml-in-php/
//Modified by Ábel Bodogán.



//function defination to convert array to xml
function array_to_xml($array, &$xml_user_info) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

//creating object of SimpleXMLElement
$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><secret></secret>");

//function call to convert array to xml
array_to_xml($finalResult,$xml_user_info);

//saving generated xml file
$xml_file = $xml_user_info->asXML('secret.xml');

//success and error message based on xml creation

if($xml_file){
    header('Content-type: text/xml');
    echo file_get_contents('secret.xml');
    //overwrite temporary XML file preventing unwanted access to it.
    $myfile = fopen("secret.xml", "w") or die("Unable to open file!");
    fclose($myfile);
    die();

}else{
    echo 'XML file generation error.';
}

?>