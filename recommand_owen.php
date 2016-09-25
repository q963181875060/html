<?php
require_once(dirname(__FILE__) . '/wp-config.php');

/*
Parameters could be changed
*/
//how many days not login, no recommmand for the user
$no_recommand_login_days = 7;
$threshold_for_super_recommand = 5;
$super_recommand_match_amount = 2;
$super_recommand_duration_days = 15;

$mysqli = new mysqli(constant('DB_HOST'),constant('DB_USER'),constant('DB_PASSWORD'), constant('DB_NAME'));


if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if(!$mysqli->query("use " . constant('DB_NAME'))){
	echo $mysqli->error;
}
$res = $mysqli->query("SELECT * FROM wp_users");
$user_map = array();
$user_map_key_name = array();
//cur_num_match_map is to store how many matches already done for this user this time
$cur_num_match_map = array();
while($row = $res->fetch_assoc()){
	$user_map[$row['ID']] = $row;
	$user_map_key_name[$row['user_login']] = $row;
	$user_map[$row['ID']]['recommandee'] = "";
	$cur_num_match_map[$row['ID']]=0;
}
$res->close();

$res = $mysqli->query("select * from wp_usermeta where meta_key in ('is_match_on', '_um_last_login', 'match_amount', 'gender', 'contact', 'city', 'property' , 'tendation', 'super_recommand_start_time')");
while($row = $res->fetch_assoc()){
	$user_map[$row['user_id']][$row['meta_key']] = $row['meta_value'];
}
$res->close();

//firstly check the users for super recommand, and update the match_amount and super_recommand_start_time field of the lucky guy
$potential_super_recommand_user_ids = array();
$res = $mysqli->query("select meta_value, count(*) count from wp_usermeta where meta_key='recommender' group by meta_value");
while($row = $res->fetch_assoc()){
	if($row['count'] >= $threshold_for_super_recommand){
		$potential_super_recommand_user_ids[] = $user_map_key_name[$row['meta_value']]['ID'];
	}
}
$res->close();

//check the super_recommand_duration_days vs actual days for update of match_amount
foreach($potential_super_recommand_user_ids as $id){
	$should_be_update = 0;
	if(!isset($user_map[$id]['super_recommand_start_time'])){
			$user_map[$id]['match_amount'] = $super_recommand_match_amount;
			$user_map[$id]['super_recommand_start_time'] = time();
			$mysqli->query("insert into wp_usermeta (user_id, meta_key, meta_value) values ({$id}, 'super_recommand_start_time', '{$user_map[$id]['super_recommand_start_time']}')");
			$should_be_update = 1;
	}else{
		if(time() - $user_map[$id]['super_recommand_start_time'] > $super_recommand_duration_days * 24 * 60 * 60){
			if($user_map[$id]['match_amount'] == $super_recommand_match_amount){
				$user_map[$id]['match_amount'] = 1;
				$should_be_update = 1;
			}
		}else{
			if($user_map[$id]['match_amount'] != $super_recommand_match_amount){
				$user_map[$id]['match_amount'] = $super_recommand_match_amount;
				$should_be_update = 1;
			}
		}
	}
	if($should_be_update == 1){
		//update match_amount
		$mysqli->query("update wp_usermeta set meta_value = '{$user_map[$id]['match_amount']}' where user_id={$id} and meta_key='match_amount'");
	}
	
}

$res = $mysqli->query("select * from wp_recommand_owen");

//how many matches have been made for a user and a candidatei
$match_map = array();
while($match = $res->fetch_object()){
	$key = $match->master_id . ',' . $match->candidate_id;
	if(!array_key_exists($key, $match_map)){
		$match_map[$key] = array("history_matched_amount"=>1, "time"=>0);
	}else{
		$match_map[$key]["history_matched_amount"]++;
		if($match->time > $match_map[$key]["time"]) $match_map[$key]["time"] = $match->time;
	}
}
$res->close();

foreach($user_map as $key=>$user){
	$user_map[$key] = (object)$user;
}

