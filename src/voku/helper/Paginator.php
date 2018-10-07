<?php

declare(strict_types=1);

namespace voku\helper;

/**
 * Paginator: PHP Pagination Class
 *
 * @author David Carr - dave@daveismyname.com - http://www.daveismyname.com
 * @author Lars Moelleken - lars@moelleken.org - http://www.moelleken.org
 *
 */
class Paginator
{

  /**
   * the current page-id from _GET
   *
   * @var int
   */
  private $_pageIdentifierFromGet;

  /**
   * pages per page in the pager
   *
   * @var int
   */
  private $_perPage;

  /**
   * @var string the get-parameter for the pager
   *
   * e.g.: "mypager=2" -> then use "mypager" here
   */
  private $_instance;

  /**
   * @var int
   */
  private $_totalRows = 0;

  /**
   * @var string
   */
  private $_paginatorUlCssClass = '';

  /**
   * @var string
   */
  private $_paginatorStartCssClass = 'pagination--start';

  /**
   * @var string
   */
  private $_paginatorEndCssClass = 'pagination--end';

  /**
   * @var string
   */
  private $_paginatorStartChar = '&laquo;';

  /**
   * @var string
   */
  private $_paginatorEndChar = '&raquo;';

  /**
   * @var bool
   */
  private $_withLinkInCurrentLi = false;

  /**
   * @var int
   */
  private $_adjacent = 2;

  /**
   * __construct
   *
   * @param int    $perPage
   * @param string $instance
   */
  public function __construct(int $perPage, string $instance)
  {
    $this->_instance = $instance;
    $this->_perPage = $perPage;

    $this->set_instance();
  }

  /**
   * @param string $path
   * @param int    $counter
   *
   * @return string
   */
  private function createLiCurrentOrNot(string $path, int $counter): string
  {
    // init
    $html = '';
    $textAndOrLink = '<a href="' . $path . $this->_instance . '=' . $counter . '">' . $counter . '</a>';

    if ($this->_withLinkInCurrentLi === false) {
      $currentTextAndOrLink = $counter;
    } else {
      $currentTextAndOrLink = $textAndOrLink;
    }

    if ($counter == $this->_pageIdentifierFromGet) {
      $html .= '<li class="current">' . $currentTextAndOrLink . '</li>';
    } else {
      $html .= '<li>' . $textAndOrLink . '</li>';
    }

    return $html;
  }

  /**
   * @param string $path
   * @param int    $counter
   *
   * @return array
   */
  private function createLiCurrentOrNotRaw(string $path, int $counter): array
  {
    $textAndOrLink = $path . $this->_instance . '=' . $counter;

    if ($this->_withLinkInCurrentLi === false) {
      $currentTextAndOrLink = $counter;
    } else {
      $currentTextAndOrLink = $textAndOrLink;
    }

    if ($counter == $this->_pageIdentifierFromGet) {
      return [$currentTextAndOrLink => true];
    }

    return [$textAndOrLink => false];
  }

  /**
   * @param string $path
   *
   * @return string
   */
  private function createLiFirstAndSecond(string $path): string
  {
    $html = '';

    $html .= '<li><a href="' . $path . $this->_instance . '=1">1</a></li>';
    $html .= '<li><a href="' . $path . $this->_instance . '=2">2</a></li>';

    return $html;
  }

  /**
   * @param string $path
   * @param array  $pagination
   *
   * @return void
   */
  private function createLiFirstAndSecondRaw(string $path, array &$pagination)
  {
    $pagination[] = [$path . $this->_instance . '=1' => false];
    $pagination[] = [$path . $this->_instance . '=2' => false];
  }

  /**
   * get next- / prev meta-links
   *
   * @param string $path
   *
   * @return string
   */
  public function getNextPrevLinks(string $path = '?'): string
  {
    // init
    $nextLink = '';
    $prevLink = '';

    $prev = $this->_pageIdentifierFromGet - 1;
    $next = $this->_pageIdentifierFromGet + 1;

    $lastpage = (int)ceil($this->_totalRows / $this->_perPage);

    if ($lastpage > 1) {

      if ($this->_pageIdentifierFromGet > 1) {
        $prevLink = '<link rel="prev" href="' . $path . $this->_instance . '=' . $prev . '">';
      }

      if ($this->_pageIdentifierFromGet < $lastpage) {
        $nextLink = '<link rel="next" href="' . $path . $this->_instance . '=' . $next . '">';
      }

    }

    return $nextLink . $prevLink;
  }

  /**
   * returns the limit for the data source
   *
   * @return string LIMIT-String for an SQL-query
   */
  public function get_limit(): string
  {
    return ' LIMIT ' . $this->get_start() . ',' . $this->_perPage;
  }

