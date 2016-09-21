<?php
/**
 * Plugin Name: recommand_owen
 * Plugin URI: 
 * Description: This plugin recommend users
 * Version: 1.0.0
 * Author: Owen
 * Author URI: 
 * License: GPL2
 */
register_activation_hook(__FILE__, 'recommand_owen_activation');

$no_recommand_login_days = 7;
$time_for_recommand = 18;

function recommand_owen_activation() {
	global $time_for_recommand;
	$cur_time = strtotime(date('Y-m-d',strtotime('+0 day')));
	$cur_time += $time_for_recommand*60*60;
	if($cur_time < time()){
	   $cur_time += 24 * 60 * 60;
	}
    if (!wp_next_scheduled ( 'recommand_owen_event' )) {
		wp_schedule_event($cur_time, 'daily', 'recommand_owen_event');
    }
	//wp_schedule_event($cur_time, 'daily', 'recommand_owen_event');
	
}

add_action('recommand_owen_event', 'recommand_owen_method');

register_deactivation_hook(__FILE__, 'recommand_owen_deactivation');

function recommand_owen_deactivation() {
	wp_clear_scheduled_hook('recommand_owen_event');
}

function recommand_owen_method() {
	global $wpdb;
	global $no_recommand_login_days;
	$blogusers = get_users();
	$match_history = $wpdb->get_results("select * from wp_recommand_owen");
	
	//how many matches have been made for a user and a candidate
	$match_map = array();
	foreach($match_history as $match){
		$key = $match->master_id . ',' . $match->candidate_id;
		if(!array_key_exists($key, $match_map)){
			$match_map[$key] = array("history_matched_amount"=>1, "time"=>0);
		}else{
			$match_map[$key]["history_matched_amount"]++;
			if($match->time > $match_map[$key]["time"]) $match_map[$key]["time"] = $match->time;
		}
	}
	
	$user_map = array();
	//cur_num_match_map is to store how many matches already done for this user this time
	$cur_num_match_map = array();
	foreach($blogusers as $user){
		$user_map[$user->ID] = $user; 
		$cur_num_match_map[$user->ID] = 0; 
		$user->recommand1 = "";
	}
	
	foreach($blogusers as $master){
		if((isset($master->is_match_on) && $master->is_match_on[0] == "否") || time() - $master->_um_last_login > $no_recommand_login_days * 24 * 60 * 60){
			$master->recommand1 = "由于连续七天未登录而停止配对，欢迎回来，从今晚开始继续为你匹配";
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
		foreach($blogusers as $candidate){
			if(!isset($candidate->match_amount)){
				$candidate->match_amount = 1;
			}
			if((isset($candidate->is_match_on) && $candidate->is_match_on[0] == "否") || time() - $candidate->_um_last_login > $no_recommand_login_days * 24 * 60 * 60 || $candidate->ID == $master->ID || !is_pare_match($master, $candidate)){
				continue;
			}
			if($cur_num_match_map[$candidate->ID] >= $candidate->match_amount) continue;
			
			if(!array_key_exists($master->ID . "," . $candidate->ID, $match_map)){
				$match_map[$master->ID . "," . $candidate->ID] = array("history_matched_amount"=>0, "time"=>0);
				$match_map[$candidate->ID . "," . $master->ID] = array("history_matched_amount"=>0, "time"=>0);
			}
			$master_match_array[] = array("candidate_id" => $candidate->ID, "history_matched_amount" => $match_map[$master->ID . "," . $candidate->ID]["history_matched_amount"], 
				"time" => $match_map[$master->ID . "," . $candidate->ID]["time"], "gender" => $candidate->gender[0]);
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
		//print_r($master_match_array);
		if(count($master_match_array) == 0){
			$master->recommand1 = "暂时找不到和你同城的匹配对象，请确认资料填写是否正确，明日将继续匹配";
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
			$master->recommand1 = $master->recommand1 . "为你配对：" . $candidate->display_name . " 联系方式：" . $candidate->contact . " 个人主页:www.xrzwg.cn?author=" . $candidate->ID;
			$candidate->recommand1 = $candidate->recommand1 . "为你配对：" .  $master->display_name . " 联系方式：" . $master->contact . " 个人主页:www.xrzwg.cn?author=" . $master->ID;
			$wpdb->insert( 
				'wp_recommand_owen', 
				array( 
					'master_id' => $master->ID, 
					'candidate_id' => $candidate->ID,
					'time' => time()
				), 
				array( 
					'%d', 
					'%d',
					'%d'
				) 
			);
			$wpdb->insert( 
				'wp_recommand_owen', 
				array( 
					'master_id' => $candidate->ID,
					'candidate_id' => $master->ID,
					'time' => time()
				), 
				array( 
					'%d', 
					'%d',
					'%d'
				) 
			);
			update_recommand_db($master);
			update_recommand_db($candidate);
		}
	}
	
	echo 'I am in the head section';
}

function is_pare_match($master, $candidate){
	//if candidate match the requirement of master
	return isset($master->city) && trim($master->city) != "" && trim($master->city) == trim($candidate->city) && is_pare_match_part($master, $candidate) && is_pare_match_part($candidate, $master);
}
	
function is_pare_match_part($master, $candidate){
	foreach($master->tend as $tendation){        
		if($tendation == "男王"){
			if($candidate->gender[0] == "男" && ($candidate->property[0] == "王" || $candidate->property[0] == "王和奴均可")){
				return 1;
			}
		}else if($tendation == "男奴"){
			if($candidate->gender[0] == "男" && ($candidate->property[0] == "奴" || $candidate->property[0] == "王和奴均可")){
				return 1;
			}
		}else if($tendation == "女王"){
			if($candidate->gender[0] == "女" && ($candidate->property[0] == "王" || $candidate->property[0] == "王和奴均可")){
				return 1;
			}
		}else if($tendation == "女奴"){
			if($candidate->gender[0] == "女" && ($candidate->property[0] == "奴" || $candidate->property[0] == "王和奴均可")){
				return 1;
			}
		}
	}
	return 0;
}

function update_recommand_db($user){
	//print_r($user);
	global $wpdb;
	$res = $wpdb->update( 
		'wp_usermeta', 
		array( 
			'meta_value' => $user->recommand1,	// string
		), 
		array( 'user_id' => $user->ID, 'meta_key' => 'recommand1' ), 
		array( '%s' ),
		array( 
			'%d',	
			'%s'	
		)
	);
	//echo "update affect row num =".$res;
	if($res == 0){
		$res = $wpdb->insert( 
			'wp_usermeta', 
			array( 'user_id' => $user->ID, 'meta_key' => 'recommand1', 'meta_value' => $user->recommand1), 
			array( 
				'%d',	
				'%s',	
				'%s'
			)
		);
	//	echo "insert affect row num =".$res;
	}
}