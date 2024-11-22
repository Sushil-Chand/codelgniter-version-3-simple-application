<?php

// $number=array('1','2','2','5','6','1','3');


// foreach($number as $num){
    
//     $result[$num]??=0;
//     $result[$num]+=1;
// }

// // print_r($result);

// // echo '<br>';

// echo "second method";

// $numbers=array(1,2,3,4,5,5,3,3,2,1,2,1,3,2,6);
// $results = [];

// for($i=0;$i< count($numbers);$i++){

//     if(!isset($results[$numbers[$i]])){
//         $results[$numbers[$i]]=1;
//     }
//     else{
//         $results[$numbers[$i]]+=1;
//     }
// }

// echo "<pre>";
// print_r($results);
// $oneCount = $results[1];
// echo $results[$oneCount];


$ceu = array( "Italy"=>"Rome", "Luxembourg"=>"Luxembourg", "Belgium"=> "Brussels", "Denmark"=>"Copenhagen", "Finland"=>"Helsinki", "France" => "Paris", "Slovakia"=>"Bratislava", "Slovenia"=>"Ljubljana", "Germany" => "Berlin", "Greece" => "Athens", "Ireland"=>"Dublin", "Netherlands"=>"Amsterdam", "Portugal"=>"Lisbon", "Spain"=>"Madrid", "Sweden"=>"Stockholm", "United Kingdom"=>"London", "Cyprus"=>"Nicosia", "Lithuania"=>"Vilnius", "Czech Republic"=>"Prague", "Estonia"=>"Tallin", "Hungary"=>"Budapest", "Latvia"=>"Riga", "Malta"=>"Valetta", "Austria" => "Vienna", "Poland"=>"Warsaw");





echo " shorting capital city";

arsort($ceu);
echo "<pre>";
print_r($ceu);


foreach($ceu as $country=>$capital){

   

    echo "The capital of $country is $capital <br>";
}


?>