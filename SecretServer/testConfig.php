<?php

$ini_array = parse_ini_file("config.ini");

if(isset($ini_array["usedReturnType"]))
{
    switch($ini_array["usedReturnType"])
    {
        case 1:
        {
            //echo "JSON";
            $returnType = 1;
            break;
        }
        case 2:
        {
            //echo "XML";
            $returnType = 2;
            break;
        }
        default:
        {
            //echo "Plain ".$ini_array["usedReturnType"] ;
            $returnType = 0;
            break;
        }
    }
}
else
{
    //echo "Return type is not set, but we assume plain text needed here.";
    $returnType = 0;
}

?>