foreach($user_map as $master){
	if(isset($master->is_match_on) && strpos($master->is_match_on,"否") != false){
		$master->recommandee = "王国已停止为你配对";
		update_recommand_db($master);
		continue;
	}
	if(time() - $master->_um_last_login > $no_recommand_login_days * 24 * 60 * 60){
		$master->recommandee = "由于连续".$no_recommand_login_days."天未登录而停止配对，欢迎回来，从今晚开始继续为你匹配";
		update_recommand_db($master);
		continue;
	}
	if(!isset($master->match_amount)){
		$master->match_amount = 1;
	}
	
	//if already matched enough users this time
	if($cur_num_match_map[$master->ID] >= $master->match_amount){
		continue;
	}
	//$master_match_array stores all the potential matched users
	$master_match_array = array();
	foreach($user_map as $candidate){
		
		if(!isset($candidate->match_amount)){
			$candidate->match_amount = 1;
		}
		if((isset($candidate->is_match_on) && strpos($candidate->is_match_on,"否") != false) || time() - $candidate->_um_last_login > $no_recommand_login_days * 24 * 60 * 60 || $candidate->ID == $master->ID){
			continue;
		}
		if(!is_pare_match($master, $candidate)){
			continue;
		}
		if($cur_num_match_map[$candidate->ID] >= $candidate->match_amount) continue;
		
		if(!array_key_exists($master->ID . "," . $candidate->ID, $match_map)){
			$match_map[$master->ID . "," . $candidate->ID] = array("history_matched_amount"=>0, "time"=>0);
			$match_map[$candidate->ID . "," . $master->ID] = array("history_matched_amount"=>0, "time"=>0);
		}
		$master_match_array[] = array("candidate_id" => $candidate->ID, "history_matched_amount" => $match_map[$master->ID . "," . $candidate->ID]["history_matched_amount"], 
			"time" => $match_map[$master->ID . "," . $candidate->ID]["time"], "gender" => $candidate->gender);
	}
	
	$tmp_match_amount = array();
	$tmp_gender = array();
	$tmp_time = array();
	foreach ($master_match_array as $candidate) {
		$tmp_match_amount[] = $candidate["history_matched_amount"];
		$tmp_gender[] = $candidate["gender"];
		$tmp_time[] = $candidate["time"];
	}
	
	// SORT_ASC是升序，降序请使用SORT_DESC
	array_multisort($tmp_match_amount, SORT_ASC, $tmp_gender, SORT_DESC, $tmp_time, SORT_ASC, $master_match_array);
	
	if(count($master_match_array) == 0){
		$master->recommandee = "暂时找不到和你同城的匹配对象，请确认资料填写是否正确，明日将继续匹配";
		update_recommand_db($master);
	}else{
		// make the match
		
		$candidate =$user_map[$master_match_array[0]["candidate_id"]];
		$cur_num_match_map[$master->ID]++;
		$cur_num_match_map[$candidate->ID]++;
		$match_map[$master->ID . "," . $candidate->ID]["history_matched_amount"]++;
		$match_map[$master->ID . "," . $candidate->ID]["time"] = time();
		$match_map[$candidate->ID . "," . $master->ID]["history_matched_amount"]++;
		$match_map[$candidate->ID . "," . $master->ID]["time"] = $match_map[$master->ID . "," . $candidate->ID]["time"];
		$WEB_HOST = constant('WEB_HOST');
		
		$master->recommandee = $master->recommandee . "<a style='color:#3ba1da' href='?author={$candidate->ID}'>{$candidate->display_name}</a>   联系方式：{$candidate->contact} <br/>";
		$candidate->recommandee = $candidate->recommandee . "<a style='color:#3ba1da' href='?author={$master->ID}'>{$master->display_name}</a>   联系方式：{$master->contact} <br/>";
		
		$time = time();
		$mysqli->query("insert into wp_recommand_owen (master_id, candidate_id, time) values ({$master->ID}, {$candidate->ID}, {$time}),({$candidate->ID}, {$master->ID}, {$time})");
		
		update_recommand_db($master);
		update_recommand_db($candidate);
		
	
	}
}
echo time() . "<br/>";

mysqli_close($mysqli);

function is_pare_match($master, $candidate){
	//if candidate match the requirement of master
	
	$res = isset($candidate->city) && isset($master->city) && trim($master->city) != "" && trim($master->city) == trim($candidate->city);
	return $res && is_pare_match_part($master, $candidate) && is_pare_match_part($candidate, $master);
}
	
function is_pare_match_part($master, $candidate){
	
	//foreach($master->tend as $tendation){
		$tendation = $master->tendation;
		if(strpos($tendation,"男王") != false){
			if(strpos($candidate->gender,"男") != false && strpos($candidate->property,"王") != false){
				return 1;
			}
		}else if(strpos($tendation,"男奴") != false){
			if(strpos($candidate->gender,"男") != false && strpos($candidate->property,"奴") != false){
				return 1;
			}
		}else if(strpos($tendation,"女王") != false){
			if(strpos($candidate->gender,"女") != false && strpos($candidate->property,"王") != false){
				return 1;
			}
		}else if(strpos($tendation, "女奴") != false){
			if(strpos($candidate->gender,"女") != false && strpos($candidate->property,"奴") != false){
				return 1;
			}
		}
	//}
	return 0;
}

//why insert recommandee fail?!
function update_recommand_db($user){
	global $mysqli;
	$res = $mysqli->query("select * from wp_usermeta where user_id={$user->ID} and meta_key='recommandee'");
	$res_num = $res->num_rows;
	$res->close();
	
	$recommandee_str = 'recommandee';
	if($res_num < 1){
		if ($stmt = $mysqli->prepare("insert into wp_usermeta (user_id, meta_key, meta_value) values (?,?,?)")) {
			/* bind parameters for markers */
			$stmt->bind_param("iss", $user->ID, $recommandee_str, $user->recommandee);
			/* execute query */
			$stmt->execute();
			$stmt->close();
		}else{
			echo "{$user->display_name}} insert fail";
		}
		
		//$mysqli->query("insert into wp_usermeta (user_id, meta_key, meta_value) values ({$user->ID}, 'recommandee', '{}')");
		//$q = "insert into wp_usermeta (user_id, meta_key, meta_value) values ({$user->ID}, 'recommandee', '{$recommand_str}')";
		//echo "insert " . $user->ID . "  " . $q . "<br/>";
	}else{
		if ($stmt = $mysqli->prepare("update wp_usermeta set meta_value = ? where user_id= ? and meta_key= ?")) {
			/* bind parameters for markers */
			$stmt->bind_param("sis", $user->recommandee, $user->ID, $recommandee_str);
			/* execute query */
			$stmt->execute();
			$stmt->close();
		}else{
			echo "{$user->display_name}} update fail";
		}
		//$mysqli->query("update wp_usermeta set meta_value = '{$recommand_str}' where user_id={$user->ID} and meta_key='recommandee'");
	}
	
}


