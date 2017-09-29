<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\LanguageExam;

/** Class name: LanguageExamTest
 *
 * This class is the PHPUnit test for the Data\LanguageExam data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LanguageExamTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_language_exam
	 *
	 * This function is testing the LanguageExam data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_language_exam(){
		$languageExam = new LanguageExam(1, "spanyol", "B2");
		$this->assertEquals(1, $languageExam->id());
		$this->assertEquals("spanyol", $languageExam->language());
		$this->assertFalse($languageExam->resolved());
		$this->assertEquals("B2", $languageExam->level());
		$this->assertCount(0, $languageExam->pictures());
		
		$languageExam = new LanguageExam(200, "spanyol", "C2", false, ["halacska"]);
		$this->assertEquals(200, $languageExam->id());
		$this->assertEquals("spanyol", $languageExam->language());
		$this->assertFalse($languageExam->resolved());
		$this->assertEquals("C2", $languageExam->level());
		$this->assertEquals(["halacska"], $languageExam->pictures());
		
		$languageExam = new LanguageExam(12, "spanyol", "B1", true, ["almafa/a/kertben", "hal"]);
		$this->assertEquals(12, $languageExam->id());
		$this->assertEquals("spanyol", $languageExam->language());
		$this->assertTrue($languageExam->resolved());
		$this->assertEquals("B1", $languageExam->level());
		$this->assertEquals(["almafa/a/kertben", "hal"], $languageExam->pictures());
		
		$languageExam = new LanguageExam("10", "spanyol", "A1", true, []);
		$this->assertEquals(10, $languageExam->id());
		$this->assertEquals("spanyol", $languageExam->language());
		$this->assertTrue($languageExam->resolved());
		$this->assertEquals("A1", $languageExam->level());
		$this->assertCount(0, $languageExam->pictures());
	}

	/** Function name: test_language_exam_attr
	 *
	 * This function is testing the LanguageExam data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_language_exam_attr(){
		$this->assertClassHasAttribute('id', LanguageExam::class);
		$this->assertClassHasAttribute('language', LanguageExam::class);
		$this->assertClassHasAttribute('resolved', LanguageExam::class);
		$this->assertClassHasAttribute('level', LanguageExam::class);
		$this->assertClassHasAttribute('pictures', LanguageExam::class);
	}
}