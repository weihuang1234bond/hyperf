<?php
namespace App\Controller\common;
use Hyperf\DbConnection\Db;

class Taskfunc 
{
	function savecontent($content){
		//到底用文件还是用数据库。文件跟数据库大了都很难管理。比如10W个人以后。每个人对应一张表 就要100W张表。
		//这是个难题。用mysql,如果量不大 就一直一张表 ，量大就删除之前的表，分表/压缩保存在文件中,查的时候用task
		//那么群聊的怎么处理？ 专门一个群聊表。给群聊一个id 按时间排序。 可以将所有表凌晨的时候分批储存到库.
		// 具体，表名?a-b b-a ? 按照用户id？比如1-5 1-7 小的前面
		
		$res=strcmp($content['save'],$content['master']);
		$e='';
		
		if($res<0){
			//要换地方。 model 里不行
			$e=Db::table($content['save'].'-'.$content['master'])->insert($content);
		}else{
			$e=Db::table($content['master'].'-'.$content['save'])->insert($content);
		}
		
		if(!$e){
			//如果插入失败。 记录失败原因到log
		}
	}
}