<?php
// +----------------------------------------------------------------------
// | Theme:分页类
// +----------------------------------------------------------------------
// | Introduce:分页的条件组织,前台输出
// +----------------------------------------------------------------------
// | Author: NiuShaoGang <NgauSiuKong@gmail.com>
// +----------------------------------------------------------------------
/**
 * 分页类
 * @author <NgauSiuKong@gmail.com>
 */
namespace page;
class pagination
{
    //每页需要列出的条数
    private $page_size = 10;
    public function __construct($page_size){
        if($page_size != null){
            $this->page_size = $page_size;
        }
    }
    //构造分页条件
    public function page_condition($page_id,$total)
    {
        //过滤分页页数
        $page_max = ceil($total/$this->page_size);
        $page_id>=1 || $page_id = 1;
        $page_id<=$page_max || $page_id = $page_max;
        //写分页条件
        $offest = ($page_id-1)*$this->page_size;
        $offest = $offest > 0?$offest:0;
        $limit = " limit ".$offest.",".$this->page_size;
        return $limit;

    }
    //提取分页显示结果,英文后台版
    public function page($page_id,$total)
    {
        //过滤分页页数
        $page_max = ceil($total/$this->page_size);
        $page_id>=1 || $page_id = 1;
        $page_id<=$page_max || $page_id = $page_max;
        $prepage = $page_id-1;
        $nextpage = $page_id+1;
        $border = " | ";
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page_id=';
        $url1 = "?page_id=";
        $HomePage = $page_id != 1 ?"<span><a href=".$url1."1".">HomePage</a></span>":"<span>HomePage</span>";
        $PrePage = $page_id != 1 ?"<span><a href='".$url1.$prepage."'>PrePage</a></span>":"<span>PrePage</span>";
        $input = "<input type='text' name='page_id' id='input_page_id' placeholder='".$page_id."'><button id='button'>Go</button>";
        $NextPage = $page_id != $page_max?"<span><a href='".$url1.$nextpage."'>NextPage</a></span>":"<span><a>NextPage</a></span>";
        $LastPage = $page_id != $page_max?"<span><a href='".$url1.$page_max."'>LastPage</a></span>":"<span>LastPage</span>";
        $JavaScript = "<script type='text/javascript'>
                            //点击跳页
                            var inputObj = document.getElementById('input_page_id');
                            var buttonObj = document.getElementById('button');

                            buttonObj.onclick = function(){
                                var input_value = inputObj.value;
                                if(!input_value){
                                    alert('请输入页码数值');
                                }
                                var url = window.location.href;
                                var num = url.indexOf('?',url);
                                if(num){
                                    url = url.substring(0,num)
                                }
                                var page_id = 'page_id='+input_value;
                                var jump_url = url+'?'+page_id;
                                window.location.href=jump_url;
                            }
                            var HomePageObj = document.getElementById('HomePage');
                       </script>";
        $page_info = $HomePage.$border.$PrePage.$border.$input.$border.$NextPage.$border.$LastPage.$JavaScript;
        return $page_info;
    }
    //提取分页结果，中文前端版
    public function page_CN($page_id,$total)
    {

    }
}