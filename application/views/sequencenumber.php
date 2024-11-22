<?php





for($num=0;$num<=20;$num++)
{
    while($num!=1){
        if($num % 2 ==0){
            $num=$num/2;
        }else
        {
            $num = $num *3 +1;
        }

      
    }


echo " the guthrie index of $num is: $iteration<br>";

}
?>