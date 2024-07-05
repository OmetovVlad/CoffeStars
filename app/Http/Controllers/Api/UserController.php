<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Avtobot;
use App\Models\Log;
use App\Models\Pay;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return User::all();
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    return User::create([
      'id' => $request->id,
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'username' => $request->username,
      'language_code' => $request->language_code,
      'is_premium' => $request->is_premium,
      'invited' => $request->invited,
      'referrals_tree' => json_encode(array()),
      'week_balance_accrual' => '0',
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    return new PlayerResource(User::findOrFail($id));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, User $user)
  {
    if (isset($request['drop'])) {
      if ($request['drop'] === 'clicker') {

        $boostsList = [
          [
            'id' => 0,
            'production' => 2,
          ], [
            'id' => 1,
            'production' => 3,
          ], [
            'id' => 2,
            'production' => 4,
          ], [
            'id' => 3,
            'production' => 5,
          ], [
            'id' => 4,
            'production' => 6,
          ], [
            'id' => 5,
            'production' => 7,
          ], [
            'id' => 6,
            'production' => 8,
          ], [
            'id' => 7,
            'production' => 9,
          ], [
            'id' => 8,
            'production' => 10,
          ], [
            'id' => 9,
            'production' => 18,
          ],
        ];

        $boostId = NULL;

        if (Pay::where([['user_id', '=', $user->id], ['status', '=', 'PAID']])->count() > 0) {
          $boostId = 0;
          foreach (Pay::where([['user_id', '=', $user->id], ['status', '=', 'PAID']])->get() as $payItem) {
            if ($payItem['boost_id'] > $boostId) {
              $boostId = $payItem['boost_id'];
            }
          }
        }

        if ($boostId !== NULL) {
          $sum = 1 * $boostsList[$boostId]['production'];
        } else {
          $sum = 1;
        }

        if ($user->id === 2138154539 and Pay::where([['user_id', '=', $user->id], ['status', '=', 'PAID']])->count() === 0) {
          $sum = 7;
        }

        Log::create([
          'user_id' => $user->id,
          'coin_id' => 1,
          'type_mining' => 'clicker',
          'type_source_id' => $boostId,
          'sum' => $sum
        ]);

        $currentUserData = User::where('id', $user->id)->first();

        return User::where('id', $user->id)->update([
          'balance' => $currentUserData->balance + $sum,
        ]);

      } else {
        Log::create([
          'user_id' => $user->id,
          'coin_id' => 1,
          'type_mining' => $request['drop'],
          'type_source_id' => 0,
          'sum' => $request->balance,
        ]);

        $user->balance = $user->balance + $request->balance;
        $user->save();

        return $user;
      }
    } elseif (isset($request['coin_id'])) {
      if (Auth::check()) {
        if (isset($request['method']) && $request['method'] === 'partner_reward_1') {
          $orders_count = Log::where([
            ['user_id', '=', $user['id']],
            ['type_mining', '=', 'partner_reward_1'],
            ['type_source_id', '=', $request['partner']],
          ])->count();

          if ($orders_count == 0) {
            Log::create([
              'user_id' => $user['id'],
              'coin_id' => 1,
              'type_mining' => 'partner_reward_1',
              'type_source_id' => $request['partner'],
              'sum' => 500
            ]);

            $currentUserData = User::where('id', $user['id'])->first();

            return User::where('id', $user['id'])->update([
              'balance' => $currentUserData->balance + 500,
            ]);
          }

          return false;
        }
        if (isset($request['method']) && ($request['method'] === 'mining' || $request['method'] === 'autobot')) {

          $boostsList = [
            [
              'id' => 0,
              'production' => 2,
            ], [
              'id' => 1,
              'production' => 3,
            ], [
              'id' => 2,
              'production' => 4,
            ], [
              'id' => 3,
              'production' => 5,
            ], [
              'id' => 4,
              'production' => 6,
            ], [
              'id' => 5,
              'production' => 7,
            ], [
              'id' => 6,
              'production' => 8,
            ], [
              'id' => 7,
              'production' => 9,
            ], [
              'id' => 8,
              'production' => 10,
            ], [
              'id' => 9,
              'production' => 18,
            ],
          ];

          $boostId = NULL;

          if (Pay::where([['user_id', '=', Auth::user()->id], ['status', '=', 'PAID']])->count() > 0) {
            $boostId = 0;
            foreach (Pay::where([['user_id', '=', Auth::user()->id], ['status', '=', 'PAID']])->get() as $payItem) {
              if ($payItem['boost_id'] > $boostId) {
                $boostId = $payItem['boost_id'];
              }
            }
          }

          if ($boostId !== NULL) {
            $sum = $request['cache'] * $boostsList[$boostId]['production'];
          } else {
            $sum = $request['cache'];
          }

          Log::create([
            'user_id' => $user['id'],
            'coin_id' => 1,
            'type_mining' => $request['method'],
            'type_source_id' => $boostId,
            'sum' => $sum
          ]);

          if ($request['method'] === 'autobot') {
            if (Avtobot::where([['user_id', '=', $user['id']], ['created_at', '>=', Carbon::today()]])->count() > 0) {

              $autobotTodayData = Avtobot::where([['user_id', '=', $user['id']], ['created_at', '>=', Carbon::today()]])->first();

              $autobotSum = $autobotTodayData->mining + $sum;

              if ($autobotSum >= 30000) {
                $autobotSum = 30000;
              }
              Avtobot::where([['user_id', '=', $user['id']], ['created_at', '>=', Carbon::today()]])->update([
                'mining' => $autobotSum,
              ]);
            } else {
              Avtobot::create([
                'user_id' => $user['id'],
                'mining' => $sum,
              ]);
            }
          }

          $currentUserData = User::where('id', $user['id'])->first();

          User::where('id', $user['id'])->update([
            'balance' => $currentUserData->balance + $sum,
          ]);

          return 'Зачислено ' . $sum . ' FC';
        }
      }
      return false;
    } else {
      if (isset($request->remember_token)) {
        if ($user->remember_token !== $request->remember_token) {
          $user->remember_token = $request->remember_token;
        }

        if (isset($request->balance)) {
          Log::create([
            'user_id' => $user->id,
            'coin_id' => 1,
            'type_mining' => 'bonus',
            'type_source_id' => 0,
            'sum' => $request->balance,
          ]);
          $user->balance = $request->balance;
        }

        $user->save();
      } elseif (isset($request->action)) {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->sur_name = $request->sur_name;
        $user->address = $request->address;
        $user->phone = $request->phone;
//                $user->card = $request->card;

        $user->save();
      }

      return true;
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function swap(Request $request)
  {
    if (Auth::check() && $request->swapFc) {

      $user = User::where([['id', '=', Auth::user()->id]])->first();

      if ($request->swapFc >= 100000 && $user->balance >= $request->swapFc) {

        $fcSum = ceil($request->swapFc);

        $fcToGfc = $fcSum / 10000;

        $commissionSwap = ceil($fcToGfc * 0.05);
        $resultSwap = floor($fcToGfc * 0.95);

        $userGfcBalance = $user->gfc + $resultSwap;
        $userFcBalance = $user->balance - $fcSum;

        User::where([['id', '=', Auth::user()->id]])->update(['gfc' => $userGfcBalance, 'balance' => $userFcBalance]);

        Log::create([
          'user_id' => Auth::user()->id,
          'coin_id' => 1,
          'type_mining' => 'writing',
          'sum' => $fcSum
        ]);

        Log::create([
          'user_id' => Auth::user()->id,
          'coin_id' => 2,
          'type_mining' => 'deposit',
          'sum' => $resultSwap
        ]);

        Log::create([
          'user_id' => Auth::user()->id,
          'coin_id' => 2,
          'type_mining' => 'commission',
          'sum' => $commissionSwap
        ]);

//        dd($commissionSwap, $resultSwap, $userGfcBalance);

        return true;
      } else {
        return false;
      }

//      if (this.value < 100000) {
//        swapBtn.disabled = true;
//        swapGfc.value = '';
//        swapCommission.value = '';
//      } else {
//        swapBtn.disabled = false;
//        let gfc = this.value / 10000;
//      swapGfc.value = Math.floor(gfc * 0.95);
//      swapCommission.value = Math.ceil(gfc * 0.05);
//    }

    }
    return false;
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    $user->delete();

    return response(null, 204);
  }
}
