<!doctype html>
<html>

@include('home.head')


@include('home.left')



	

        <div class="tpl-content-wrapper">
            <div class="tpl-content-page-title">
                用户管理
            </div>
            <ol class="am-breadcrumb">
                <li><a href="#" class="am-icon-home">首页</a></li>
                <li><a href="#">用户管理</a></li>
                <li class="am-active">用户列表</li>
            </ol>
            <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 列表
                    </div>
                    <div class="tpl-portlet-input tpl-fz-ml">
                        <div class="portlet-input input-small input-inline">
                            <div class="input-icon right">
                                <i class="am-icon-search"></i>
                                <input type="text" class="form-control form-control-solid" placeholder="搜索..."> </div>
                        </div>
                    </div>


                </div>
                <div class="tpl-block">
                    <div class="am-g">
                        <div class="am-u-sm-12 am-u-md-6">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <button type="button" class="am-btn am-btn-default am-btn-success">新增</button>
                                    <button type="button" class="am-btn am-btn-default am-btn-secondary"> 保存</button>
                                    <button type="button" class="am-btn am-btn-default am-btn-warning"> 审核</button>
                                    <button type="button" class="am-btn am-btn-default am-btn-danger"> 删除</button>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-sm-12 am-u-md-3">
                            <div class="am-form-group">
                                <select data-am-selected="{btnSize: 'sm'}">
					              <option value="option1">所有类别</option>
					              <option value="option2">IT业界</option>
					            
					            </select>
                            </div>
                        </div>
                        <div class="am-u-sm-12 am-u-md-3">
                            <div class="am-input-group am-input-group-sm">
                                <input type="text" class="am-form-field">
                                <span class="am-input-group-btn">
            <button class="am-btn  am-btn-default am-btn-success tpl-am-btn-success am-icon-search" type="button"></button>
          </span>
                            </div>
                        </div>
                    </div>
                    <div class="am-g container">
                        <div class="am-u-sm-12">
                            <form class="am-form">
                                <table class="am-table am-table-striped am-table-hover table-main">
                                    <thead>
                                        <tr>
                                            <th class="table-check"><input type="checkbox" class="tpl-table-fz-check"></th>
                                            <th class="table-id">ID</th>
                                            <th class="table-title">职位</th>
                                            <th class="table-title">名字</th>
                                            <th class="table-type">注册时间</th>
                                            <th class="table-author am-hide-sm-only">业绩</th>
                                            <th class="table-date am-hide-sm-only">描述</th>
                                            <th class="table-set">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ttt">
                                    	<input type="hidden" name="master" value="{{$master}}">
                                    	@foreach($data as $v)
                                        <tr class="ppp" >
                                            <td><input type="checkbox"></td>
                                            <td class="id">{{$v->id}}</td>
                                            <td>{{$v->type}}</td>
                                            <td>{{$v->name}}</td>
                                            <td>{{$v->addtime}}</td>
                                            <td class="am-hide-sm-only">{{$v->descript}}</td>
                                            <td class="am-hide-sm-only">{{$v->value}}</td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                    	<a href="{{CLIENT_HOST}}/user/chat?master={{$master}}&save={{$v->name}}"><button class="am-btn am-btn-default am-btn-xs am-text-secondary">信息</button></a>
                                                        <a href="{{CLIENT_HOST}}/user/index?id={{$v->id}}"><button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button></a>
                                                        <!--<button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 复制</button>-->
                                                        <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only dell"><span class="am-icon-trash-o"></span> 删除</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                </table>
                                
                                <div id="page">
								
                                    <!--<div class="am-fr">-->
                                    <!--    <ul class="am-pagination tpl-pagination">-->
                                    <!--        <li class="am-disabled"><a href="#">«</a></li>-->
                                    <!--        <li class="am-active"><a href="#">1</a></li>-->
                                    <!--        <li><a href="#">2</a></li>-->
                                           
                                    <!--    </ul>-->
                                    <!--</div>-->
                                </div>
                                <script>
                                
                                
                                		
                                layui.use('laypage', function(){
									  var laypage = layui.laypage;
									  
									  //执行一个laypage实例
									  laypage.render({
									    elem: 'page' //注意，这里的 test1 是 ID，不用加 # 号
									    ,count: 50000 //数据总数，从服务端得到
									    ,limit:5
										,jump: function(obj, first){
										    //obj包含了当前分页的所有参数，比如：
										  //  console.log(obj.curr);得到当前页，以便向服务端请求对应页的数据。
										  //  console.log(obj.limit); 得到每页显示的条数
										  var dd=$('.ppp');
                                			var tt=$('.ttt');
										    //首次不执行
										    if(!first){
										      //do something
										        $.post("{{CLIENT_HOST}}/index/page",{'page':obj.curr},function(re){
									    			dd.remove('tr');
									    			$.each(re.data,function(i,n){
									    													var html='';
									    	html +='<tr class="ppp" >';
                                         	html +=   '<td><input type="checkbox"></td>';
                                        	html +=   ' <td>'+n.id+'</td>';
                                        	html +=   '<td>'+n.type+'</td>';
                                         	html +=  ' <td>'+n.name+'</td>';
                                    		html +=      '<td>'+n.addtime+'</td>';
                                    	  	html +=     '<td class="am-hide-sm-only">'+n.descript+'</td>';
                                    	 	html +=     '<td class="am-hide-sm-only">'+n.value+'</td>';
                                          	html +=  '<td>';
                                        	html +=        '<div class="am-btn-toolbar">';
                                        	html +=            '<div class="am-btn-group am-btn-group-xs">';
                                        	html +=     '<button class="am-btn am-btn-default am-btn-xs am-text-secondary">';
                                        	html +=				'<span class="am-icon-pencil-square-o"></span> 编辑</button>';
                                                        
                                           	html +=  '<button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only">';
                                         	html +=    	'<span class="am-icon-trash-o"></span> 删除</button>';
                                          	html +=          '</div>';
                                        	html +=        '</div>';
                                    		html +=     '</td>';
                                    		html +=   '</tr>';
									    				tt.append(html);
									    			});
									    			
									    });
										    }
										   
										  }
									   
									  });
									});
									
									
									// 删除
									$('.dell').click(function(){
										var id=$('.id').val();
										$.post('{{CLIENT_HOST}}/user/del',{'id':id},function(res){
											if(res){
												$this.remove();
											}
										});
									});
                                </script>
                                <hr>

                            </form>
                        </div>
				
                    </div>
                </div>
                <div class="tpl-alert"></div>
            </div>

        </div>

    </div>
    <!--这样应该能获取master的值-->
<script src="/home/static/js/ws.js"></script>
@include('home.foot');