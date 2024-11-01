<?php

class wikiLeechMedal {
	private $data2;
	private $start;
	private $end;
	private $cache_file;
	
	
	public function __construct() {
		$this->cache_file = plugin_dir_path(__FILE__).'medal.cache';
	}
	
	public function leechMeMedal($url) {
		$data = file_get_contents($url);
		$data = strip_tags($data, '<ul>, <li>'); //wywala ca³y html
		$this->data2 = trim(preg_replace('/\s+/', ' ', $data)); //usuwa wszystkie, niepotrzebne przerwy, entery itp.
	}
	
	public function showWikiMedal($start, $end) {
		$data = $this->data2;
		$matches = array();
    	$pattern = "/$start(.*?)$end/";
    	if (preg_match($pattern, $data, $matches)) {
			return "<div class=\"wikimedal\">" . $matches[1] . "</div>";
		}
	}
 	
	public function cacheMeOrNotMedal($cache, $url, $start, $end) {
		if($cache == 1) {
			if(file_exists($this->cache_file) && (filesize($this->cache_file) > 50) && (filemtime($this->cache_file) > (time() - 3600 * 5 ))) {
				$cached = file_get_contents($this->cache_file);
				echo $cached;
			}else {
				$this->leechMeMedal($url);
				$to_file = $this->showWikiMedal($start, $end);
				file_put_contents($this->cache_file, $to_file);
				$cached = file_get_contents($this->cache_file);
				echo $cached;
			}
		}else {
			$this->leechMeMedal($url);
			echo $this->showWikiMedal($start, $end);
		}
	}
}