  /**
   * returns the limit for the data source as array [start, end]
   *
   * @return array LIMIT-array for e.g. SQL-queries
   */
  public function get_limit_raw(): array
  {
    return [$this->get_start(), $this->_perPage];
  }

  /**
   * creates the starting point for get_limit()
   *
   * @return int
   */
  public function get_start(): int
  {
    return ($this->_pageIdentifierFromGet * $this->_perPage) - $this->_perPage;
  }

  /**
   * create links for the paginator
   *
   * @param string $path
   *
   * @return string
   */
  public function page_links(string $path = '?'): string
  {
    // init
    $counter = 0;
    $pagination = '';

    $prev = $this->_pageIdentifierFromGet - 1;
    $next = $this->_pageIdentifierFromGet + 1;

    $lastpage = (int)ceil($this->_totalRows / $this->_perPage);
    $tmpSave = $lastpage - 1;

    if ($this->_pageIdentifierFromGet < $lastpage) {
      $nextDataAttribute = $next;
    } else {
      $nextDataAttribute = 'false';
    }

    if ($this->_pageIdentifierFromGet > 1) {
      $prevDataAttribute = $prev;
    } else {
      $prevDataAttribute = 'false';
    }

    if ($lastpage > 1) {
      $pagination .= '<ul class="pagination ' . $this->_paginatorUlCssClass . '" data-pagination-current="' . $this->_pageIdentifierFromGet . '" data-pagination-prev="' . $prevDataAttribute . '" data-pagination-next="' . $nextDataAttribute . '" data-pagination-length="' . $lastpage . '">';

      if ($this->_pageIdentifierFromGet > 1) {
        $pagination .= '<li class="' . $this->_paginatorStartCssClass . '"><a href="' . $path . $this->_instance . '=' . $prev . '">' . $this->_paginatorStartChar . '</a></li>';
      } else {
        $pagination .= '<li class="' . $this->_paginatorStartCssClass . '">' . $this->_paginatorStartChar . '</li>';
      }

      if ($lastpage < 7 + ($this->_adjacent * 2)) {

        for ($counter = 1; $counter <= $lastpage; $counter++) {
          $pagination .= $this->createLiCurrentOrNot($path, $counter);
        }

      } elseif ($this->_pageIdentifierFromGet < 5 && ($lastpage > 5 + ($this->_adjacent * 2))) {

        if ($this->_pageIdentifierFromGet < 1 + ($this->_adjacent * 2)) {
          for ($counter = 1; $counter < 4 + ($this->_adjacent * 2); $counter++) {
            $pagination .= $this->createLiCurrentOrNot($path, $counter);
          }
        }

        $pagination .= '<li>&hellip;</li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $tmpSave . '">' . $tmpSave . '</a></li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $lastpage . '">' . $lastpage . '</a></li>';

      } elseif ($lastpage - ($this->_adjacent * 2) > $this->_pageIdentifierFromGet && $this->_pageIdentifierFromGet > ($this->_adjacent * 2)) {

        $pagination .= $this->createLiFirstAndSecond($path);

        if ($this->_pageIdentifierFromGet != 5) {
          $pagination .= '<li>&hellip;</li>';
        }

        for ($counter = $this->_pageIdentifierFromGet - $this->_adjacent;
             $counter <= $this->_pageIdentifierFromGet + $this->_adjacent; $counter++) {
          $pagination .= $this->createLiCurrentOrNot($path, $counter);
        }

        $pagination .= '<li>&hellip;</li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $tmpSave . '">' . $tmpSave . '</a></li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $lastpage . '">' . $lastpage . '</a></li>';

      } else {

        $pagination .= $this->createLiFirstAndSecond($path);

        $pagination .= '<li>&hellip;</li>';

        for ($counter = $lastpage - (2 + ($this->_adjacent * 2)); $counter <= $lastpage; $counter++) {
          $pagination .= $this->createLiCurrentOrNot($path, $counter);
        }

      }

      if ($this->_pageIdentifierFromGet < $counter - 1) {
        $pagination .= '<li class="' . $this->_paginatorEndCssClass . '"><a href="' . $path . $this->_instance . '=' . $next . '">' . $this->_paginatorEndChar . '</a></li>';
      } else {
        $pagination .= '<li class="' . $this->_paginatorEndCssClass . '">' . $this->_paginatorEndChar . '</li>';
      }

      $pagination .= '</ul>';
    }

    return $pagination;
  }

