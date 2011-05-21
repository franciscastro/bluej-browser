<?php

class TagTest extends CDbTestCase {
	
	public $fixtures = array(
		'tags'=>'Tag',
		'LogSessionTag',
	);
	
	/**
	 * @dataProvider getTagProvider
	 */
	public function testGetTag($tagName, $tagTypeId = Tag::TERM_OTHER) {
		$tagExists = Tag::model()->exists('name = :name', array(':name'=>$tagName));
		$tag = Tag::model()->getTagByName($tagName, $tagTypeId);
		$this->assertEquals($tag->name, $tagName);
		if(!$tagExists) {
			$this->assertEquals($tag->parentId, $tagTypeId);
		}
	}
	
	public function getTagProvider() {
		return array(
			array('2010-2011', Tag::TERM_YEAR),
			array('CS21a', Tag::TERM_COURSE),
			array('A', Tag::TERM_SECTION),
			array('Lab1', Tag::TERM_LAB),
			array('Lab2', Tag::TERM_LAB),
			array('weeee'),
			array('tags'),
			array('this'),
			array('does'),
			array('not'),
			array('exist'),
		);
	}
	
	/**
	 * @dataProvider getTagsProvider
	 */
	public function testGetTags($tagTypeId) {
		$tags = Tag::model()->getTags($tagTypeId);
		foreach($tags as $tag) {
			$this->assertEquals($tag->parentId, $tagTypeId);
		}
	}
	
	public function getTagsProvider() {
		return array(
			array(Tag::TERM_YEAR),
			array(Tag::TERM_COURSE),
			array(Tag::TERM_SECTION),
			array(Tag::TERM_LAB),
			array(Tag::TERM_OTHER),
		);
	}
	
	/**
	 * @dataProvider getNewTagsProvider
	 * @depends testGetTag
	 */
	public function testGetNewTags($postData) {
		$_POST['tag'] = $postData;
		$tags = Tag::model()->getNewTags();
		foreach($postData as $n => $datum) {
			if($n < 5) {
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
				2 => $this->tags('sample1')->id,
				3 => $this->tags('sample2')->id,
				4 => $this->tags('sample3')->id,
				5 => 'Lab1',
				6 => 'weeee, tags',
			)),
			array(array(
				3 => $this->tags('sample2')->id,
				4 => $this->tags('sample3')->id,
			)),
			array(array(
				2 => $this->tags('sample1')->id,
				3 => $this->tags('sample2')->id,
			)),
			array(array(
				2 => $this->tags('sample1')->id,
				4 => $this->tags('sample3')->id,
			)),
			array(array(
				2 => $this->tags('sample1')->id,
			)),
			array(array(
				3 => $this->tags('sample2')->id,
			)),
			array(array(
				4 => $this->tags('sample3')->id,
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
		$viewData = Tag::model()->getViewData($tags);
		$this->assertEquals($viewData[Tag::TERM_YEAR], $this->tags('sample1')->id);
		$this->assertEquals($viewData[Tag::TERM_COURSE], $this->tags('sample2')->id);
		$this->assertEquals($viewData[Tag::TERM_SECTION], $this->tags('sample3')->id);
		$this->assertEquals($viewData[Tag::TERM_LAB], $this->tags('sample4')->name);
		$this->assertEquals($viewData[Tag::TERM_OTHER], $this->tags('sample5')->name .', '. $this->tags('sample6')->name);
	}
	
	public function testLogSessionTag() {
		$logSessionModel = new LogSession;
		$logSessionModel->newTags = array(
			$this->tags('sample1'),
			$this->tags('sample2'),
			$this->tags('sample3'),
		);
		$this->assertTrue($logSessionModel->save());
		$this->assertEquals(count($logSessionModel->tags), 3);
		$this->assertTrue(in_array($this->tags('sample1'), $logSessionModel->tags));
		$this->assertTrue(in_array($this->tags('sample2'), $logSessionModel->tags));
		$this->assertTrue(in_array($this->tags('sample3'), $logSessionModel->tags));
		
		$logSessionModel->newTags = array(
			$this->tags('sample5'),
			$this->tags('sample3'),
		);
		$this->assertTrue($logSessionModel->save());
		$logSessionModel->refresh();
		$this->assertEquals(count($logSessionModel->tags), 2);
		$this->assertTrue(!in_array($this->tags('sample1'), $logSessionModel->tags));
		$this->assertTrue(!in_array($this->tags('sample2'), $logSessionModel->tags));
		$this->assertTrue(in_array($this->tags('sample3'), $logSessionModel->tags));
		$this->assertTrue(in_array($this->tags('sample5'), $logSessionModel->tags));
	}
}
