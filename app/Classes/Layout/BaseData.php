<?php

namespace App\Classes\Layout;

use DB;
use App\Classes\Logger;

/* Class name: BaseData
 *
 * This class handles the base data
 * support in the layout namespace.
 *
 * Functionality:
 * 		- faculty support
 * 		- workshop support
 * 		- country support
 * 		- admission year support
 * 		- status code support
 * 
 * Functions that can throw exceptions:
 */
class BaseData{

// PRIVATE DATA
	
	private $faculties;
	private $workshops;
	private $admissionYears;
	private $countries;
	private $staticCountries;
	private $statusCodes;
	
// PUBLIC FUNCTIONS
	
	/* Function name: __construct
	 * Input: -
	 * Output: -
	 *
	 * The constructor for the BaseData class.
	 */
	public function __construct(){
		$this->faculties = $this->getFaculties();
		$this->workshops = $this->getWorkshops();
		$this->admissionYears = $this->getAdmissionYears();
		$this->staticCountries = $this->getStaticCountryCodes();
		$this->statusCodes = $this->getStatusCodes();
	}
	
	/* Function name: faculties
	 * Input: -
	 * Output: array of faculties
	 *
	 * Getter function for faculties.
	 */
	public function faculties(){
		return $this->faculties;
	}
	
	/* Function name: workshops
	 * Input: -
	 * Output: array of workshops
	 *
	 * Getter function for workshops.
	 */
	public function workshops(){
		return $this->workshops;
	}
	
	/* Function name: countryCodes
	 * Input: -
	 * Output: array of country codes
	 *
	 * Getter function for country codes.
	 */
	public function countryCodes(){
		return $this->staticCountries;
	}
	
	/* Function name: admissionYears
	 * Input: -
	 * Output: array of admission years
	 *
	 * Getter function for admission years.
	 */
	public function admissionYears(){
		return $this->admissionYears;
	}
	
	/* Function name: statusCodes
	 * Input: -
	 * Output: array of status codes
	 *
	 * Getter function for status codes.
	 */
	public function statusCodes(){
		return $this->statusCodes;
	}
	
	/* Function name: countries
	 * Input: -
	 * Output: arary of countries
	 *
	 * Getter function for countries.
	 */
	public function countries(){
		if($this->countries === null){
			try{
				$this->countries = DB::table('country')
					->get()
					->toArray();
			}catch(\Exception $ex){
				$this->countries = [];
			}
		}
		return $this->countries;
	}
	
	/* Function name: getPagination
	 * Input: 	$firstId (int) - identifier of the element
	 * 			$countPerPage (int) - count of visible elements per page
	 * 			$maximumItems (int) - count of items
	 * 			$pages (int) - count of visible pages on the website
	 * Output: array of pages
	 *
	 * This function returnes the pagination pages.
	 * The pages are starting at the highest lower value.
	 * 
	 * The returned structure is an array,
	 * the key is the identifier of the page,
	 * the value can be
	 * 		- the text 'middle', if the page is the currently active one
	 * 			(middle element in the list)
	 * 		- the text 'disabled' if the page is not exist
	 * 		- the lowest identifier of the elements in that page.
	 */
	public function getPagination($firstId, $countPerPage, $maximumItems, $pages = 5){
		$pageArray = [];
		$firstId -= ($firstId % $countPerPage);
		$firstId = $firstId < 0 ? 0 : ($maximumItems-$countPerPage < $firstId ? $maximumItems - ($maximumItems % $countPerPage) : $firstId);
		$lastId = $maximumItems + ($countPerPage - ($maximumItems % $countPerPage));

		for($i = $firstId - ($pages * $countPerPage); $i < $firstId + (($pages+1) * $countPerPage); $i += $countPerPage){
			if($i === $firstId){
				$pageArray[($i / $countPerPage)+ 1] = 'middle';
			}else if($i < 0 || $lastId <= $i){
				$pageArray[($i / $countPerPage)+ 1] = 'disabled';
			}else{
				$pageArray[($i / $countPerPage)+ 1] = $i;
			}
		}
		return $pageArray;
	}
	
// PRIVATE FUNCTIONS
	
