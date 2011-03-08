<?php

class TermTest extends CDbTestCase {
	
	public $fixtures = array(
		'terms'=>'Term',
		'ImportSessionTerm',
	);
	
	/**
	 * @dataProvider getTermProvider
	 */
	public function testGetTerm($termName, $termTypeId = Term::TERM_OTHER) {
		$termExists = Term::model()->exists('name = :name', array(':name'=>$termName));
		$term = Term::model()->getTermByName($termName, $termTypeId);
		$this->assertEquals($term->name, $termName);
		if(!$termExists) {
			$this->assertEquals($term->parentId, $termTypeId);
		}
	}
	
	public function getTermProvider() {
		return array(
			array('2010-2011', Term::TERM_YEAR),
			array('CS21a', Term::TERM_COURSE),
			array('A', Term::TERM_SECTION),
			array('Lab1', Term::TERM_LAB),
			array('Lab2', Term::TERM_LAB),
			array('weeee'),
			array('tags'),
			array('this'),
			array('does'),
			array('not'),
			array('exist'),
		);
	}
	
	/**
	 * @dataProvider getTermsProvider
	 */
	public function testGetTerms($termTypeId) {
		$terms = Term::model()->getTerms($termTypeId);
		foreach($terms as $term) {
			$this->assertEquals($term->parentId, $termTypeId);
		}
	}
	
	public function getTermsProvider() {
		return array(
			array(Term::TERM_YEAR),
			array(Term::TERM_COURSE),
			array(Term::TERM_SECTION),
			array(Term::TERM_LAB),
			array(Term::TERM_OTHER),
		);
	}
	
	/**
	 * @dataProvider getNewTermsProvider
	 * @depends testGetTerm
	 */
	public function testGetNewTerms($postData) {
		$_POST['term'] = $postData;
		$terms = Term::model()->getNewTerms();
		foreach($postData as $n => $datum) {
			if($n < 5) {
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
				2 => $this->terms('sample1')->id,
				3 => $this->terms('sample2')->id,
				4 => $this->terms('sample3')->id,
				5 => 'Lab1',
				6 => 'weeee, tags',
			)),
			array(array(
				3 => $this->terms('sample2')->id,
				4 => $this->terms('sample3')->id,
			)),
			array(array(
				2 => $this->terms('sample1')->id,
				3 => $this->terms('sample2')->id,
			)),
			array(array(
				2 => $this->terms('sample1')->id,
				4 => $this->terms('sample3')->id,
			)),
			array(array(
				2 => $this->terms('sample1')->id,
			)),
			array(array(
				3 => $this->terms('sample2')->id,
			)),
			array(array(
				4 => $this->terms('sample3')->id,
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
		$viewData = Term::model()->getViewData($terms);
		$this->assertEquals($viewData[Term::TERM_YEAR], $this->terms('sample1')->id);
		$this->assertEquals($viewData[Term::TERM_COURSE], $this->terms('sample2')->id);
		$this->assertEquals($viewData[Term::TERM_SECTION], $this->terms('sample3')->id);
		$this->assertEquals($viewData[Term::TERM_LAB], $this->terms('sample4')->name);
		$this->assertEquals($viewData[Term::TERM_OTHER], $this->terms('sample5')->name .', '. $this->terms('sample6')->name);
	}
	
	public function testImportSessionTerm() {
		$importSessionModel = new ImportSession;
		$importSessionModel->newTerms = array(
			$this->terms('sample1'),
			$this->terms('sample2'),
			$this->terms('sample3'),
		);
		$this->assertTrue($importSessionModel->save());
		$this->assertEquals(count($importSessionModel->terms), 3);
		$this->assertTrue(in_array($this->terms('sample1'), $importSessionModel->terms));
		$this->assertTrue(in_array($this->terms('sample2'), $importSessionModel->terms));
		$this->assertTrue(in_array($this->terms('sample3'), $importSessionModel->terms));
		
		$importSessionModel->newTerms = array(
			$this->terms('sample5'),
			$this->terms('sample3'),
		);
		$this->assertTrue($importSessionModel->save());
		$importSessionModel->refresh();
		$this->assertEquals(count($importSessionModel->terms), 2);
		$this->assertTrue(!in_array($this->terms('sample1'), $importSessionModel->terms));
		$this->assertTrue(!in_array($this->terms('sample2'), $importSessionModel->terms));
		$this->assertTrue(in_array($this->terms('sample3'), $importSessionModel->terms));
		$this->assertTrue(in_array($this->terms('sample5'), $importSessionModel->terms));
	}
}
