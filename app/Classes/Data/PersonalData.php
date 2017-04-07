<?php

namespace App\Classes\Data;

/** Class name: PersonalData
 *
 * This class stores a website User's personal data.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PersonalData{

	// PRIVATE DATA
	private $city_of_birth;
	private $date_of_birth;
	private $name_of_mother;
	private $high_school;
	private $year_of_leaving_exam;
	private $neptun;
	private $admission_year;
	private $faculties;
	private $workshops;
	private $languageExams;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the PersonalData class.
	 *
	 * @param string $neptun - user's neptun code
	 * @param string $city_of_birth - the user's birth city
	 * @param datetime $date_of_birth - the user's birth date
	 * @param string $name_of_mother - the user's mother's name
	 * @param string $high_school - the user's name of the high school
	 * @param int $year_of_leaving_exam - the user's year of the leaving exam
	 * @param int $admission_year - the user's admission year to the dormitory
	 * @param arrayOfFaculty $faculties - the user's faculties
	 * @param arrayOfWorkshop $workshops - the user's workshops
	 * @param arrayOfLanguageExam $languageExams - the user's language exam requirements
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(string $neptun, string $city_of_birth, string $date_of_birth, string $name_of_mother, string $high_school, int $year_of_leaving_exam, int $admission_year, $faculties = [], $workshops = [], $languageExams = []){
		$this->neptun = $neptun;
		$this->city_of_birth = $city_of_birth;
		$this->date_of_birth = $date_of_birth;
		$this->name_of_mother = $name_of_mother;
		$this->high_school = $high_school;
		$this->year_of_leaving_exam = $year_of_leaving_exam;
		$this->admission_year = $admission_year;
		$this->faculties = $faculties;
		$this->workshops = $workshops;
		$this->languageExams = $languageExams;
	}

	/** Function name: neptun
	 *
	 * This is the getter for neptun.
	 *
	 * @return string - The user's neptun code.
	 */
	public function neptun() : string{
		return $this->neptun;
	}
	
	/** Function name: cityOfBirth
	 *
	 * This is the getter for city_of_birth.
	 *
	 * @return string - The birth city of the user.
	 */
	public function cityOfBirth() : string{
		return $this->city_of_birth;
	}

	/** Function name: dateOfBirth
	 *
	 * This is the getter for date_of_birth.
	 *
	 * @return string - The birth date of the user.
	 */
	public function dateOfBirth() : string{
		return $this->date_of_birth;
	}
	
	/** Function name: nameOfMother
	 *
	 * This is the getter for name_of_mother.
	 *
	 * @return string - The user's mother's name.
	 */
	public function nameOfMother() : string{
		return $this->name_of_mother;
	}
	
	/** Function name: highSchool
	 *
	 * This is the getter for high_school.
	 *
	 * @return string - The user's high school name.
	 */
	public function highSchool() : string{
		return $this->high_school;
	}
	
	/** Function name: leavingExamYear
	 *
	 * This is the getter for year_of_leaving_exam.
	 *
	 * @return int - The user's date of the leaving exam (only the year).
	 */
	public function leavingExamYear() : int{
		return $this->year_of_leaving_exam;
	}
	
	/** Function name: admissionYear
	 *
	 * This is the getter for admission_year.
	 *
	 * @return int - The user's date of admission to the dormitory (only the year).
	 */
	public function admissionYear() : int{
		return $this->admission_year;
	}
	
	/** Function name: faculties
	 *
	 * This is the getter for faculties.
	 *
	 * @return Faculty - The user's faculties.
	 */
	public function faculties(){
		return $this->faculties;
	}
	
	/** Function name: workshops
	 *
	 * This is the getter for workshops.
	 *
	 * @return Workshop - The user's workshops.
	 */
	public function workshops(){
		return $this->workshops;
	}
	
	/** Function name: languageExams
	 *
	 * This is the getter for languageExams.
	 *
	 * @return LanguageExam - The user's language exam requirements.
	 */
	public function languageExams(){
		return $this->languageExams;
	}

}