<?php
// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

if ($text == "") {
    // This is the first request. Note how we start the response with CON
    $response  = "CON Welcome to NJARA  \n";
    $response .= "1. APPLICANT \n";
    $response .= "2. STUDENT \n";
   
 

} else if ($text == "1") {
    // Business logic for first level response
    $response = "CON Enter your Application code \n";
    $response .= "1. Account number \n";

} else if ($text == "2") {
    // Business logic for first level response
    // This is a terminal request. Note how we start the response with END
    $response = "END Enter your Student/Registraton code ";

} 
else if($text == "1*1") { 
    // This is a second level response where the user selected 1 in the first instance
    $accountNumber  = "ACC1001";

    // This is a terminal request. Note how we start the response with END
    $response = "END Your account number is ".$accountNumber;

}
else if($text == "4") { 
    // This is a second level response where the user selected 1 in the first instance 
    $response = "CON Choose ID you want to view \n";  
    $response .= "1. ID number \n";
    $response .= "2. ID  Card \n";

}
else if($text == "4*1") { 
    // This is a second level response where the user selected 1 in the first instance
     $IDnumber  = "11997800";
     $response = "END Your ID number is ".$IDnumber;

}
else if($text == "4*2") { 
    // This is a second level response where the user selected 1 in the first instance
     $IDnumber  = "11999658545454";
     $response = "END Your ID card is ".$IDnumber;

}
else{
     $response = "END Invalid selection "; 
}

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;

 