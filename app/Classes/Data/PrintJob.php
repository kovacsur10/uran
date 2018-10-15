<?php

namespace App\Classes\Data;

use App\Classes\Data\User;

class PrintJob{

	// PRIVATE DATA
	private $id;
	private $filename;
	private $filepath;
	private $user_id;
	private $date;
	private $state;
	private $cost;
	private $cost_explanation;

	// PUBLIC FUNCTIONS

	public function __construct(int $id, string $filename, string $filepath, int $user_id, string $date, string $state, $cost, $costExplanation){
		$this->id = $id;
		$this->filename = $filename;
		$this->filepath = $filepath;
		$this->user_id = $user_id;
		$this->date = $date;
		$this->state = $state;
		$this->cost = $cost ?? 0;
		$this->cost_explanation = $costExplanation;
	}


	public function id() : int{
		return $this->id;
	}
	

	public function filename() : string{
		return $this->filename;
	}

	
	public function date() : string{
		return $this->date;
	}

    public function filepath() : string{
        return $this->filepath;
    }

    public function user_id() : int{
        return $this->user_id;
    }

    public function state() : string{
        return $this->state;
    }

    public function cost() : int{
        return $this->cost;
    }

    public function costExplanation() : string{
        return $this->cost_explanation ?? "";
    }
}