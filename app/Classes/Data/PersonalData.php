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
	private $faculty;
	private $workshop;

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
	 * @param string $year_of_leaving_exam - the user's year of the leaving exam
	 * @param string $admission_year - the user's admission year to the dormitory
	 * @param Faculty $faculty - the user's faculty
	 * @param Workshop $workshop - the user's workshop
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(string $neptun, string $city_of_birth, string $date_of_birth, string $name_of_mother, string $high_school, string $year_of_leaving_exam, string $admission_year, Faculty $faculty, Workshop $workshop){
		$this->neptun = $neptun;
		$this->city_of_birth = $city_of_birth;
		$this->date_of_birth = $date_of_birth;
		$this->name_of_mother = $name_of_mother;
		$this->high_school = $high_school;
		$this->year_of_leaving_exam = $year_of_leaving_exam;
		$this->admission_year = $admission_year;
		$this->faculty = $faculty;
		$this->workshop = $workshop;
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
	 * @return string - The user's date of the leaving exam (only the year).
	 */
	public function leavingExamYear() : string{
		return $this->year_of_leaving_exam;
	}
	
	/** Function name: admissionYear
	 *
	 * This is the getter for admission_year.
	 *
	 * @return string - The user's date of admission to the dormitory (only the year).
	 */
	public function admissionYear() : string{
		return $this->admission_year;
	}
	
	/** Function name: faculty
	 *
	 * This is the getter for faculty.
	 *
	 * @return Faculty - The user's faculty.
	 */
	public function faculty() : Faculty{
		return $this->faculty;
	}
	
	/** Function name: workshop
	 *
	 * This is the getter for workshop.
	 *
	 * @return Workshop - The user's workshop.
	 */
	public function workshop() : Workshop{
		return $this->workshop;
	}

}