	/* Function name: getFaculties
	 * Input: -
	 * Output: array of the faculties
	 *
	 * This function returns the faculties
	 * from the database.
	 */
	private function getFaculties(){
		try{
			$faculties = DB::table('faculties')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$faculties = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'faculties' was not successful! ".$ex->getMessage());
		}
		return $faculties;
	}
	
	/* Function name: getWorkshops
	 * Input: -
	 * Output: array of the workshops
	 *
	 * This function returns the workshops of 
	 * the dormitory from the database.
	 */
	private function getWorkshops(){
		try{
			$workshops = DB::table('workshops')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$workshops = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'workshops' was not successful! ".$ex->getMessage());
		}
		return $workshops;
	}
	
	/* Function name: getAdmissionYears
	 * Input: -
	 * Output: array of admission years
	 *
	 * This function returns the available 
	 * admission years from the database.
	 */
	private function getAdmissionYears(){
		try{
			$admissionYears = DB::table('admission_years')
				->orderBy('year', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$admissionYears = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'admission_years' was not successful! ".$ex->getMessage());
		}
		return $admissionYears;
	}
	
	/* Function name: getStatusCodes
	 * Input: -
	 * Output: array of status codes
	 *
	 * This function returns the user
	 * status codes from the database.
	 */
	private function getStatusCodes(){
		try{
			$statusCodes = DB::table('user_status_codes')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$statusCodes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'user_status_codes' was not successful! ".$ex->getMessage());
		}
		return $statusCodes;
	}
	
	/* Function name: getStaticCountryCodes
	 * Input: -
	 * Output: array of country codes
	 *
	 * This function returns an array
	 * of the country codes on Earth.
	 */
	private function getStaticCountryCodes(){
		return ['HUN','AFG','ALA','ALB','DZA','ASM','AND','AGO','AIA','ATA','ATG','ARG','ARM','ABW','AUS','AUT','AZE','BHS','BHR','BGD','BRB','BLR','BEL','BLZ','BEN','BMU','BTN','BOL','BES','BIH','BWA','BVT','BRA','IOT','BRN','BGR','BFA','BDI','KHM','CMR','CAN','CPV','CYM','CAF','TCD','CHL','CHN','CXR','CCK','COL','COM','COG','COD','COK','CRI','CIV','HRV','CUB','CUW','CYP','CZE','DNK','DJI','DMA','DOM','ECU','EGY','SLV','GNQ','ERI','EST','ETH','FLK','FRO','FJI','FIN','FRA','GUF','PYF','ATF','GAB','GMB','GEO','DEU','GHA','GIB','GRC','GRL','GRD','GLP','GUM','GTM','GGY','GIN','GNB','GUY','HTI','HMD','VAT','HND','HKG','ISL','IND','IDN','IRN','IRQ','IRL','IMN','ISR','ITA','JAM','JPN','JEY','JOR','KAZ','KEN','KIR','PRK','KOR','KWT','KGZ','LAO','LVA','LBN','LSO','LBR','LBY','LIE','LTU','LUX','MAC','MKD','MDG','MWI','MYS','MDV','MLI','MLT','MHL','MTQ','MRT','MUS','MYT','MEX','FSM','MDA','MCO','MNG','MNE','MSR','MAR','MOZ','MMR','NAM','NRU','NPL','NLD','NCL','NZL','NIC','NER','NGA','NIU','NFK','MNP','NOR','OMN','PAK','PLW','PSE','PAN','PNG','PRY','PER','PHL','PCN','POL','PRT','PRI','QAT','REU','ROU','RUS','RWA','BLM','SHN','KNA','LCA','MAF','SPM','VCT','WSM','SMR','STP','SAU','SEN','SRB','SYC','SLE','SGP','SXM','SVK','SVN','SLB','SOM','ZAF','SGS','SSD','ESP','LKA','SDN','SUR','SJM','SWZ','SWE','CHE','SYR','TWN','TJK','TZA','THA','TLS','TGO','TKL','TON','TTO','TUN','TUR','TKM','TCA','TUV','UGA','UKR','ARE','GBR','USA','UMI','URY','UZB','VUT','VEN','VNM','VGB','VIR','WLF','ESH','YEM','ZMB','ZWE'];
	}
	
}
