<?php

/**
 * Генерирует строку html-разметки для пагинатора
 *
 * @author mr
 */
class Paginator 
{
    private static $_currentPage = 0;
    private static $_prev = 0;
    private static $_next = 10;
    private static $_pages = 0;
    
    /**
     * Возвращает строку с "пагинатором"
     * @param string $nameTable
     * @param integer $count
     * @return string
     */
    public static function getListPages($nameTable, $count)
    {
        $items = self::getLi($nameTable, $count);
        self::setPrevNext();
        $prevDisabled = self::$_currentPage == 0 ? ' class="disabled"' : '';
        $nextDisabled = self::$_currentPage == self::$_pages*10 ? ' class="disabled"' : '';
        $preStr = "<nav><ul class=\"pagination\"><li" . $prevDisabled . "><a href=\"$nameTable.php?page=" . self::$_prev . "\" aria-label=\"Previous\">" .
                 "<span aria-hidden=\"true\">&laquo;</span></a></li>";
        $postStr = "<li" . $nextDisabled . "><a href=\"$nameTable.php?page=" . self::$_next . "\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span></a></li></ul></nav>";
        return $preStr . $items . $postStr;
    }
    
    /**
     * Заполнение строки лишками с нумерованными страницами
     * @param string $nameTable
     * @param integer $count
     * @return string 
     */
    private static function getLi($nameTable, $count)
    {
        $str = '';
        self::$_pages = round($count/10);
        for($i=0; $i<self::$_pages+1; $i++) {
            $num = $i+1;
            $page = $i*10;
            $currentPage = $_GET['page'];
            if($currentPage == $page) {
                self::$_currentPage = $currentPage;
                $str .= "<li class=\"active\"><a href=\"$nameTable.php?page=$page\">$num</a></li>";
            } else {
                $str .= "<li><a href=\"$nameTable.php?page=$page\">$num</a></li>";
            }
        }
        return $str;
    }
    
    /**
     * Заполнение номером стрелки назад и вперед
     */
    private static function setPrevNext()
    {
        if(self::$_currentPage == 0) {
            self::$_prev = 0;
            self::$_next = 10;
        } elseif (self::$_currentPage == self::$_pages*10) {
            self::$_prev = (self::$_pages-1)*10;
            self::$_next = self::$_pages*10;
        } else {
            self::$_prev = self::$_currentPage-10;
            self::$_next = self::$_currentPage+10;
        }
    }
}