<?php

    $servername = "localhost";
    $username = "averageUser";
    $password = "DD7IW7bqrM3O368s";
    $dbname = "secretserver";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8"); //set UTF8 encoding


    /*
    This solution is from: https://stackoverflow.com/questions/4356289/php-random-string-generator
    */
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    $sanitizedSecret = mysqli_real_escape_string($conn,$_POST['secret']);
    $secretKey = generateRandomString(12);
    //$sanitizedSecret = $_POST['secret'];
    //$secretKey = random_bytes(12);


    //TODO: We could check, if this random generated link is already exists with a SELECT query, but due to the size of this project, collision is not probable.

   
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

else

//Connection was successful
{

    $sql = "INSERT INTO `secrets` (`data`, `generatedKey`, `TTL`, `expire`) VALUES ('".$sanitizedSecret."', '".$secretKey."', '".$_POST['numberOfAccess']."', '".$_POST['expireDate']."')";
    $result = $conn->query($sql);

    if($result == false) {
        echo $sql."<br>Trouble! ";
        die(mysqli_error($conn));
        
    }
    else
    {
        //If everything was successful, send the data back to the client:
        echo "New secret is created! <br> Access it using this unique link:<br>"
        .$servername."/SecretServer/getSecret.php/?Wanted=".$secretKey;
    }
}



    

?>