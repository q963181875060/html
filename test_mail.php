<?php

/*$cur_time = strtotime(date('Y-m-d',strtotime('+0 day')));
deal();

function deal(){
global $cur_time;
$cur_time += 21*60*60;
if($cur_time < time()){
   echo "before";
   $cur_time += 24 * 60 * 60;
}*/
        $cur_time = strtotime(date('Y-m-d',strtotime('+0 day')));
        $cur_time += 17*60*60;
        if($cur_time < time()){
           $cur_time += 24 * 60 * 60;
        }



//echo date("Y-m-d H:i:s",$cur_time) . " " . '<br/>';
//echo time() . '<br/>';

echo date("Y-m-d H:i:s",1474740061) . " ";
echo date("Y-m-d H:i:s",1474747261) . " ";
echo date("Y-m-d H:i:s",1474761661) . " ";

echo date("Y-m-d H:i:s",1474765261) . " ";
