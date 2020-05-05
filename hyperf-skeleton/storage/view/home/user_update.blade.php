<!doctype html>
<html>

@include('home.head')


@include('home.left')
<div class="tpl-content-wrapper">
 <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 表单
                    </div>
                    <div class="tpl-portlet-input tpl-fz-ml">
                        <div class="portlet-input input-small input-inline">
                            <div class="input-icon right">
                                <i class="am-icon-search"></i>
                                <input type="text" class="form-control form-control-solid" value="搜索..."> </div>
                        </div>
                    </div>


                </div>
                <div class="tpl-block ">

                    <div class="am-g tpl-amazeui-form">

				
				@if(isset($res))
                        <div class="am-u-sm-12 am-u-md-9">
                            <form class="am-form am-form-horizontal">
                                <div class="am-form-group">
                                    <label for="user-name" class="am-u-sm-3 am-form-label">姓名 / Name</label>
                                    <div class="am-u-sm-9">
                                        <input type="text" id="name" value="{{$res->name}}">
                                        
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="user-email" class="am-u-sm-3 am-form-label">部门 / Department</label>
                                    <div class="am-u-sm-9">
                                        <input type="text" id="type" value="{{$res->type}}">
                                        
                                    </div>
                                </div>

                                

                                <div class="am-form-group">
                                    <label for="user-QQ" class="am-u-sm-3 am-form-label">业绩 / Values</label>
                                    <div class="am-u-sm-9">
                                        <input type="text"  id="value" value="{{$res->value}}">
                                    </div>
                                </div>
									
                                <div class="am-form-group">
                                    <label for="user-weibo" class="am-u-sm-3 am-form-label">评价 / Descript</label>
                                     
                                    <div class="am-u-sm-9">
                                       <textarea class="" rows="5" id="user-intro" value="">{{$res->descript}}</textarea> 
                                    </div>
                                </div>
								
								<input type="hidden" name="id" value="{{$res->id}}">
                                
				
                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="button" class="am-btn am-btn-primary submit">保存修改</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
		@endif
            </div>
            <script>
            	$('.submit').click(function(){
            		var name=$('#name').val();
            		var value=$('#value').val();
            		var descript=$('#descript').val();
            		var type=$('#type').val();
            		var id=$('#id').val();
            		$.post('{{CLIENT_HOST}}/user/update',{'id':id,'name':name,'value':value,'descript':descript,'type':type},function(res){
            			if(res){
            				alert('修改成功');
            				window.location.reload();
            			}
            		});
            	});
            </script>
       @include('home.foot')