<?php

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
   * __construct
   *
   * @param int    $perPage
   * @param string $instance
   */
  public function __construct($perPage, $instance)
  {
    $this->_instance = (string) $instance;
    $this->_perPage = (int) $perPage;
    $this->set_instance();
  }

  /**
   * sets the object parameter
   */
  private function set_instance()
  {
    $this->_pageIdentifierFromGet = isset($_GET[$this->_instance]) ? $_GET[$this->_instance] : '';
    $this->_pageIdentifierFromGet = ($this->_pageIdentifierFromGet == 0 ? 1 : $this->_pageIdentifierFromGet);
  }

  /**
   * set the "totalRows"
   *
   * @param int $_totalRows
   */
  public function set_total($_totalRows)
  {
    $this->_totalRows = (int)$_totalRows;
  }

  /**
   * set the "withLinkInCurrentLi"
   *
   * @param bool $bool
   */
  public function set_withLinkInCurrentLi($bool)
  {
    $this->_withLinkInCurrentLi = (bool)$bool;
  }

  /**
   * set the "paginatorStartCssClass"
   *
   * @param $string
   */
  public function set_paginatorStartCssClass($string)
  {
    $this->_paginatorStartCssClass = $string;
  }

  /**
   * set the "paginatorEndCssClass"
   *
   * @param $string
   */
  public function set_paginatorEndCssClass($string)
  {
    $this->_paginatorEndCssClass = $string;
  }
  /**
   * set the "paginatorStartChar"
   *
   * @param $string
   */
  public function set_paginatorStartChar($string)
  {
    $this->_paginatorStartChar = $string;
  }

  /**
   * set the "paginatorEndChar"
   *
   * @param $string
   */
  public function set_paginatorEndChar($string)
  {
    $this->_paginatorEndChar = $string;
  }

  /**
   * returns the limit for the data source
   *
   * @return string LIMIT-String for a SQL-Query
   */
  public function get_limit()
  {
    return ' LIMIT ' . (int)$this->get_start() . ',' . (int)$this->_perPage;
  }

  /**
   * creates the starting point for get_limit()
   *
   * @return int
   */
  public function get_start()
  {
    return ($this->_pageIdentifierFromGet * $this->_perPage) - $this->_perPage;
  }

  /**
   * get next- / prev meta-links
   *
   * @param string $path
   *
   * @return string
   */
  public function getNextPrevLinks($path = '?')
  {
    // init
    $nextLink = '';
    $prevLink = '';

    $prev = $this->_pageIdentifierFromGet - 1;
    $next = $this->_pageIdentifierFromGet + 1;

    $lastpage = ceil($this->_totalRows / $this->_perPage);

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
   * create links for the paginator
   *
   * @param string $path
   *
   * @return string
   */
  public function page_links($path = '?')
  {
    // init
    $counter = 0;
    $adjacents = 2;
    $pagination = '';

    $prev = $this->_pageIdentifierFromGet - 1;
    $next = $this->_pageIdentifierFromGet + 1;

    $lastpage = ceil($this->_totalRows / $this->_perPage);
    $tmpSave = $lastpage - 1;

    if ($lastpage > 1) {
      $pagination .= '<ul class="pagination">';

      if ($this->_pageIdentifierFromGet > 1) {
        $pagination .= '<li class="' . $this->_paginatorStartCssClass . '"><a href="' . $path . $this->_instance . '=' . $prev . '">' . $this->_paginatorStartChar . '</a></li>';
      } else {
        $pagination .= '<li class="' . $this->_paginatorStartCssClass . '">' . $this->_paginatorStartChar . '</li>';
      }

      if ($lastpage < 7 + ($adjacents * 2)) {

        for ($counter = 1; $counter <= $lastpage; $counter++) {
          $pagination .= $this->createLiCurrentOrNot($path, $counter);
        }

      } elseif ($this->_pageIdentifierFromGet < 5 && ($lastpage > 5 + ($adjacents * 2))) {

        if ($this->_pageIdentifierFromGet < 1 + ($adjacents * 2)) {
          for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
            $pagination .= $this->createLiCurrentOrNot($path, $counter);
          }
        }

        $pagination .= '<li>&hellip;</li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $tmpSave . '">' . $tmpSave . '</a></li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $lastpage . '">' . $lastpage . '</a></li>';

      } elseif ($lastpage - ($adjacents * 2) > $this->_pageIdentifierFromGet && $this->_pageIdentifierFromGet > ($adjacents * 2)) {

        $pagination .= $this->createLiFirstAndSecond($path);

        if ($this->_pageIdentifierFromGet != 5) {
          $pagination .= '<li>&hellip;</li>';
        }

        for ($counter = $this->_pageIdentifierFromGet - $adjacents; $counter <= $this->_pageIdentifierFromGet + $adjacents; $counter++) {
          $pagination .= $this->createLiCurrentOrNot($path, $counter);
        }

        $pagination .= '<li>&hellip;</li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $tmpSave . '">' . $tmpSave . '</a></li>';
        $pagination .= '<li><a href="' . $path . $this->_instance . '=' . $lastpage . '">' . $lastpage . '</a></li>';

      } else {

        $pagination .= $this->createLiFirstAndSecond($path);

        $pagination .= '<li>&hellip;</li>';

        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
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
   * @param string $path
   *
   * @return string
   */
  private function createLiFirstAndSecond($path)
  {
    $html = '';

    $html .= '<li><a href="' . $path . $this->_instance . '=1">1</a></li>';
    $html .= '<li><a href="' . $path . $this->_instance . '=2">2</a></li>';

    return $html;
  }

  /**
   * @param string $path
   * @param int    $counter
   *
   * @return string
   */
  private function createLiCurrentOrNot($path, $counter)
  {
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
}
