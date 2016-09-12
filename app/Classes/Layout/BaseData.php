<?php

namespace App\Classes\Layout;

use DB;

class BaseData{
	private $faculties;
	private $workshops;
	private $admissionYears;
	private $countries;
	private $staticCountries;
	private $statusCodes;
	
	public function __construct(){
		$this->faculties = $this->getFaculties();
		$this->workshops = $this->getWorkshops();
		$this->admissionYears = $this->getAdmissionYears();
		$this->staticCountries = $this->getStaticCountryCodes();
		$this->statusCodes = $this->getStatusCodes();
	}
	
	public function faculties(){
		return $this->faculties;
	}
	
	public function workshops(){
		return $this->workshops;
	}
	
	public function countryCodes(){
		return $this->staticCountries;
	}
	
	public function admissionYears(){
		return $this->admissionYears;
	}
	
	public function statusCodes(){
		return $this->statusCodes;
	}
	
	public function countries(){
		if($this->countries === null){
			$this->countries = DB::table('country')
				->get();
		}
		return $this->countries === null ? [] : $this->countries->toArray();
	}
	
	private function getFaculties(){
		return DB::table('faculties')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	private function getWorkshops(){
		return DB::table('workshops')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	private function getAdmissionYears(){
		return DB::table('admission_years')
			->orderBy('year', 'asc')
			->get()
			->toArray();
	}
	
	private function getStatusCodes(){
		return DB::table('user_status_codes')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	private function getStaticCountryCodes(){
		return ['HUN','AFG','ALA','ALB','DZA','ASM','AND','AGO','AIA','ATA','ATG','ARG','ARM','ABW','AUS','AUT','AZE','BHS','BHR','BGD','BRB','BLR','BEL','BLZ','BEN','BMU','BTN','BOL','BES','BIH','BWA','BVT','BRA','IOT','BRN','BGR','BFA','BDI','KHM','CMR','CAN','CPV','CYM','CAF','TCD','CHL','CHN','CXR','CCK','COL','COM','COG','COD','COK','CRI','CIV','HRV','CUB','CUW','CYP','CZE','DNK','DJI','DMA','DOM','ECU','EGY','SLV','GNQ','ERI','EST','ETH','FLK','FRO','FJI','FIN','FRA','GUF','PYF','ATF','GAB','GMB','GEO','DEU','GHA','GIB','GRC','GRL','GRD','GLP','GUM','GTM','GGY','GIN','GNB','GUY','HTI','HMD','VAT','HND','HKG','ISL','IND','IDN','IRN','IRQ','IRL','IMN','ISR','ITA','JAM','JPN','JEY','JOR','KAZ','KEN','KIR','PRK','KOR','KWT','KGZ','LAO','LVA','LBN','LSO','LBR','LBY','LIE','LTU','LUX','MAC','MKD','MDG','MWI','MYS','MDV','MLI','MLT','MHL','MTQ','MRT','MUS','MYT','MEX','FSM','MDA','MCO','MNG','MNE','MSR','MAR','MOZ','MMR','NAM','NRU','NPL','NLD','NCL','NZL','NIC','NER','NGA','NIU','NFK','MNP','NOR','OMN','PAK','PLW','PSE','PAN','PNG','PRY','PER','PHL','PCN','POL','PRT','PRI','QAT','REU','ROU','RUS','RWA','BLM','SHN','KNA','LCA','MAF','SPM','VCT','WSM','SMR','STP','SAU','SEN','SRB','SYC','SLE','SGP','SXM','SVK','SVN','SLB','SOM','ZAF','SGS','SSD','ESP','LKA','SDN','SUR','SJM','SWZ','SWE','CHE','SYR','TWN','TJK','TZA','THA','TLS','TGO','TKL','TON','TTO','TUN','TUR','TKM','TCA','TUV','UGA','UKR','ARE','GBR','USA','UMI','URY','UZB','VUT','VEN','VNM','VGB','VIR','WLF','ESH','YEM','ZMB','ZWE'];
	}
	
}
