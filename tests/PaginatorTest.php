<?php

use voku\helper\Paginator;

/**
 * PaginatorTest
 */
class PaginatorTest extends PHPUnit_Framework_TestCase
{

  /**
   *
   * @var Paginator
   */
  public $paginator;

  /**
   * testPageOffline
   */
  public function testPageLinks()
  {
    self::assertEquals(
        '<ul class="pagination"><li class="pagination--start">&laquo;</li><li class="current">1</li><li><a href="?pid=1&pager=2">2</a></li><li><a href="?pid=1&pager=3">3</a></li><li><a href="?pid=1&pager=4">4</a></li><li class="pagination--end"><a href="?pid=1&pager=2">&raquo;</a></li></ul>',
        $this->paginator->page_links('?pid=1&')
    );
  }

  public function testGetSQLLimit()
  {
    self::assertEquals($this->paginator->get_limit(), ' LIMIT 0,4');
  }

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->paginator = new Paginator(4, 'pager');
    $this->paginator->set_total(16);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }


}
