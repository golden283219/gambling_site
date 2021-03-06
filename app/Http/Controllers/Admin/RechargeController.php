<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dividend;
use App\Models\Member;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recharge;
use App\Traits\ValidationTrait;
use DB;
class RechargeController extends Controller
{
    use ValidationTrait;
    public function index(Request $request)
    {
        $mod = new Recharge();

        $status = $payment_type = $name = $start_at = $end_at ='';
        if ($request->has('name'))
        {
            $name = $request->get('name');
            $m_list = Member::where('name', 'LIKE', "%$name%")->pluck('id');
            $mod = $mod->whereIn('member_id', $m_list);
        }
        if ($request->has('status'))
        {
            $status = $request->get('status');
            $mod = $mod->where('status', $status);
        }

        if ($request->has('payment_type'))
        {
            $payment_type = $request->get('payment_type');
            $mod = $mod->where('payment_type', $payment_type);
        }
        if ($request->has('start_at'))
        {
            $start_at = $request->get('start_at');
            $mod = $mod->where('created_at', '>=', $start_at);
        }
        if ($request->has('end_at'))
        {
            $end_at = $request->get('end_at');
            $mod = $mod->where('created_at', '<=',$end_at);
        }

        $data = $mod->orderBy('created_at', 'desc')->paginate(config('admin.page-size'));

        $total_recharge = $mod->sum('money');
        $total_diff_money = $mod->sum('diff_money');

        return view('admin.recharge.index', compact('data', 'status', 'payment_type', 'total_recharge', 'total_diff_money', 'name', 'start_at', 'end_at'));
    }

    public function show($id)
    {
        $data = Recharge::findOrFail($id);

        $mod = SystemConfig::where('id',1)->first();
        $cz_ration = $mod->cz_ration / 100;
        $data->diff_money = sprintf('%.2f',$data -> money * $cz_ration);
        //dd($data->toArray());
        return view('admin.recharge.show', compact('data'));
    }

    //????????????
    public function confirm(Request $request, $id)
    {

        $validator = $this->verify($request, 'recharge.confirm');

        if ($validator->fails())
        {
            $messages = $validator->messages()->toArray();
            return responseWrong($messages);
        }

        if ($request->get('money') < 1)
            return responseWrong('?????????????????????');

        $mod = Recharge::findOrFail($id);
        $data = $request->all();
        try{
            DB::transaction(function() use($mod, $data,$request) {

                $diff_money = $request->get('diff_money') > 0 ? $request->get('diff_money') : 0 ;

                $mod->update([
                    'status' => 2,
                    'confirm_at' => date('Y-m-d H:i:s'),
                    'diff_money' => $diff_money
                ]);

                //???????????????????????? ?????????????????????
                if ($diff_money > 0)
                    Dividend::create([
                        'member_id' => $mod->member_id,
                        'type' => 1,
                        'money' => $request->get('diff_money'),
                        'describe' => '??????????????????',
//                        'before_money' => $mod->member->money,
//                        'after_money' => $mod->member->money + $data['money'],
                        'status' => 1
                    ]);

                //????????????????????????
                $m = $mod->money + $diff_money;
                $mod->member()->increment('money', $m);



            });
        }catch(Exception $e){
            DB::rollback();
            return respF('????????????');
        }

        return responseSuccess('??????????????????', '', route('recharge.index'));
    }

    public function create()
    {
        return view('admin.recharge.create');
    }

    public function store(Request $request)
    {
        $validator = $this->verify($request, 'recharge.store');

        if ($validator->fails())
        {
            $messages = $validator->messages()->toArray();
            return responseWrong($messages);
        }

        $data = $request->all();

        Recharge::create($data);

        return responseSuccess('', '', route('recharge.index'));

    }

    public function edit($id)
    {
        $data = Recharge::findOrFail($id);

        return view('admin.recharge.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (!$request->has('fail_reason'))
            respF('????????????????????????');

        $mod = Recharge::findOrFail($id);

        $mod->update([
            'fail_reason' => $request->get('fail_reason'),
            'status' => 3
        ]);

        return respS();

    }

    public function destroy($id)
    {
        Recharge::destroy($id);

        return respS();
    }
}
