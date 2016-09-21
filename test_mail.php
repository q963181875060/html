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
$cur_time += 21*60*60;
if($cur_time < time()){
   $cur_time += 24 * 60 * 60;
}

echo date("Y-m-d H:i:s",$cur_time) . " ";
echo date("Y-m-d H:i:s",1474416348);
