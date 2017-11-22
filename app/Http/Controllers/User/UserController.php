<?php

namespace App\Http\Controllers\User;

use App\Classes\LayoutData;
use App\Classes\Layout\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/** Class name: UserController
 *
 * This controller is for handling the user data related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class UserController extends Controller{
	
	/** Function name: showData
	 *
	 * This function shows the user data page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showData(){
		return view('user.showdata', ["layout" => new LayoutData()]);
	}
	
	/** Function name: showPublicData
	 *
	 * This function shows the public user data page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showPublicData($username){
		$layout = new LayoutData();
		try{
			$targetId = $layout->user()->getUserDataByUsername($username);
			return view('user.showpublicdata', ["layout" => $layout,
					"target" => new User($targetId->id())]);
		}catch(\Exception $ex){
			return view('errors.usernotfound', ["layout" => $layout]);
		}
	}
	
	/** Function name: getLanguageExams
	 *
	 * This function shows the user's language exam
	 * requirements.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getLanguageExams(){
		$layout = new LayoutData();
		return view('user.languageexams.list', ["layout" => $layout]);
	}
	
	/** Function name: showUploadLanguageExam
	 *
	 * This function shows the language exam upload page.
	 * 
	 * @param int $examid - language exam identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showUploadLanguageExam($examid){
		$layout = new LayoutData();
		if($layout->user()->user()->hasLanguageExam($examid)){
			return view('user.languageexams.show', ["layout" => $layout,
					"exam" => $layout->user()->user()->getLanguageExam($examid)
			]);
		}else{
			$layout->errors()->add('upload', __('languageexams.error_at_getting_the_language_exam'));
			return view('user.languageexams.show', ["layout" => $layout,
					"exam" => $layout->user()->user()->getLanguageExam($examid)
			]);
		}
	}
	
	/** Function name: uploadLanguageExam
	 *
	 * This function uploads a new language exam.
	 *
	 * @param int $examid - language exam identifier
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function uploadLanguageExam($examid, Request $request){
		$this->validate($request, [
			'exampicture' => 'required',
		]);
		
		$layout = new LayoutData();
		if($layout->user()->user()->hasLanguageExam($examid)){
			$image = $request->exampicture;
			try{
				$layout->user()->uploadLanguageExamPicture($examid, $image);
				return redirect('data/languageexam/upload');
			}catch(\Exception $ex){
				$layout->errors()->add('upload', __('languageexams.error_at_uploading_the_language_exam'));
				return view('user.languageexams.show', ["layout" => $layout,
						"exam" => $layout->user()->user()->getLanguageExam($examid)
				]);
			}
		}else{
			$layout->errors()->add('upload', __('languageexams.error_at_getting_the_language_exam'));
			return view('user.languageexams.show', ["layout" => $layout,
					"exam" => $layout->user()->user()->getLanguageExam($examid)
			]);
		}
	}
	
	/** Function name: showLanguageExam
	 *
	 * This function returns an uploaded language exam
	 * picture or document file.
	 *
	 * @param string $location - filename
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showLanguageExam($location){
		$filename = 'languageexams/'.$location;		
		if(!Storage::disk('langexams')->has($location)) {
			return response()->view('errors.404', ["layout" => new LayoutData()], 404);
		}
		$file = Storage::disk('langexams')->get($location);
		
		$type =	Storage::mimeType($filename);	
		return response($file)
			->header('Content-Type', $type);
	}
}
