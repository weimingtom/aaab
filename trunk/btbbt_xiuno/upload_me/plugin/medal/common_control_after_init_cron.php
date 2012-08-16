if($_SERVER['time'] > $cron_2_next_time) {
	$this->medal_user->cron_clear_expiredtime();
}