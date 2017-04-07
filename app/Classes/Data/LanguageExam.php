<?php

namespace App\Classes\Data;

/** Class name: LanguageExam
 *
 * This class stores a FONAL language exam phase of a user.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LanguageExam{

	// PRIVATE DATA
	private $id;
	private $language;
	private $resolved;
	private $level;
	private $pictures;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the LanguageExam class.
	 *
	 * @param string $id - language exam require identifier
	 * @param string $language - language name
	 * @param string $level - level of the language exam
	 * @param bool $resolved - the user resolved this requirement or not
	 * @param arrayOfString $picture - the path of the language exam picture
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $language, string $level, bool $resolved = false, $pictures = null){
		if($pictures === null){
			$pictures = [];
		}
		$this->id = $id;
		$this->language = $language;
		$this->resolved = $resolved;
		$this->pictures = $pictures;
		$this->level = $level;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return string - The identifier of the language exam requirement.
	 */
	public function id() : string{
		return $this->id;
	}

	/** Function name: language
	 *
	 * This is the getter for language.
	 *
	 * @return string - The language.
	 */
	public function language() : string{
		return $this->language;
	}
	
	/** Function name: resolved
	 *
	 * This is the getter for resolved.
	 *
	 * @return bool - The user resolved this requirement or not.
	 */
	public function resolved() : bool{
		return $this->resolved;
	}
	
	/** Function name: level
	 *
	 * This is the getter for level.
	 *
	 * @return string - The level.
	 */
	public function level() : string{
		return $this->level;
	}
	
	/** Function name: pictures
	 *
	 * This is the getter for pictures.
	 *
	 * @return array of string - The pictures of the language exam.
	 */
	public function pictures(){
		return $this->pictures;
	}

}