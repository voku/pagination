<?php

use voku\helper\Paginator;

/**
 * PaginatorTest
 */
class PaginatorTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var Paginator
   */
  public $paginator;

  public function testPageLinks()
  {
    self::assertSame(
        '<ul class="pagination " data-pagination-current="1" data-pagination-prev="false" data-pagination-next="2" data-pagination-length="17"><li class="pagination--start">&laquo;</li><li class="current">1</li><li><a href="?pid=1&pager=2">2</a></li><li><a href="?pid=1&pager=3">3</a></li><li><a href="?pid=1&pager=4">4</a></li><li><a href="?pid=1&pager=5">5</a></li><li><a href="?pid=1&pager=6">6</a></li><li><a href="?pid=1&pager=7">7</a></li><li>&hellip;</li><li><a href="?pid=1&pager=16">16</a></li><li><a href="?pid=1&pager=17">17</a></li><li class="pagination--end"><a href="?pid=1&pager=2">&raquo;</a></li></ul>',
        $this->paginator->page_links('?pid=1&')
    );

    $this->paginator->set_adjacent(8);

    self::assertSame(
        '<ul class="pagination " data-pagination-current="1" data-pagination-prev="false" data-pagination-next="2" data-pagination-length="17"><li class="pagination--start">&laquo;</li><li class="current">1</li><li><a href="?pid=1&pager=2">2</a></li><li><a href="?pid=1&pager=3">3</a></li><li><a href="?pid=1&pager=4">4</a></li><li><a href="?pid=1&pager=5">5</a></li><li><a href="?pid=1&pager=6">6</a></li><li><a href="?pid=1&pager=7">7</a></li><li><a href="?pid=1&pager=8">8</a></li><li><a href="?pid=1&pager=9">9</a></li><li><a href="?pid=1&pager=10">10</a></li><li><a href="?pid=1&pager=11">11</a></li><li><a href="?pid=1&pager=12">12</a></li><li><a href="?pid=1&pager=13">13</a></li><li><a href="?pid=1&pager=14">14</a></li><li><a href="?pid=1&pager=15">15</a></li><li><a href="?pid=1&pager=16">16</a></li><li><a href="?pid=1&pager=17">17</a></li><li class="pagination--end"><a href="?pid=1&pager=2">&raquo;</a></li></ul>',
        $this->paginator->page_links('?pid=1&')
    );

    $this->paginator->set_adjacent(2);

    self::assertSame(
        '<ul class="pagination " data-pagination-current="1" data-pagination-prev="false" data-pagination-next="2" data-pagination-length="17"><li class="pagination--start">&laquo;</li><li class="current">1</li><li><a href="?pid=1&pager=2">2</a></li><li><a href="?pid=1&pager=3">3</a></li><li><a href="?pid=1&pager=4">4</a></li><li><a href="?pid=1&pager=5">5</a></li><li><a href="?pid=1&pager=6">6</a></li><li><a href="?pid=1&pager=7">7</a></li><li>&hellip;</li><li><a href="?pid=1&pager=16">16</a></li><li><a href="?pid=1&pager=17">17</a></li><li class="pagination--end"><a href="?pid=1&pager=2">&raquo;</a></li></ul>',
        $this->paginator->page_links('?pid=1&')
    );
  }

  public function testGetNextPrevLinks()
  {
    $this->paginator->set_pageIdentifierFromGet(1);

    self::assertSame(
        '<link rel="next" href="?pid=1&pager=2">',
        $this->paginator->getNextPrevLinks('?pid=1&')
    );

    //

    $this->paginator->set_pageIdentifierFromGet(2);

    self::assertSame(
        '<link rel="next" href="?pid=1&pager=3"><link rel="prev" href="?pid=1&pager=1">',
        $this->paginator->getNextPrevLinks('?pid=1&')
    );
  }

  public function testGetSQLLimit()
  {
    self::assertSame(' LIMIT 0,6', $this->paginator->get_limit());
  }

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->paginator = new Paginator(6, 'pager');
    $this->paginator->set_total(100);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }


}