  /**
   * create links as array for the paginator
   *
   * @param string $path
   *
   * @return array
   */
  public function page_links_raw(string $path = '?'): array
  {
    // init
    $counter = 0;
    $pagination = [];

    $prev = $this->_pageIdentifierFromGet - 1;
    $next = $this->_pageIdentifierFromGet + 1;

    $lastpage = (int)ceil($this->_totalRows / $this->_perPage);
    $tmpSave = $lastpage - 1;

    if ($lastpage > 1) {

      if ($this->_pageIdentifierFromGet > 1) {
        $pagination[] = [$path . $this->_instance . '=' . $prev => false];
      }

      if ($lastpage < 7 + ($this->_adjacent * 2)) {

        for ($counter = 1; $counter <= $lastpage; $counter++) {
          $pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
        }

      } elseif ($this->_pageIdentifierFromGet < 5 && ($lastpage > 5 + ($this->_adjacent * 2))) {

        if ($this->_pageIdentifierFromGet < 1 + ($this->_adjacent * 2)) {
          for ($counter = 1; $counter < 4 + ($this->_adjacent * 2); $counter++) {
            $pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
          }
        }

        $pagination[] = ['' => false];
        $pagination[] = [$path . $this->_instance . '=' . $tmpSave => false];
        $pagination[] = [$path . $this->_instance . '=' . $lastpage => false];

      } elseif ($lastpage - ($this->_adjacent * 2) > $this->_pageIdentifierFromGet && $this->_pageIdentifierFromGet > ($this->_adjacent * 2)) {

        $this->createLiFirstAndSecondRaw($path, $pagination);

        if ($this->_pageIdentifierFromGet != 5) {
          $pagination[] = ['' => false];
        }

        for ($counter = $this->_pageIdentifierFromGet - $this->_adjacent;
             $counter <= $this->_pageIdentifierFromGet + $this->_adjacent; $counter++) {
          $pagination[] = $this->createLiCurrentOrNotRaw($path, $counter);
        }

        $pagination[] = ['' => false];
        $pagination[] = [$path . $this->_instance . '=' . $tmpSave => false];
        $pagination[] = [$path . $this->_instance . '=' . $lastpage => false];

      } else {

        $this->createLiFirstAndSecondRaw($path, $pagination);

        $pagination[] = ['' => false];

        for ($counter = $lastpage - (2 + ($this->_adjacent * 2)); $counter <= $lastpage; $counter++) {
          $pagination[] = $this->createLiCurrentOrNot($path, $counter);
        }

      }

      if ($this->_pageIdentifierFromGet < $counter - 1) {
        $pagination[] = [$path . $this->_instance . '=' . $next => false];
      }

    }

    return $pagination;
  }

  /**
   * set the "adjacent"
   *
   * @param int $adjacent
   *
   * @return $this
   */
  public function set_adjacent(int $adjacent): self
  {
    $this->_adjacent = $adjacent;

    return $this;
  }

  /**
   * set the object parameter
   */
  private function set_instance()
  {
    if (isset($_GET[$this->_instance])) {
      $this->_pageIdentifierFromGet = (int)$_GET[$this->_instance];
    }

    if (!$this->_pageIdentifierFromGet) {
      $this->_pageIdentifierFromGet = 1;
    }
  }

  /**
   * set the "pageIdentifierFromGet"
   *
   * @param int $pageId
   *
   * @return $this
   */
  public function set_pageIdentifierFromGet(int $pageId): self
  {
    $this->_pageIdentifierFromGet = $pageId;

    return $this;
  }

  /**
   * set the "paginatorEndChar"
   *
   * @param string $string
   *
   * @return $this
   */
  public function set_paginatorEndChar(string $string): self
  {
    $this->_paginatorEndChar = $string;

    return $this;
  }

  /**
   * set the "paginatorEndCssClass"
   *
   * @param string $string
   *
   * @return $this
   */
  public function set_paginatorEndCssClass(string $string): self
  {
    $this->_paginatorEndCssClass = $string;

    return $this;
  }

  /**
   * set the "paginatorStartChar"
   *
   * @param string $string
   *
   * @return $this
   */
  public function set_paginatorStartChar(string $string): self
  {
    $this->_paginatorStartChar = $string;

    return $this;
  }

  /**
   * set the "paginatorStartCssClass"
   *
   * @param string $string
   *
   * @return $this
   */
  public function set_paginatorStartCssClass(string $string): self
  {
    $this->_paginatorStartCssClass = $string;

    return $this;
  }

  /**
   * set the "paginatorUlCssClass"
   *
   * @param string $string
   *
   * @return $this
   */
  public function set_paginatorUlCssClass(string $string): self
  {
    $this->_paginatorUlCssClass = $string;

    return $this;
  }

  /**
   * set the "totalRows"
   *
   * @param int $totalRows
   *
   * @return $this
   */
  public function set_total(int $totalRows): self
  {
    $this->_totalRows = $totalRows;

    return $this;
  }

  /**
   * set the "withLinkInCurrentLi"
   *
   * @param bool $bool
   *
   * @return $this
   */
  public function set_withLinkInCurrentLi(bool $bool): self
  {
    $this->_withLinkInCurrentLi = $bool;

    return $this;
  }
}
