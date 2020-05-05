<!doctype html>
<html>

@include('home.head')


@include('home.left')
 <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 表单
                    </div>
                    <div class="tpl-portlet-input tpl-fz-ml">
                        <div class="portlet-input input-small input-inline">
                            <div class="input-icon right">
                                <i class="am-icon-search"></i>
                                <input type="text" class="form-control form-control-solid" placeholder="搜索..."> </div>
                        </div>
                    </div>


                </div>
                <div class="tpl-block ">

                    <div class="am-g tpl-amazeui-form">

				@foreach($res as $v)
                        <div class="am-u-sm-12 am-u-md-9">
                            <form class="am-form am-form-horizontal">
                                <div class="am-form-group">
                                    <label for="user-name" class="am-u-sm-3 am-form-label">姓名 / Name</label>
                                    <div class="am-u-sm-9">
                                        <input type="text" id="name" placeholder="{{$v->name}}">
                                        
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="user-email" class="am-u-sm-3 am-form-label">部门 / Department</label>
                                    <div class="am-u-sm-9">
                                        <input type="email" id="type" placeholder="{{$v->type}}">
                                        
                                    </div>
                                </div>

                                

                                <div class="am-form-group">
                                    <label for="user-QQ" class="am-u-sm-3 am-form-label">业绩 / Values</label>
                                    <div class="am-u-sm-9">
                                        <input type="number" pattern="[0-9]*" id="value" placeholder="{{$v->value}}">
                                    </div>
                                </div>
									
                                <div class="am-form-group">
                                    <label for="user-weibo" class="am-u-sm-3 am-form-label">评价 / Descript</label>
                                    <div class="am-u-sm-9">
                                        <input type="text" id="descript" placeholder="{{$v->descript}}">
                                    </div>
                                </div>
								
								<input type="hidden" name="id" value="{{$v->id}}">
                                
					@endforeach
                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="button" class="am-btn am-btn-primary">保存修改</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
       @include('home.foot')