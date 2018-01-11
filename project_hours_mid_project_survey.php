<?php 

$to = '0144a918.sohodragon.com@amer.teams.ms,rm@sohodragon.com,mh@sohodragon.com';
//$to='67e3cba4.sohodragon.com@amer.teams.ms';
$headers = 'From: noreply@wardpeter.com' . "\r\n" .
    'Reply-To: noreply@wardpeter.com' . "\r\n" .
    'CC: pw@sohodragon.com' . "\r\n" .
    "Content-type: text/html; charset=\"UTF-8\"; format=flowed \r\n";
    'X-Mailer: PHP/' . phpversion();

    
echo "The To Address is: \r\n".$to;


require_once( 'connection.php' );
  
  $today=date('Ymd');
  
  
  $result= $api->getProjects();
  $projects=$result->data;
  
  
  
  foreach($projects as $key=>$value){
  $project_id=$value->get("id");
  $project_name=$value->get("name");
  $client_id=$value->get("client-id");
   $budgetHours=$value->get("budget"); 
   $active=$value->get("active");
   
   if($active=="true"){
//  echo "The Project ID is: ".$project_id;
 //Getting the Client Name
   $resultGetClient=$api->getClient($client_id);
$clientGetClient=$resultGetClient->data;
$clientName=$clientGetClient->get("name");
  
  //echo "The Client Name is: \r\n".$clientName;
  
 
  
  //Getting the Project Start Date-Final:$start_Date
  $date=$value->get("created-at");

//Removing the Portion of the String Including and after "T"
 $start_date=strstr($date, 'T', true);
 
 //Removing all the Hyphens in the String
 $start_Date= str_replace('-', '', $start_date);
 
 //echo "The Start Date is: \n".$start_Date;
 //echo "The End Date is:  \n".$today;
   
$range= new Harvest_Range($start_Date,$today);

  $resultGetEntries=$api->getProjectEntries($project_id,$range); 
     $dayEntries= $resultGetEntries->data;
     
      
     // echo "The Total Budget Hours is: ".$budgetHours;
     
     
     //Calculating Total Hours During a Given Time Range
                        $totalActualHours=0;
                        foreach($dayEntries as $key=>$value){
                          $totalActualHours=$totalActualHours+ $value->get("hours");
                          }
                          
                         // echo "The Total Actual Hours is: \n".$totalActualHours;
$budgetPercentage=($totalActualHours/$budgetHours)*100;

//echo "The Budget Percentage is: \n".$budgetPercentage; 
if( (50<=$budgetPercentage) && ($budgetPercentage<=60) ){


$subject = 'ACTION REQUIRED - Mid Project Survey: Please send the Type Form to this Client: '.$clientName;
$message = 'The Project Name is '.$project_name .'. The Form is in Hubspot.';
$message2='Kindly Ignore this email if you have already sent the Mid Project Survey to the client for this particular project.';
$message3="Best Regards,";
$message4="SoHo Ops";
$Final_Message=$message."<br><br>".$message2."<br><br>".$message3."<br>".$message4;
mail($to, $subject, $Final_Message, $headers);

echo "The Mail is Sent";
}

  }}

/*
$to      = 'az6p6jgxjulxtfpzcbt7@boards.trello.com';
$subject = 'Please send the Type Form to this Client: '.$clientName;
$message = 'Please send the Type Form to the Client. Confirm with the Project Manager before sending it';
$headers = 'From: rr@sohodragon.com' . "\r\n" .
    'Reply-To: rr@sohodragon.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
*/


?>