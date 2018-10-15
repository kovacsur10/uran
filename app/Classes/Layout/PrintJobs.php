<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_PrintJobs;
use App\Classes\Interfaces\Pageable;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Database;
use Illuminate\Http\Request;
use App\Classes\Layout\EcnetData;


class PrintJobs extends Pageable{
	
// PRIVATE DATA
	private $costperpage = 8;
	private $printJobs;
	private $printJob;

// PUBLIC FUNCTIONS
    

	public function __construct(int $userId){
	    $this->getPrintJobs($userId);
	}
	

	public function get(){
        return $this->printJobs;
	}

    public function getPrintJobs(int $userId){
        try{
            $this->printJobs = P_PrintJobs::getPrintJobs($userId);
        }catch(\Exception $ex){
            $this->printJobs = [];
            Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
        }
        return $this->printJobs;
    }

    public function printJobsToPages($from = 0, $count = 50){
        return $this->toPages($this->printJobs, $from, $count);
    }
}