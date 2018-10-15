<?php

namespace App\Persistence;

use App\Classes\Logger;
use DB;
use App\Classes\Data\PrintJob;
use Illuminate\Support\Facades\Storage;

class P_PrintJobs{
    public static $PRINTER_NAME; // initialized at the bottom

    static function addPrintJob(int $user_id, string $state, $file, $two_sided, int $cost, string $costExplanation){
        $state = "QUEUED";
        try {
            $path = $file->storeAs('', md5(rand(0, 100000) . date('c')) . '.pdf', 'printing');
            $path = Storage::disk('printing')->getDriver()->getAdapter()->applyPathPrefix($path);
            $result = exec("lp -d " . self::$PRINTER_NAME . ($two_sided ? " -o sides=two-sided-long-edge " : " ") . $path . " 2>&1");
            if(!preg_match("/^request id is ([^\s]*) \\(1 file\\(s\\)\\)$/", $result, $job_id)){
                Logger::error_log("Printing error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). result:"
                    . print_r($result, true));
                $state = "ERROR";
            }
            $job_id = $job_id[1];
        }
        catch (\Exception $e){
            Logger::error_log("Printing error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ". $e->getMessage());
            $state = "ERROR";
            $job_id = "";
        }
        DB::table('print_jobs')
            ->insert([
                'filename' => $file->getClientOriginalName(),
                'filepath' => $path,
                'user' => $user_id,
                'date' => DB::raw('NOW()'),
                'state' => $state,
                'job_id' => $job_id,
                'cost' => ($state == "QUEUED") * $cost,
                'cost_explanation' => ($state == "QUEUED") ? $costExplanation : "-"
            ]);
        return $state == "QUEUED";
    }

    static function updateState(string $job_id, string $state){
        DB::table('print_jobs')
            ->where('job_id', '=', $job_id)
            ->update([
                'state' => $state
            ]);
    }

    static function getPrintJobs($userId){
        if($userId != -1) $getPrintJobs = DB::table('print_jobs')->where('user', $userId)->orderBy('print_jobs.id', 'desc')->get();
        else $getPrintJobs = DB::table('print_jobs')->orderBy('print_jobs.id', 'desc')->get();
        $printJobs = [];
        foreach($getPrintJobs as $printJob){
            $printJobs[] = new PrintJob(
                $printJob->id,
                $printJob->filename,
                $printJob->filepath,
                $printJob->user,
                $printJob->date,
                $printJob->state,
                $printJob->cost,
                $printJob->cost_explanation);
        }
        return $printJobs;
    }
}
P_PrintJobs::$PRINTER_NAME = config('app.printer_name');