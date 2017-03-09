<?php

namespace App\Classes\Layout;

use App\Classes\Logger;
use App\Persistence\P_General;
use App\Persistence\P_User;

/** Class name: BaseData
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
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class BaseData{

// PRIVATE DATA
	
// PUBLIC FUNCTIONS
		
	/** Function name: faculties
	 *
	 * Getter function for faculties.
	 * 
	 * @return array of Faculty
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function faculties(){
		try{
			$faculties = P_General::getFaculties();
		}catch(\Exception $ex){
			$faculties = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $faculties;
	}
	
	/** Function name: workshops
	 *
	 * Getter function for workshops.
	 * 
	 * @return array of Workshop
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function workshops(){
		try{
			$workshops = P_General::getWorkshops();
		}catch(\Exception $ex){
			$workshops = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $workshops;
	}
	
	/** Function name: countryCodes
	 *
	 * This function returns the country codes.
	 * Country codes are 3 capital letters!
	 * ALERT! This is a hardcoded array!
	 * 
	 * @return array of country codes
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function countryCodes(){
		return ['HUN','AFG','ALA','ALB','DZA','ASM','AND','AGO','AIA','ATA','ATG','ARG','ARM','ABW','AUS','AUT','AZE','BHS','BHR','BGD','BRB','BLR','BEL','BLZ','BEN','BMU','BTN','BOL','BES','BIH','BWA','BVT','BRA','IOT','BRN','BGR','BFA','BDI','KHM','CMR','CAN','CPV','CYM','CAF','TCD','CHL','CHN','CXR','CCK','COL','COM','COG','COD','COK','CRI','CIV','HRV','CUB','CUW','CYP','CZE','DNK','DJI','DMA','DOM','ECU','EGY','SLV','GNQ','ERI','EST','ETH','FLK','FRO','FJI','FIN','FRA','GUF','PYF','ATF','GAB','GMB','GEO','DEU','GHA','GIB','GRC','GRL','GRD','GLP','GUM','GTM','GGY','GIN','GNB','GUY','HTI','HMD','VAT','HND','HKG','ISL','IND','IDN','IRN','IRQ','IRL','IMN','ISR','ITA','JAM','JPN','JEY','JOR','KAZ','KEN','KIR','PRK','KOR','KWT','KGZ','LAO','LVA','LBN','LSO','LBR','LBY','LIE','LTU','LUX','MAC','MKD','MDG','MWI','MYS','MDV','MLI','MLT','MHL','MTQ','MRT','MUS','MYT','MEX','FSM','MDA','MCO','MNG','MNE','MSR','MAR','MOZ','MMR','NAM','NRU','NPL','NLD','NCL','NZL','NIC','NER','NGA','NIU','NFK','MNP','NOR','OMN','PAK','PLW','PSE','PAN','PNG','PRY','PER','PHL','PCN','POL','PRT','PRI','QAT','REU','ROU','RUS','RWA','BLM','SHN','KNA','LCA','MAF','SPM','VCT','WSM','SMR','STP','SAU','SEN','SRB','SYC','SLE','SGP','SXM','SVK','SVN','SLB','SOM','ZAF','SGS','SSD','ESP','LKA','SDN','SUR','SJM','SWZ','SWE','CHE','SYR','TWN','TJK','TZA','THA','TLS','TGO','TKL','TON','TTO','TUN','TUR','TKM','TCA','TUV','UGA','UKR','ARE','GBR','USA','UMI','URY','UZB','VUT','VEN','VNM','VGB','VIR','WLF','ESH','YEM','ZMB','ZWE'];
	}
	
	/** Function name: admissionYears
	 *
	 * Getter function for admission years.
	 * 
	 * @return array of admission years
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function admissionYears(){
		try{
			$admissionYears = P_General::getAdmissionYears();
		}catch(\Exception $ex){
			$admissionYears = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $admissionYears;
	}
	
	/** Function name: statusCodes
	 *
	 * Getter function for status codes.
	 * 
	 * @return array of StatusCode
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function statusCodes(){
		try{
			$statusCodes = P_User::getStatusCodes();
		}catch(\Exception $ex){
			$statusCodes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $statusCodes;
	}
	
	/** Function name: countries
	 *
	 * Getter function for countries.
	 * 
	 * @return arary of Country
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function countries(){
		try{
			$countries = P_General::getCountries();
		}catch(\Exception $ex){
			$countries = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $countries;
	}
	
	/** Function name: getPagination
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
	 * 
	 * @param ind $firstId - identifier of the element
	 * @param int $countPerPage - count of visible elements per page
	 * @param int $maximumItems - count of items
	 * @param int $pages - count of visible pages on the website
	 * @return array of pages
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getPagination($firstId, $countPerPage, $maximumItems, $pages = 5){
		$pageArray = [];
		if($firstId === null || $countPerPage === null || $maximumItems === null || $pages === null || $firstId < 0 || $countPerPage < 1 || $maximumItems < 1 || $pages < 1){
			return $pageArray;
		}
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
	
}
