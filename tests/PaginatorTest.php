<?php

use voku\helper\Paginator;

/**
 * PaginatorTest
 */
class PaginatorTest extends \PHPUnit\Framework\TestCase
{

  /**
   * @var Paginator
   */
  public $paginator;

  public function testPageLinksRaw()
  {
    $this->paginator->set_withLinkInCurrentLi(true);

    self::assertSame(
        [
            0  => [
                '?pid=1&pager=1' => true,
            ],
            1  => [
                '?pid=1&pager=2' => false,
            ],
            2  => [
                '?pid=1&pager=3' => false,
            ],
            3  => [
                '?pid=1&pager=4' => false,
            ],
            4  => [
                '?pid=1&pager=5' => false,
            ],
            5  => [
                '?pid=1&pager=6' => false,
            ],
            6  => [
                '?pid=1&pager=7' => false,
            ],
            7  => [
                '' => false,
            ],
            8  => [
                '?pid=1&pager=16' => false,
            ],
            9  => [
                '?pid=1&pager=17' => false,
            ],
            10 => [
                '?pid=1&pager=2' => false,
            ],
        ],
        $this->paginator->page_links_raw('?pid=1&')
    );

    $this->paginator->set_adjacent(8);

    self::assertSame(
        [
            0  => [
                '?pid=1&pager=1' => true,
            ],
            1  => [
                '?pid=1&pager=2' => false,
            ],
            2  => [
                '?pid=1&pager=3' => false,
            ],
            3  => [
                '?pid=1&pager=4' => false,
            ],
            4  => [
                '?pid=1&pager=5' => false,
            ],
            5  => [
                '?pid=1&pager=6' => false,
            ],
            6  => [
                '?pid=1&pager=7' => false,
            ],
            7  => [
                '?pid=1&pager=8' => false,
            ],
            8  => [
                '?pid=1&pager=9' => false,
            ],
            9  => [
                '?pid=1&pager=10' => false,
            ],
            10 => [
                '?pid=1&pager=11' => false,
            ],
            11 => [
                '?pid=1&pager=12' => false,
            ],
            12 => [
                '?pid=1&pager=13' => false,
            ],
            13 => [
                '?pid=1&pager=14' => false,
            ],
            14 => [
                '?pid=1&pager=15' => false,
            ],
            15 => [
                '?pid=1&pager=16' => false,
            ],
            16 => [
                '?pid=1&pager=17' => false,
            ],
            17 => [
                '?pid=1&pager=2' => false,
            ],
        ],
        $this->paginator->page_links_raw('?pid=1&')
    );

    $this->paginator->set_adjacent(2)->set_pageIdentifierFromGet(3);

    self::assertSame(
        [
            0  => [
                '?pid=1&pager=2' => false,
            ],
            1  => [
                '?pid=1&pager=1' => false,
            ],
            2  => [
                '?pid=1&pager=2' => false,
            ],
            3  => [
                '?pid=1&pager=3' => true,
            ],
            4  => [
                '?pid=1&pager=4' => false,
            ],
            5  => [
                '?pid=1&pager=5' => false,
            ],
            6  => [
                '?pid=1&pager=6' => false,
            ],
            7  => [
                '?pid=1&pager=7' => false,
            ],
            8  => [
                '' => false,
            ],
            9  => [
                '?pid=1&pager=16' => false,
            ],
            10 => [
                '?pid=1&pager=17' => false,
            ],
            11 => [
                '?pid=1&pager=4' => false,
            ],
        ],
        $this->paginator->page_links_raw('?pid=1&')
    );
  }

  public function testPageLinks()
  {
    $this->paginator->set_withLinkInCurrentLi(false);

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

    $this->paginator->set_pageIdentifierFromGet(4);

    self::assertSame(' LIMIT 18,6', $this->paginator->get_limit());
  }

  public function testGetSQLLimitRaw()
  {
    self::assertSame([0, 6], $this->paginator->get_limit_raw());

    $this->paginator->set_pageIdentifierFromGet(4);

    self::assertSame([18, 6], $this->paginator->get_limit_raw());
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
