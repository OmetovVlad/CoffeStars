<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Log;
use App\Models\Referal_payment;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccrualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Referal_payment::create([
            'for_date' => Carbon::now()->startOfWeek()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        function getTodayBalance($user_id) {
            $referralPayments = DB::table('referal_payments')->get();
            $referralPaymentsCount = count($referralPayments);

            if ($referralPaymentsCount > 0) {
                $logs = DB::table('logs')->where([
                    ['user_id', '=', $user_id],
                    ['type_mining', '=', 'mining'],
                    ['created_at', '>=',  DB::table('referal_payments')->orderby('id', 'desc')->first()->created_at], //Carbon::now()->startOfWeek()
                ])->get();

                $sum = 0;
                for ($i = 0; $i < count($logs); $i++) {
                    $sum += $logs[$i]->sum;
                }

                return $sum;
            }

            $userData = DB::table('users')->where('id', $user_id)->first();
            return $userData->balance;
        }

        if ($request->type === 'nullReferrals') {
            return User::where('id', $request->user_id)->update([
                'referrals_tree' => json_encode(array()),
                'week_balance_accrual' => '0',
            ]);
        }

        if ($request->type === 'load') {

//            dd($request);

            function referrals_line_data($data, $multiplier) {
                return [
                    'id' => $data->id,
                    'name' => $data->first_name,
                    'fullBalance' => number_format($data->balance, 0, '', ' '),
                    'weekBalance' => number_format(getTodayBalance($data->id), 0, '', ' '),
                    'weekBalanceAccrual' => number_format(getTodayBalance($data->id) * $multiplier, 0, '', ' '),
                ];
            }

            $referrals = DB::table('users')->where('invited', $request->user_id)->get();
            $referralsTree = array();
            $weekBalanceAccrual = 0;

            for ($i = 0; $i < count($referrals); $i++) {
                $referralsTree[$i] = referrals_line_data($referrals[$i], 0.2);
                $weekBalanceAccrual += getTodayBalance($referrals[$i]->id) * 0.2;
                $referrals_2line = DB::table('users')->where('invited', $referrals[$i]->id)->get();

                for ($j = 0; $j < count($referrals_2line); $j++) {
                    $referralsTree[$i][$j] = referrals_line_data($referrals_2line[$j], 0.1);
                    $weekBalanceAccrual += getTodayBalance($referrals_2line[$j]->id) * 0.1;
                    $referrals_3line = DB::table('users')->where('invited', $referrals_2line[$j]->id)->get();

                    for ($k = 0; $k < count($referrals_3line); $k++) {
                        $referralsTree[$i][$j][$k] = referrals_line_data($referrals_3line[$k], 0.05);
                        $weekBalanceAccrual += getTodayBalance($referrals_3line[$k]->id) * 0.05;
                        $referrals_4line = DB::table('users')->where('invited', $referrals_3line[$k]->id)->get();

                        for ($n = 0; $n < count($referrals_4line); $n++) {
                            $referralsTree[$i][$j][$k][$n] = referrals_line_data($referrals_4line[$n], 0.03);
                            $weekBalanceAccrual += getTodayBalance($referrals_4line[$n]->id) * 0.03;
                            $referrals_5line = DB::table('users')->where('invited', $referrals_4line[$n]->id)->get();

                            for ($m = 0; $m < count($referrals_5line); $m++) {
                                $referralsTree[$i][$j][$k][$n][$m] = referrals_line_data($referrals_5line[$m], 0.01);
                                $weekBalanceAccrual += getTodayBalance($referrals_5line[$m]->id) * 0.01;
                                $referrals_6line = DB::table('users')->where('invited', $referrals_5line[$m]->id)->get();

                                for ($l = 0; $l < count($referrals_6line); $l++) {
                                    $referralsTree[$i][$j][$k][$n][$m][$l] = referrals_line_data($referrals_6line[$l], 0.01);
                                    $weekBalanceAccrual += getTodayBalance($referrals_6line[$l]->id) * 0.01;
                                }
                            }
                        }
                    }
                }
            }

            $weekBalanceAccrual = number_format($weekBalanceAccrual, 0, '', ' ');

            User::where('id', $request->user_id)->update([
                'referrals_tree' => json_encode($referralsTree),
                'week_balance_accrual' => $weekBalanceAccrual,
            ]);

            return json_encode($referralsTree);
        }

        if ($request->user_id > 0) {

            function accrualPay ($user_id) {

                $referrals = DB::table('users')->where('invited', $user_id)->get();

                $weekBalanceAccrual = 0;

                for ($i = 0; $i < count($referrals); $i++) {
                    $weekBalanceAccrual += getTodayBalance($referrals[$i]->id) * 0.2;
                    $referrals_2line = DB::table('users')->where('invited', $referrals[$i]->id)->get();

                    for ($j = 0; $j < count($referrals_2line); $j++) {
                        $weekBalanceAccrual += getTodayBalance($referrals_2line[$j]->id) * 0.1;
                        $referrals_3line = DB::table('users')->where('invited', $referrals_2line[$j]->id)->get();

                        for ($k = 0; $k < count($referrals_3line); $k++) {
                            $weekBalanceAccrual += getTodayBalance($referrals_3line[$k]->id) * 0.05;
                            $referrals_4line = DB::table('users')->where('invited', $referrals_3line[$k]->id)->get();

                            for ($n = 0; $n < count($referrals_4line); $n++) {
                                $weekBalanceAccrual += getTodayBalance($referrals_4line[$n]->id) * 0.03;
                                $referrals_5line = DB::table('users')->where('invited', $referrals_4line[$n]->id)->get();

                                for ($m = 0; $m < count($referrals_5line); $m++) {
                                    $weekBalanceAccrual += getTodayBalance($referrals_5line[$m]->id) * 0.01;
                                    $referrals_6line = DB::table('users')->where('invited', $referrals_5line[$m]->id)->get();

                                    for ($l = 0; $l < count($referrals_6line); $l++) {
                                        $weekBalanceAccrual += getTodayBalance($referrals_6line[$l]->id) * 0.01;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($weekBalanceAccrual > 0) {
                    Log::create([
                        'user_id' => $user_id,
                        'coin_id' => 1,
                        'type_mining' => 'referal',
                        'type_source_id' => 0,
                        'sum' => round($weekBalanceAccrual),
                    ]);

                    $currentUserData = User::where('id', $user_id)->first();

                    User::where('id', $user_id)->update([
                        'balance' => $currentUserData->balance + round($weekBalanceAccrual),
                    ]);
                }

                return round($weekBalanceAccrual);
            }

            return accrualPay($request->user_id);
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // return User::findOrFail($id)::whereNull('invited')->with('invitedUser')->get();
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort(404);
    }
}
