<?

 /**
  * @package ALL
  */

/**
 * Организует постраничный вывод
 * @uses Select
 * @uses outTree
 *

 * @version 1.03 - 01.08.2007 14:45
 *
 * .01 конструктор Pager вызывает метод initBorder() - интерфейс не изменился <br>
 * .01 добавлен класс PagerQuery extends Pager <br>
 * .02 исправлен глюк с выставлением на нужную страницу по колонке <br>
 * .03 нулевая страница -  та, что по умолчанию (без /cp/0)
 *
 */
 class Pager {
   var  $allCount = 0,
        $table,       //таблица в которой производить выборку
        $count,       //число записей на страницу
        $jumpValue = null,   //значение в колонке на которое надо перейти
        $curPage,
        $field;

   /**
    * запрос
    *
    * @var Select
    */
   var $r;

   /**
    * c какой позиции стартовать
    *
    * @var int
    */
   var $startIndex = 0;

   /**
    * ветка типа Pager
    *
    * @var outTree
    */
   var $ot;


   /**
   * @param Db $_db подключение к базе данных
   * @param string $_table имя таблицы
   * @param int $_count количество записей на страницу
   * @param int $_curPage какую страницу показывать
   * @param mixed $_jumpValue значение поля записи, которую отображать (приоритетней $_curPage)
   * @param string $_where условия
   * @param string $_order порядок
   * @param string $_field имя поля, со значением $_jumpValue которого проводится сравнение.
   * @return Pager
   */
   function Pager($_db,$_table,$_count = 10,$_curPage = 0,$_jumpValue,$_where,$_order,$_field='id',$myquery='') {
      $this->db = $_db;
      $this->table = $_table;
      $this->count = $_count;
      $this->curPage = $_curPage;
      $this->jumpValue = $_jumpValue;
      $this->field = $_field;
      if ($myquery!=='') $r = new Select($this->db,$myquery);
      //echo 'select * from '.$this->table.($_where ? ' where '.$_where: '').($_order ? ' order by '.$_order: '');
      else $r = new Select($this->db,'select * from '.$this->table.($_where ? ' where '.$_where: '').($_order ? ' order by '.$_order: ''));
             $this->r = &$r;
      $this->initBorder();
   }

   /**
    * выставляет границы запросу
    */
   function initBorder() {
             $r = &$this->r;
      $this->allCount = $r->num_rows;
      if (isset($this->jumpValue)) {
          while ($r->next_row()) {

             if ($this->jumpValue==$r->result($this->field) ) {
                $this->curPage=intval($r->result_row/$this->count);
                $this->curLine=$r->result_row%$this->count;
                break;
             }
          }
       }

       if ($this->allCount) {
            if ($this->curPage > ($curpage = floor(($this->allCount-1)/$this->count)) )
                    $this->curPage = $curpage;
                if ($this->curPage<0)
                        $this->curPage=0;

            $this->startIndex = $r->result_row = $this->curPage*$this->count-1;
            $r->end = $this->endIndex = $this->startIndex+$this->count;
       }
   }

    /**
     * инициализирует поля типа Pager в $ot
     * @param string $href адрес корневой ссылки
     * @param bool $SA выставлять ссылку текущей странице
     * @param bool $asGET постраничный просмотр через запрос GET  (а не ЧПУ) - нужен в BACK
     * @return bool проиницализировал или нет
     */
    function initPAGER($href = '', $SA = null, $asGET = false) {
                if ( $this->allCount/$this->count > 1 ) {

          $strcp = isset($asGET) ? '?cp=' : '/cp/' ;
          $strcp0 = isset($asGET) ? '?cp=0' : '' ;
          $pager = new outTree();
          $c_p = $this->allCount/$this->count;

          for ($i=0; $i < $c_p; $i++) {
               // echo $href.($i ? $strcp.$i : $strcp0)."<br>";
               $sub = new outTree();
               $sub->addField('T', ( $i == $this->curPage ? ($SA ? 'SA' :'S') : 'A' ) );
               $sub->addField('href', $href.($i ? $strcp.$i : $strcp0));
               $sub->addField('page', $i+1);
               if ($i < ($c_p-1))
                  $sub->addField('separator', '');
               $sub->ITEMTYPE = $sub->T;
               $pager->addField('sub',&$sub);
               unset($sub);
          }

          if ($this->curPage > 0) {
              $page = $this->curPage-1;
              $pager->addField('prev', $href.($page ? $strcp.$page : $strcp0));
              $pager->addField('init_prev', '');
          }

          if ( ($this->curPage+1)<($this->allCount/$this->count) ) {
              $page = $this->curPage+1;
              $pager->addField('next', $href.($page ? $strcp.$page : $strcp0) );
              $pager->addField('init_next', '');
          }

          $pager->addField('pages', '');

       }

       if (isset($pager)) {
                       $this->ot = &$pager;
                       return true;
       }

       return false;
    }

    /**
     * добавляет ветку типа Pager в дерево
     *
     * @param outTree $tree дерево
     * @param string $field имя поля, с каким добавлять
     */
    function addPAGER(&$tree,$field = 'pager') {
            if (isset($this->ot))
                    $tree->addField($field,&$this->ot);
    }

    /**
     * статическая функция для создания объекта Pager
     * и инициализации дерева страниц
     * (выставляет страницу по полю 'id')
     *
     * @return Pager
     */
    function &newPager(&$_db,$_table,$_count = 10,$_curPage = 0,&$param) {

                if (strlen($param['query']) >0) {

                     $pg = new Pager($_db,$_table,$_count,$_curPage,$param['jumpValue'],$param['where'],$param['order'],'id',$param['query']);
                }
                else
                $pg = new Pager($_db,$_table,$_count,$_curPage,$param['jumpValue'],$param['where'],$param['order']);
                if ($pg->allCount) {
                        $pg->initPAGER($param['href'],$param['SA'],$param['asGET']);
                        return $pg;
                }
                return null;
         }


  }

 /**
  * Работает с готовым запросом
  */
 class PagerQuery extends Pager {

   /**
    * Запрос
    *
    * @var Select
    */
   var  $r;

   /**
    *
    * @param Select $_r готовый запрос к базе
    * @param int $_count количество записей на страницу
    * @param int $_curPage страница, которую показывать
    * @param mixed $_jumpValue значение поля записи, которую отображать (приоритетней $_curPage)
    * @param string $_field имя поля, со значением $_jumpValue которого проводится сравнение.
    * @return PagerQuery
    */
        function PagerQuery(&$_r,$_count = 10,$_curPage = 0,$_jumpValue,$_field='id') {
      $this->count = $_count;
      $this->curPage = $_curPage;
      $this->jumpValue = $_jumpValue;
      $this->r = &$_r;
      $this->initBorder();
        }


    /**
     * статическая функция для создания объекта PagerQuery
     * и инициализации дерева страниц
     * (выставляет страницу по полю 'id')
     *
     * @return PagerQuery
     */
         function &new_(&$r,$_count,$_curPage,&$param) {
                 $pg = new PagerQuery($r,$_count,$_curPage,$param['jumpValue']);
                if ($pg->allCount) {
                        $pg->initPAGER($param['href'],$param['SA'],$param['asGET']);
                        return $pg;
                }
                return null;
         }
 }

 /**
  * осуществляет постраничный вывод различных конфигураций
  */
 class Pagers {

         /**
         * отсортированный по полю sort
          */
    function &So(&$_db,$_table,$_count = 10,$_curPage = 0,$href,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order'=>'sort',
                                               'where'=>'pabl="1"'
                                               ));
         }
   function &PrSoZakup(&$_db,$_table,$where,$_count = 10,$_curPage = 0,$href,$field,$order,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order'=>$field.' '.$order,
                                               'where' => $where
                                               ));
         }
         /**
         * отсортированный по полю sort и отфильтрованный по полю parent
          */
    function &PrSo(&$_db,$_table,$_parent,$_count = 10,$_curPage = 0,$href,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order'=>'sort',
                                               'where' => 'pabl="1" AND parent="'.$_parent.'"'
                                               ));
         }

       function &PrSoCt(&$_db,$_table,$_parent,$_count = 10,$_curPage = 0,$href,$field,$order,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order'=>$field.' '.$order,
                                               'where' => 'pabl="1" AND parent="'.$_parent.'"'
                                               ));
         }

      function &PrSoSearch(&$_db,$_table,$where,$_count = 10,$_curPage = 0,$href,$field,$order,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order'=>$field.' '.$order,
                                               'where' => 'pabl="1" '.$where
                                               ));
         }

         /**
         * отсортированный по полю datetime desc
          */
         function &Da(&$_db,$_table,$_count = 10,$_curPage = 0,$href,$SA = null,$_jumpValue=null) {
            return Pager::newPager($_db,$_table,$_count,$_curPage,
                      $ar=array('href'=>$href,
                                               'SA'=>$SA,
                                               'jumpValue'=>$_jumpValue,
                                               'order' => 'datetime desc',
                                               'where'=>'pabl="1"'
                                               ));
         }
 }

?>
