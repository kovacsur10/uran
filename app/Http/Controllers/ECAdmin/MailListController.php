<?php

namespace App\Http\Controllers\ECAdmin;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: MailListController
 *
 * This controller is for handling the EC mailing lists related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class MailListController extends Controller{

	/** Function name: showList
	 *
	 * This function shows the available mailing lists.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showList(){
		$layout = new LayoutData();
		if($layout->user()->permitted('mailing_lists_handling')){
	        return view('ecadmin.maillists.list', ["layout" => $layout,
	        	"mailing_lists" => ["membraCollegii", "alumni", "rendszergazda"]
	        ]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
    
    /** Function name: show
     *
     * This function shows members of a mailing list.
     * 
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
    public function show(Request $request){
    	$this->validate($request, [
    		'mailing_lists' => 'required'
    	]);
    	$layout = new LayoutData();
    	if($layout->user()->permitted('mailing_lists_handling')){
    		try{
	    		if($request->mailing_lists === "membraCollegii"){
	    			$members = $layout->user()->getForMembraMailingList(null);
	    			return view('ecadmin.maillists.show', ["layout" => $layout,
	    					"list_name" => "membraCollegii",
	    					"members" => $members
	    			]);
	    		}else if($request->mailing_lists === "alumni"){
	    			$members = $layout->user()->getForAlumniMailingList(null);
	    			return view('ecadmin.maillists.show', ["layout" => $layout,
	    					"list_name" => "rendszergazda",
	    					"members" => $members
	    			]);
	    		}else if($request->mailing_lists === "rendszergazda"){
	    			$members = $layout->user()->getForRgMailingList(null);
	    			return view('ecadmin.maillists.show', ["layout" => $layout,
	    				"list_name" => "rendszergazda",
	    				"members" => $members
	    			]);
	    		}else{
	    			
	    		}
    		}catch(\Exception $ex){
    			return view('errors.error', ["layout" => $layout,
    				"message_indicator" => 'ecadmin.error_at_getting_users',
    				"url" => '/ecadmin/maillist/list']);
    		}
    	}else{
    		return view('errors.authentication', ["layout" => $layout]);
    	}
    }
    
    /** Function name: showDiff
     *
     * This function shows the diff of the requested
     * mailing list.
     *
     * @param Request $request
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
    public function showDiff(Request $request){
    	$this->validate($request, [
    			'mailing_lists' => 'required',
    			'mailing_lists_textarea' => 'required'
    	]);
    	$layout = new LayoutData();
    	if($layout->user()->permitted('mailing_lists_handling')){
    		try{
    			$encodedTextareaData = htmlentities($request->mailing_lists_textarea);
    			if($request->mailing_lists === "membraCollegii"){
    				$members = $layout->user()->getForMembraMailingList($encodedTextareaData);
    				return view('ecadmin.maillists.show', ["layout" => $layout,
    						"list_name" => "membraCollegii",
    						"members" => $members
    				]);
    			}else if($request->mailing_lists === "alumni"){
    				$members = $layout->user()->getForAlumniMailingList($encodedTextareaData);
    				return view('ecadmin.maillists.show', ["layout" => $layout,
    						"list_name" => "rendszergazda",
    						"members" => $members
    				]);
    			}else if($request->mailing_lists === "rendszergazda"){
    				$members = $layout->user()->getForRgMailingList($encodedTextareaData);
    				return view('ecadmin.maillists.show', ["layout" => $layout,
    						"list_name" => "rendszergazda",
    						"members" => $members
    				]);
    			}else{
    
    			}
    		}catch(\Exception $ex){
    			return view('errors.error', ["layout" => $layout,
    					"message_indicator" => 'ecadmin.error_at_getting_users',
    					"url" => '/ecadmin/maillist/list']);
    		}
    	}else{
    		return view('errors.authentication', ["layout" => $layout]);
    	}
    }
    
}

?>