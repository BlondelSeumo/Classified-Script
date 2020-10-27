<?php 
namespace Packages\Tracker;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jenssegers\Agent\Agent;

/**
 * Tracker class
 * @copyright MendelManGroup
 * @author MendelManGrou <ezzaroual@mail.com>
 * @category Tracking
 */
class Tracker
{
	public $isSucceed           = false;
	public $continent           = null;
	public $countryName         = null;
	public $countryCode         = null;
	public $state               = null;
	public $city                = null;
	public $latitude            = null;
	public $longitutde          = null;
	public $provider            = null;
	public $isPhone             = false;
	public $isTablet            = false;
	public $isDesktop           = true;
	public $isRobot             = false;
	public $robotName           = null;
	public $deviceName          = null;
	public $browserName         = null;
	public $browserVersion      = null;
	public $platformName        = null;
	public $platformVersion     = null;
	public $referrer            = null;
	public $searchEngineKeyword = null;
	public $referrerDomain      = null;
	public $first_visit         = null;
	public $last_visit          = null;


	public function __construct()
	{
		$this->api();
	}


	/**
	 * Get api data
	 * @return [type] [description]
	 */
	public function api()
	{
		$ip = ip();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
		curl_setopt($ch, CURLOPT_URL, 'http://ip-api.com/json/' . $ip);
		$result = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($result);

		$this->setResponse($response);
	}


	/**
	 * Set response from api
	 * @param [type] $response [description]
	 */
	public function setResponse($response)
	{
		// Check if response success
		if ($response && $response->status !== "fail") {
			
			$CrawlerDetect             = new CrawlerDetect;
			$agent                     = new Agent;
			$this->continent           = $this->getContinent($response->countryCode);
			$this->countryName         = $response->country;
			$this->countryCode         = $response->countryCode;
			$this->state               = $response->regionName;
			$this->city                = $response->city;
			$this->latitude            = $response->lat;
			$this->longitutde          = $response->lon;
			$this->provider            = $response->isp;
			$this->isPhone             = $agent->isMobile();
			$this->isTablet            = $agent->isTablet();
			$this->isDesktop           = $agent->isDesktop();
			$this->isRobot             = $CrawlerDetect->isCrawler();
			$this->robotName           = $CrawlerDetect->getMatches();
			$this->platformName        = $agent->platform();
			$this->platformVersion     = $agent->version($this->platformName);
			$this->browserName         = $agent->browser();
			$this->browserVersion      = $agent->version($this->browserName);
			$this->deviceName          = $agent->device();
			$this->referrer            = getenv('HTTP_REFERER');
			$this->referrerDomain      = parse_url(getenv('HTTP_REFERER'), PHP_URL_HOST);
			$this->searchEngineKeyword = $this->getSearchEngineKeyword();
			$this->first_visit         = now();
			$this->last_visit          = now();
			$this->isSucceed           = $this->status($response->status);

		}
	}


	/**
	 * Get response status
	 * @param  [type] $status [description]
	 * @return [type]         [description]
	 */
	private function status($status)
	{
		if ($status == "success") {
			return true;
		}
		return false;
	}


	/**
	 * Get country continent
	 * @param  [type] $country [description]
	 * @return [type]          [description]
	 */
	private function getContinent($country)
	{
		// Get countries
		$countries = config('countries');

		// Return continent
		return $countries[$country]['continent'];
	}


	/**
	 * Get search engine
	 * @param  boolean $url [description]
	 * @return [type]       [description]
	 */
	private function getSearchEngineKeyword() 
	{
		// Get referrer
		if (getenv('HTTP_REFERER')) {
			$url = getenv('HTTP_REFERER');	
		}else{
			return null;
		}

		// Parse link
    	$parts = parse_url($url);

    	// Check if query exists
    	if (!isset($parts['query'])) {
    		return null;
    	}

    	// Get query part
    	parse_str($parts['query'], $query);

    	// Default search engines
	    $search_engines = array(
			'bing'         => 'q',
			'google'       => 'q',
			'yahoo'        => 'p',
			'ask'          => 'q',
			'yandex'       => 'text',
			'aol'          => 'q',
			'baidu'        => 'wd',
			'wolframalpha' => 'i',
			'duckduckgo'   => 'q'
	    );

    	preg_match('/(' . implode('|', array_keys($search_engines)) . ')\./', $parts['host'], $matches);

    	return isset($matches[1]) && isset($query[$search_engines[$matches[1]]]) ? $query[$search_engines[$matches[1]]] : '';
	}
}