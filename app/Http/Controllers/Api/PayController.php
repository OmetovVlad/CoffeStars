<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avtobot;
use App\Models\Log;
use App\Models\Pay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PayController extends Controller
{
    public function index()
    {
        abort(404);
//        return Log::all();
    }

    public function create(Request $request)
    {
        $redirectUrl = 'https://fingerplay.fun/api/login?id='.Auth::user()->id.'&password='.Auth::user()->remember_token;

        if (isset($request->boost_id)) {
            $boostsList = [
                [
                    'id' => 0,
                    'name' => 'Бронза 1',
                    'production' => 2,
                    'price' => 1000,
                ],[
                    'id' => 1,
                    'name' => 'Бронза 2',
                    'production' => 3,
                    'price' => 3000,
                ],[
                    'id' => 2,
                    'name' => 'Бронза 3',
                    'production' => 4,
                    'price' => 5000,
                ],[
                    'id' => 3,
                    'name' => 'Серебро 1',
                    'production' => 5,
                    'price' => 10000,
                ],[
                    'id' => 4,
                    'name' => 'Серебро 2',
                    'production' => 6,
                    'price' => 15000,
                ],[
                    'id' => 5,
                    'name' => 'Серебро 3',
                    'production' => 7,
                    'price' => 20000,
                ],[
                    'id' => 6,
                    'name' => 'Золото 1',
                    'production' => 8,
                    'price' => 25000,
                ],[
                    'id' => 7,
                    'name' => 'Золото 2',
                    'production' => 9,
                    'price' => 30000,
                ],[
                    'id' => 8,
                    'name' => 'Золото 3',
                    'production' => 10,
                    'price' => 50000,
                ],[
                    'id' => 9,
                    'name' => 'Бриллиант',
                    'production' => 18,
                    'price' => 85000,
                ],
            ];

            for ($i = 0; $i < count($boostsList); $i++) {
                if ( $boostsList[$i]['id'] == $request->boost_id ) {

                    $payment = time() . mt_rand();

                    Pay::create([
                        'user_id' => (int) $request->user_id,
                        'order_id' => $payment,
                        'boost_id' => (int) $boostsList[$i]['id'],
                        'amount' => (int) $boostsList[$i]['price'],
                    ]);

                    $payData = array (
                        'merchant_id' => '66694f41a15c2e61d2b59b03',
                        'secret' => 'LxV0q-FZSOb-rHftm-jMDqb-d4jhD',
                        'order_id' => $payment,
                        'customer' => $request->user_id,
                        'amount' => $boostsList[$i]['price'] * 100,
                        'currency' => 'RUB',
                        'success_url' => $redirectUrl,
                        'fail_url' => $redirectUrl,
                    );

                    $getPaymentUrl = curl_init();
                    curl_setopt_array($getPaymentUrl, array(
                        CURLOPT_URL => 'https://nicepay.io/public/api/payment',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query($payData)
                    ));
                    $paymentUrl = curl_exec($getPaymentUrl);
                    curl_close($getPaymentUrl);

                    header("Location: ".json_decode($paymentUrl)->data->link); // Редирект на страницу оплаты
                    exit();
                }
            }

            return false;
        } elseif (isset($request->autobot_id)) {
            $autobotList = [
                [
                    'id' => 0,
                    'name' => 'Автобот 30',
                    'days' => '30',
                    'price' => 3000,
                ],[
                    'id' => 1,
                    'name' => 'Автобот 90',
                    'days' => '90',
                    'price' => 6500,
                ],[
                    'id' => 2,
                    'name' => 'Автобот 180',
                    'days' => '180',
                    'price' => 10000,
                ],
            ];

            for ($i = 0; $i < count($autobotList); $i++) {
                if ( $autobotList[$i]['id'] == $request->autobot_id ) {
                    $payment = time() . mt_rand();

                    Pay::create([
                        'user_id' => (int) $request->user_id,
                        'order_id' => $payment,
                        'boost_id' => (int) $request->autobot_id,
                        'product_name' => 'autobot',
                        'amount' => (int) $autobotList[$i]['price'],
                    ]);

                    $payData = array (
                        'merchant_id' => '66694f41a15c2e61d2b59b03',
                        'secret' => 'LxV0q-FZSOb-rHftm-jMDqb-d4jhD',
                        'order_id' => $payment,
                        'customer' => $request->user_id,
                        'amount' => $autobotList[$i]['price'] * 100,
                        'currency' => 'RUB',
                        'success_url' => $redirectUrl,
                        'fail_url' => $redirectUrl,
                    );

                    $getPaymentUrl = curl_init();
                    curl_setopt_array($getPaymentUrl, array(
                        CURLOPT_URL => 'https://nicepay.io/public/api/payment',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query($payData)
                    ));
                    $paymentUrl = curl_exec($getPaymentUrl);
                    curl_close($getPaymentUrl);

                    header("Location: ".json_decode($paymentUrl)->data->link); // Редирект на страницу оплаты
                    exit();
                }
            }
        }

    }

    public function result(Request $request)
    {

        $autobotList = [
            [
                'id' => 0,
                'name' => 'Автобот 30',
                'days' => '30',
                'price' => 3000,
            ],[
                'id' => 1,
                'name' => 'Автобот 90',
                'days' => '90',
                'price' => 6500,
            ],[
                'id' => 2,
                'name' => 'Автобот 180',
                'days' => '180',
                'price' => 10000,
            ],
        ];

        $params = $request->query->all();
        $secretKey = 'LxV0q-FZSOb-rHftm-jMDqb-d4jhD'; //  Секретный ключ мерчанта

        $hash = $params['hash'];
        unset($params['hash']); // Удаляем из данных строку подписи
        ksort($params, SORT_STRING); // Сортируем по ключам в алфавитном порядке элементы массива

        array_push($params, $secretKey); // Добавляем в конец массива ключ
        $hash_string = implode('{np}', $params); // Конкатенируем значения через символ "{np}"
        $hash_sha256 = hash('sha256', $hash_string);

        if ($hash != $hash_sha256) {
            echo json_encode(array('error' => array('message' => 'Invalid hash')));
            exit;
        }



        switch ($params['result']) {
            case "success":
                $payment_id = $params['payment_id'];

                if (Pay::where('order_id', $params['order_id'])->count() > 0) {

                    $pay_data = Pay::where('order_id', $params['order_id'])->first();

                    if ($pay_data->product_name === 'autobot') {
                        for ($i = 0; $i < count($autobotList); $i++) {
                            if ( $autobotList[$i]['id'] == $pay_data->boost_id ) {
                                $pay_user = User::where('id', $pay_data->user_id)->first();

                                if ($pay_user->autobot > Carbon::now()) {
                                    $autobot_end_date = Carbon::parse($pay_user->autobot)->addDays($autobotList[$i]['days']);
                                } else {
                                    $autobot_end_date = Carbon::now()->addDays($autobotList[$i]['days']);
                                }

                                User::where('id', $pay_data->user_id)->update([
                                    'autobot' => $autobot_end_date,
                                ]);
                            }
                        }
                    }

                    return Pay::where('order_id', $params['order_id'])->update([
                        'status' => 'PAID',
                    ]);
                }
                exit;
            break;
            case "error":
                $payment_id = $params['payment_id'];

                echo json_encode(array('error' => array('message' => 'Error')));
                exit;
            break;
            default:
                echo json_encode(array('error' => array('message' => 'Empty')));
                exit;
            break;
        }


        switch ($request->result) {
            case "success":


                exit;
            break;
            case "error":
                $payment_id = $request->payment_id;

                echo json_encode(array('error' => array('message' => 'Error')));
                exit;
            break;
            default:
                echo json_encode(array('error' => array('message' => 'Empty')));
                exit;
            break;
        }

        return false;
    }

    public function show(string $id)
    {
        abort(404);
    }
}
