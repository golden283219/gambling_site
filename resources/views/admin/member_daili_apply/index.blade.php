@extends('admin.layouts.main')
@section('content')
    <style type="text/css">
        .btn-group>.btn:first-child{
            width: 84px;
        }
    </style>
     <section class="content">
         <div class="panel panel-primary">
             <div class="panel-heading">
                 <h3 class="panel-title">{{__('words.agent_review_list')}}</h3>
             </div>
             <div class="panel-body">
                 @include('admin.member_daili_apply.filter')

                 <table class="table table-bordered table-hover text-center">
                     <tr>
                         <th style="width: 15%">{{__('words.user')}}</th>
                         <th style="width: 20%">{{__('words.phone_number')}}</th>
                         <th style="width: 20%">Email</th>
                         <th>{{__('words.reasone_for_application')}}</th>
                         <th style="width: 10%">{{__('words.status')}}</th>
                         <th style="width: 20%">{{__('words.reason_for_rejection')}}</th>
                         <th style="width: 15%">{{__('words.operating')}}</th>
                     </tr>
                     @foreach($data as $item)
                         <tr>
                             <td>
                                 <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-primary">
                                        {{ $item->member->name }}
                                    </button>
                                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#" class="show-cate" data-uri="{{ route('member.checkBalance', ['id' => $item->member->getKey()]) }}">??????</a></li>
                                        <li><a href="{{ route('member.edit', ['id' => $item->member->getKey()]) }}">??????</a></li>
                                        <li><a href="{{ route('member.assign', ['id' => $item->member->getKey()]) }}">????????????</a></li>

                                         @if ($item->member->status == 0)
                                            <li><a href="{{ route('member.check', ['id' => $item->member->getKey(), 'status' => 1]) }}" onclick="return confirm('??????????????????')">??????</a></li>
                                         @elseif($item->member->status == 1)
                                            <li><a href="{{ route('member.check', ['id' => $item->member->getKey(), 'status' => 0]) }}" onclick="return confirm('??????????????????')">??????</a></li>
                                         @endif
                                        
                                    </ul>
                                </div>
                             </td>
                             <td>
                                 {{ $item->phone }}
                             </td>
                             <td>
                                 {{ $item->msn_qq }}
                             </td>
                             <td>
                                 {{ $item->about }}
                             </td>
                             <td>
                                 @if($item->status == 0)
                                     <strong style="color: orange">?????????</strong>
                                 @elseif($item->status == 1)
                                     <strong style="color: green">????????????</strong>
                                 @elseif($item->status == 2)
                                     <strong style="color: red">???????????????</strong>
                                 @endif
                             </td>
                             <td>
                                 {{ $item->fail_reason }}
                             </td>
                             <td>
                                 @if ($item->status == 0)
                                 <a href="{{ route('member_daili_apply.show', ['id' => $item->getKey()]) }}" class="btn btn-primary btn-xs" onclick="return confirm('??????????????????')">??????</a>

                                 <button type="button" class="btn btn-danger btn-xs" data-uri="{{ route('member_daili_apply.update', ['id' => $item->id]) }}" onclick="showRemark(this)">?????????</button>
                                 {{--<button class="btn btn-danger btn-xs"--}}
                                         {{--data-url="{{route('fs_level.destroy', ['id' => $item->getKey()])}}"--}}
                                         {{--data-toggle="modal"--}}
                                         {{--data-target="#delete-modal"--}}
                                 {{-->--}}
                                     {{--??????--}}
                                 {{--</button>--}}
                                     @endif
                             </td>
                         </tr>
                     @endforeach
                 </table>
                 <div class="clearfix">
                     <div class="pull-left" style="margin: 0;">
                         <p>{{__('words.total')}} <strong style="color: red">{{ $data->total() }}</strong> {{__('words.article')}}</p>
                     </div>
                 <div class="pull-right" style="margin: 0;">
                    {!! $data->appends(['phone' => $phone])->links() !!}
                 </div>
                 </div>

             </div>
         </div>

     </section><!-- /.content -->

     <!-- Modal -->
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                     <h4 class="modal-title" id="myModalLabel">???????????????</h4>
                 </div>
                 <div class="modal-body">
                     <form action="" method="post" class="form-horizontal" id="updateReason">
                         {!! csrf_field() !!}
                         <input type="hidden" name="_method" value="put">
                         <div class="box-body">
                             <div class="form-group">
                                 <label for="fail_reason" class="col-sm-3 control-label"><span style="color: red">???????????????</span></label>
                                 <div class="col-sm-8">
                                     <textarea name="fail_reason" id="fail_reason" rows="3" required class="form-control"></textarea>
                                 </div>
                             </div>
                         </div><!-- /.box-body -->
                         <div class="box-footer">
                             <div class="form-group">
                                 <label class="col-sm-3 control-label"></label>
                                 <div class="col-sm-8">
                                     <button type="submit" class="btn btn-info btn-flat">??????</button>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
     <script>
         function showRemark(e)
         {
             var uri = $(e).attr('data-uri');
             $('#updateReason').attr('action',uri)
             $('#myModal').modal('show');
         }
     </script>
      <script>
         $(function(){
             $('.show-cate').click(function(){
                 var url = $(this).attr('data-uri');
                 layer.open({
                     type: 2,
                     title: '??????',
                     shadeClose: false,
                     shade: 0.8,
                     area: ['90%', '90%'],
                     content: url
                 });
             })
         });
     </script>
@endsection
@section("after.js")
     @include('admin.layouts.delete',['title'=>'????????????','content'=>'????????????????????????????????????????'])
@endsection