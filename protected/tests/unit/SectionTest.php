<?php

class SectionTest extends CDbTestCase {
	
	public $fixtures = array(
		'tags'=>'Tag',
		'sections'=>'Section',
	);
	
	/**
	 * @dataProvider getNewTagsProvider
	 */
	public function testGetNewTags($postData) {
		$_POST['tag'] = $postData;
		$tags = Section::model()->getNewTags();
		foreach($postData as $n => $datum) {
			if($n == 'section') {
				$section = Section::model()->findByPk($datum);
				$this->assertTrue(in_array($section->year, $tags));
				$this->assertTrue(in_array($section->course, $tags));
				$this->assertTrue(in_array($section->section, $tags));
			}
			else if($n < 5) {
				$tag = Tag::model()->findByPk($datum);
				$this->assertTrue(in_array($tag, $tags));
			}
			else if($n == 5) {
				$tag = Tag::model()->getTagByName($datum, Tag::TERM_LAB);
				$this->assertTrue(in_array($tag, $tags));
			}
			else if($n > 5) {
				$otherTagNames = preg_split('/\s*,\s*/', $datum);
				foreach($otherTagNames as $tagName) {
					$tag = Tag::model()->getTagByName($tagName);
					$this->assertTrue(in_array($tag, $tags));
				}
			}
		}
	}
	
	public function getNewTagsProvider() {
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
		$tags = array();
		foreach($this->tags as $n => $_) {
			if($this->tags($n)->parentId > 1) {
				$tags[] = $this->tags($n);
			}
		}
		$viewData = Section::model()->getViewData($tags);
		$this->assertEquals($viewData['section'], $this->sections('sample1')->id);
		$this->assertEquals($viewData[Tag::TERM_YEAR], $this->tags('sample1')->id);
		$this->assertEquals($viewData[Tag::TERM_COURSE], $this->tags('sample2')->id);
		$this->assertEquals($viewData[Tag::TERM_SECTION], $this->tags('sample3')->id);
		$this->assertEquals($viewData[Tag::TERM_LAB], $this->tags('sample4')->name);
		$this->assertEquals($viewData[Tag::TERM_OTHER], $this->tags('sample5')->name .', '. $this->tags('sample6')->name);
	}
}
