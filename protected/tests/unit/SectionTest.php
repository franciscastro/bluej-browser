<?php

class SectionTest extends CDbTestCase {
  
  public $fixtures = array(
		'terms'=>'Term',
    'sections'=>'Section',
	);
  
  /**
	 * @dataProvider getNewTermsProvider
	 */
  public function testGetNewTerms($postData) {
    $_POST['term'] = $postData;
    $terms = Section::model()->getNewTerms();
    foreach($postData as $n => $datum) {
      if($n == 'section') {
        $section = Section::model()->findByPk($datum);
        $this->assertTrue(in_array($section->year, $terms));
        $this->assertTrue(in_array($section->course, $terms));
        $this->assertTrue(in_array($section->section, $terms));
      }
      else if($n < 5) {
        $term = Term::model()->findByPk($datum);
        $this->assertTrue(in_array($term, $terms));
      }
      else if($n == 5) {
        $term = Term::model()->getTermByName($datum, Term::TERM_LAB);
        $this->assertTrue(in_array($term, $terms));
      }
      else if($n > 5) {
        $otherTermNames = preg_split('/\s*,\s*/', $datum);
        foreach($otherTermNames as $termName) {
          $term = Term::model()->getTermByName($termName);
          $this->assertTrue(in_array($term, $terms));
        }
      }
    }
  }
  
  public function getNewTermsProvider() {
    $this->setUp();
    return array(
      array(array(
        'section' => $this->sections('sample1')->id,
        5 => 'Lab1',
        6 => 'weeee, tags',
      )),
    );
  }
  
  public function testGetViewData() {
    $terms = array();
    foreach($this->terms as $n => $_) {
      if($this->terms($n)->parentId > 1) {
        $terms[] = $this->terms($n);
      }
    }
    $viewData = Section::model()->getViewData($terms);
    $this->assertEquals($viewData['section'], $this->sections('sample1')->id);
    $this->assertEquals($viewData[Term::TERM_YEAR], $this->terms('sample1')->id);
    $this->assertEquals($viewData[Term::TERM_COURSE], $this->terms('sample2')->id);
    $this->assertEquals($viewData[Term::TERM_SECTION], $this->terms('sample3')->id);
    $this->assertEquals($viewData[Term::TERM_LAB], $this->terms('sample4')->name);
    $this->assertEquals($viewData[Term::TERM_OTHER], $this->terms('sample5')->name .', '. $this->terms('sample6')->name);
  }
}
