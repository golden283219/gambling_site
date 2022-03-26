@extends('admin.layouts.main')
@section('content')
    <section class="content">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('words.Administrator_editor')}}</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal cmxform" name="registerForm" id="form" action="{{ route('user.update', ['id' => $data->getKey()]) }}}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="put">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">{{__('words.user_name')}}</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{__('words.user_name')}}" value="{{ $data->name }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">{{__('words.password')}}</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{__('words.The_original_password_is_not_modified')}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">{{__('words.mailbox')}}</label>
                            <div class="col-sm-7">
                                <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}" placeholder="{{__('words.mailbox')}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role_id" class="col-sm-2 control-label">{{__('words.Management_group')}}</label>
                            <div class="col-sm-7">
                                <select name="role_id" id="role_id" class="form-control">
                                    @foreach($role_list as $k => $v)
                                        <option value="{{ $k }}" @if($k == $data->role_id) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-7">
                                <button type="button" class="btn btn-primary submit-form-sync">{{__('words.submit')}}</button>
                                &nbsp;<a href="{{ route('user.index') }}" class="btn btn-info">{{__('words.return')}}</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </section><!-- /.content -->

@endsection