<?php
$send = mail('279340843@qq.com', 'My Subject', 'The test mail');
echo $send;
if($send){echo 'success';}else{echo 'fail';}
?>

