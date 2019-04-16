<?php

$servername = "localhost";
$username = "averageUser";
$password = "DD7IW7bqrM3O368s";
$dbname = "secretserver";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8"); //set UTF8 encoding

if(isset($_GET["Wanted"])) //If user gave the secret's url
{
    $dataFromServer = "";


    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    else

    //Connection was successful
    {

        //$sql = "SELECT `data` FROM `secrets` WHERE `generatedKey` = 'sgsdfg3224324AAFFcsqq33'";
        /*
        SELECT `data` FROM `secrets` WHERE `generatedKey` = 'sgsdfg3224324AAFFcsqq33' 
        AND DATE('expire') >= CURRENT_DATE()
        */
        //SELECT `data` FROM `secrets` WHERE `expire` >= CURRENT_DATE()

        //SELECT `data` FROM `secrets` WHERE `expire` >= CURRENT_DATE() AND `generatedKey` = 'sgsdfg3224324AAFFcsqq33'

        $sql = "SELECT `data`, `TTL`, `expire` FROM `secrets` WHERE `expire` >= CURRENT_DATE() AND `generatedKey` = '".$_GET["Wanted"]."' AND `TTL` > 0";
        $result = $conn->query($sql);

        if($result == false) { 
            die(mysqli_error($conn));
            
        }

        if (mysqli_num_rows($result) > 0) 
        {

            while($row = $result->fetch_assoc()) 
            {

                $finalResult = [
                    'dataFromServer' => $row["data"],
                    'TTL'   => $row["TTL"]-1, //will decrease below
                    'expire' => $row["expire"]
                ];
            }
            //We have to decrease the TTL as we want limited number of access to secret data.
            $sql = "UPDATE `secrets` SET `TTL`= `TTL`-1 WHERE `expire` >= CURRENT_DATE() AND `generatedKey` = '".$_GET["Wanted"]."' AND `TTL` > 0";
            $result = $conn->query($sql);
            if($result == false) { 
                die(mysqli_error($conn));
                
            }
            
            //If everything was successful, send the data back to the client:
            
            //Checking which type we need to send the data back. The result will be in $returnType variable
            $returnType = 0;
            include("testConfig.php");

            switch ($returnType)
            {
                case 1: //JSON
                {
                    echo json_encode($finalResult);
                    break;
                }
                case 2: //XML
                {
                    include('xmlTest.php');
                    break;
                }
                default:
                {
                    echo "Your secret is: ".($finalResult['dataFromServer'] . "<br> You can access this secret ". $finalResult['TTL'] . " more times".
                    "<br> You cannot access this secret after ".$finalResult['expire']);
                    break;
                }
            }
            
        } 
        else 
        {
            echo "NO DATA FOUND";
        } 


    }

}

//TODO:
//INSERT INTO `secrets` (`data`, `generatedKey`, `TTL`, `expire`) VALUES ('teszt adat', 'sgsdfg3224324AAFFcsqq33', '3', '2019-04-17');


?>