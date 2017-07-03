<?php

class Page
{
    private $rollPage = 5;      //分页栏每页显示的页数

    protected $parameter;  // 分页跳转时要带的参数

    public $offset;
    public $totalPages;
    public $listRows;      // 列表每页显示行数
    public $prePage;
    public $nextPage;
    public $firstPage;
    public $lastPage;

    public function __construct($listRows)
    {
        $this->parameter = $_GET;
        if (!key_exists('p', $this->parameter)) {
            $this->parameter['p'] = 1;
        }
        $this->listRows = $listRows;
        $this->offset = $this->parameter['p'] > 1 ? ($this->parameter['p'] - 1) * $listRows : 0;
    }

    public function show()
    {
        if ($this->parameter['p'] < 1 || $this->parameter['p'] > $this->totalPages) {
            return null;
        }
        if ($this->parameter['p'] > 1) {
            $this->firstPage = $_SERVER['PHP_SELF'] . '?p=1';
            $this->prePage = $_SERVER['PHP_SELF'] . '?p=' . ($this->parameter['p'] - 1);
        }
        if ($this->parameter['p'] < $this->totalPages) {
            $this->lastPage = $_SERVER['PHP_SELF'] . '?p=' . $this->totalPages;
            $this->nextPage = $_SERVER['PHP_SELF'] . '?p=' . ($this->parameter['p'] + 1);
        }
        foreach ($this->parameter as $key => $value) {
            if ($key != 'p') {
                if ($this->prePage) {
                    $this->prePage .= "&$key=" . $value;
                }
                if ($this->nextPage) {
                    $this->nextPage .= "&$key=" . $value;
                }
                if ($this->firstPage) {
                    $this->firstPage .= "&$key=" . $value;
                }
                if ($this->lastPage) {
                    $this->lastPage .= "&$key=" . $value;
                }
            }
        }

        if ($this->prePage) {
            $prePage = "<a href=\"$this->prePage\"><button  type=\"button\" class=\"btn btn-default\">pre</button></a>";
        } else {
            $prePage = "<a ><button type=\"button\" class=\"btn btn-default\">pre</button></a>";
        }
        if ($this->nextPage) {
            $nextPage = "<a href=\"$this->nextPage\"><button  type=\"button\" class=\"btn btn-default\">next</button></a>";
        } else {
            $nextPage = "<a ><button  type=\"button\" class=\"btn btn-default\">next</button></a>";
        }
        if ($this->firstPage) {
            $firstPage = "<a href=\"$this->firstPage\"><button  type=\"button\" class=\"btn btn-default\">first</button></a>";
        } else {
            $firstPage = "<a ><button  type=\"button\" class=\"btn btn-default\">first</button></a>";
        }
        if ($this->lastPage) {
            $lastPage = "<a href=\"$this->lastPage\"><button  type=\"button\" class=\"btn btn-default\">last</button></a>";
        } else {
            $lastPage = "<a ><button  type=\"button\" class=\"btn btn-default\">last</button></a>";
        }

        $startPage = $this->parameter['p'] - ($this->rollPage - 1)/2 > 0 ? $this->parameter['p'] - ($this->rollPage - 1)/2 : 1;
        $endPage = $this->parameter['p'] + ($this->rollPage - 1)/2 < $this->totalPages ? $this->parameter['p'] + ($this->rollPage-1)/2 : $this->totalPages;
        $clickPages = '';
        for ($i = $startPage; $i <= $endPage; $i ++) {
            if ($i != $this->parameter['p']) {
                $param = "?p=$i";
                foreach ( $this->parameter as $key => $value ) {
                    if ($key != 'p') { 
                        $param .=  "&$key=$value";
                    }
                } 
                $clickPages .= "&nbsp;<a href=\"".$_SERVER['PHP_SELF'] ."$param\"><button  type=\"button\" class=\"btn btn-default\">$i</button></a>&nbsp;";
            } else {
                $clickPages .= "&nbsp;<button  type=\"button\" class=\"btn btn-primary\">$i</button>&nbsp;";
            }
        }
        
        $status = '第'. $this->parameter['p']. '页 / 共'. $this->totalPages . '页';
        return $roll = $firstPage . ' ' .$prePage . $clickPages . $nextPage . ' ' . $lastPage. " " . $status;
    }

}
