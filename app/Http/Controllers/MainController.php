<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Avtobot;
use App\Models\Order;
use App\Models\Pay;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;

class MainController extends Controller
{
    public function index(Request $request)
    {

        if ( Auth::check() ) {
            $balance = Auth::user()->balance ? Auth::user()->balance : 0;
            $balanceGfc = Auth::user()->gfc ? Auth::user()->gfc : 0;

            if (Auth::user()->referrals_tree) {
                $referralsTree = json_decode(Auth::user()->referrals_tree);
                $weekBalanceAccrual = Auth::user()->week_balance_accrual;
            } else {
                $referralsTree = array();
                $weekBalanceAccrual = 0;
            }

            //$weekBalanceAccrual = Auth::user()->week_balance_accrual;

            $products = Product::all();

            $orders = Order::where('user_id', Auth::user()->id)->get();

            foreach ($products as $product) {
                $product->lock = 0;
                if ($product->limit > 0) {
                    $orders_count = Order::where([
                        ['user_id', '=', Auth::user()->id],
                        ['product_id', '=', $product->id],
                    ])->count();

                    if ( $orders_count > 0 && $orders_count == $product->limit ) {
                        $product->lock = 1;
                    }
                }
            }

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

            $boostsList = [
                [
                    'id' => 0,
                    'name' => 'Бронза 1',
                    'production' => 2,
                    'price' => 1000,
                    'image' => Vite::asset('resources/img/coin_1.png'),
                ],[
                    'id' => 1,
                    'name' => 'Бронза 2',
                    'production' => 3,
                    'price' => 3000,
                    'image' => Vite::asset('resources/img/coin_2.png'),
                ],[
                    'id' => 2,
                    'name' => 'Бронза 3',
                    'production' => 4,
                    'price' => 5000,
                    'image' => Vite::asset('resources/img/coin_3.png'),
                ],[
                    'id' => 3,
                    'name' => 'Серебро 1',
                    'production' => 5,
                    'price' => 10000,
                    'image' => Vite::asset('resources/img/coin_4.png'),
                ],[
                    'id' => 4,
                    'name' => 'Серебро 2',
                    'production' => 6,
                    'price' => 15000,
                    'image' => Vite::asset('resources/img/coin_5.png'),
                ],[
                    'id' => 5,
                    'name' => 'Серебро 3',
                    'production' => 7,
                    'price' => 20000,
                    'image' => Vite::asset('resources/img/coin_6.png'),
                ],[
                    'id' => 6,
                    'name' => 'Золото 1',
                    'production' => 8,
                    'price' => 25000,
                    'image' => Vite::asset('resources/img/coin_7.png'),
                ],[
                    'id' => 7,
                    'name' => 'Золото 2',
                    'production' => 9,
                    'price' => 30000,
                    'image' => Vite::asset('resources/img/coin_8.png'),
                ],[
                    'id' => 8,
                    'name' => 'Золото 3',
                    'production' => 10,
                    'price' => 50000,
                    'image' => Vite::asset('resources/img/coin_9.png'),
                ],[
                    'id' => 9,
                    'name' => 'Бриллиант',
                    'production' => 18,
                    'price' => 85000,
                    'image' => Vite::asset('resources/img/coin_10.png'),
                ],
            ];

            $partnersList = [
                [
                    'id' => 12,
                    'name' => 'Finger Play',
                    'desc' => 'Большой каталог, более 300 игровых автоматов от популярных провайдеров',
                    'link' => 'https://fingerplay.online/go/70',
                    'class' => 'fingerplay',
                    'reward1' => 500,
                    'reward2' => 200000,
                    'logo' => '<img src="https://fingerplay.online/images/favicon.png" />',
                ],[
                    'id' => 0,
                    'name' => 'Дебетовая Альфа-Карта',
                    'desc' => 'Бесплатное обслуживание и суперкэшбэк каждый месяц',
                    'link' => 'https://pxl.leads.su/click/13af8fa2d331fdb5844c8bed69246eec?erid=LjN8K7iVx',
                    'class' => 'black alfabank',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<svg height="47" viewBox="0 0 33 50" width="34" class="d3HYp" data-test-id="Main-Header-Main-DesktopLogo"><path clip-rule="evenodd" d="M0 49.982v-6.825h32.61v6.825H0zm11.646-28.764h9.064L16.39 7.526h-.17l-4.573 13.692h-.001zm10.587-16.22l9.615 28.887h-7.115l-2.16-6.866H9.698l-2.33 6.867H.679l10.09-28.887C11.746 2.197 12.887 0 16.559 0s4.744 2.206 5.674 4.999v-.001z" fill="currentColor" fill-rule="evenodd"></path></svg>',
                ],[
                    'id' => 1,
                    'name' => 'Дебетовая карта Газпромбанка',
                    'desc' => 'Доход до 35% по карте',
                    'link' => 'https://pxl.leads.su/click/c0797fd42a3572aade79ef538937bdee?erid=LjN8K8NZ8',
                    'class' => 'black gazprombank',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="6" fill="#476BF0"/><path d="M16.0003 6.11875C13.5694 6.11875 11.3426 6.99486 9.61977 8.44817C8.95575 9.00831 8.21846 9.48855 7.39212 9.75766C7.14671 9.83758 6.90555 9.91946 6.66889 10.0035C6.36797 10.1104 6.2175 10.1638 6.14292 10.1091C6.11737 10.0904 6.09659 10.0638 6.08462 10.0344C6.04967 9.94884 6.13512 9.82068 6.30602 9.56437C8.39187 6.43617 11.9551 4.375 16.0002 4.375C18.8498 4.375 21.4602 5.39783 23.4835 7.09596C24.0685 7.58692 24.3292 8.41147 23.8889 9.03518C23.8063 9.15217 23.7155 9.2642 23.6175 9.37033C23.1482 9.87856 22.5271 10.2589 21.8802 10.5565C20.3961 11.2391 18.4003 11.6453 16.6679 11.9979L16.665 11.9985C16.4995 12.0322 16.3363 12.0654 16.1762 12.0983C11.6455 13.03 8.34051 13.8021 6.15784 14.9545C5.69159 15.2006 5.28715 15.459 4.93926 15.7332C4.70269 15.9196 4.58441 16.0128 4.50297 15.9942C4.47309 15.9874 4.44855 15.9747 4.42569 15.9543C4.36336 15.8987 4.37049 15.7585 4.38475 15.478C4.3908 15.3591 4.39817 15.2387 4.40686 15.1183C4.46473 14.3172 4.9338 13.6085 5.65495 13.2531C5.89586 13.1343 6.14632 13.0199 6.40709 12.9088C8.78118 11.8971 11.9707 11.1828 15.8242 10.3904C15.9753 10.3593 16.1268 10.3284 16.2783 10.2974C18.0683 9.93171 19.8611 9.56542 21.1499 8.9726C21.6179 8.75732 21.9673 8.53426 22.2125 8.30914C20.5136 6.93812 18.3546 6.11875 16.0003 6.11875Z" fill="white"/><path d="M6.10503 20.907C5.95482 21.1864 5.87971 21.326 5.80138 21.3432C5.77125 21.3498 5.74715 21.3492 5.71742 21.341C5.64012 21.3196 5.58072 21.1935 5.46193 20.9412C5.45762 20.932 5.45332 20.9228 5.44903 20.9137C5.05805 20.0776 5.20847 19.072 5.92751 18.4925C6.26996 18.2165 6.64859 17.969 7.05639 17.7443C9.1128 16.6111 12.1697 15.9276 15.8267 15.1449C19.5763 14.3424 22.3389 13.7049 24.1181 12.7245C24.9828 12.248 25.546 11.725 25.8788 11.1229C25.8844 11.1129 25.8898 11.1028 25.8953 11.0927C26.0455 10.8134 26.1206 10.6737 26.1989 10.6565C26.2291 10.6498 26.2532 10.6505 26.2829 10.6587C26.3602 10.68 26.4196 10.8062 26.5384 11.0585C26.5428 11.0677 26.5471 11.0769 26.5514 11.0861C26.9423 11.9222 26.792 12.9277 26.073 13.5072C25.7305 13.7833 25.3517 14.0309 24.9438 14.2557C22.8874 15.3889 19.8305 16.0724 16.1734 16.8551C12.4239 17.6575 9.66125 18.2951 7.88209 19.2755C7.01741 19.752 6.45414 20.275 6.12135 20.8771C6.11586 20.887 6.11042 20.897 6.10503 20.907Z" fill="white"/><path d="M25.3373 21.9874C25.6383 21.8801 25.7888 21.8265 25.8634 21.8812C25.889 21.8999 25.9098 21.9265 25.9218 21.9558C25.9568 22.0414 25.8715 22.1696 25.7007 22.4262C23.6158 25.5597 20.0495 27.625 16.0002 27.625C13.1444 27.625 10.5288 26.5977 8.50365 24.8928C7.91684 24.3988 7.65856 23.569 8.10534 22.9458C8.1843 22.8357 8.27053 22.73 8.36321 22.6296C8.83251 22.1214 9.45369 21.7411 10.1006 21.4435C11.5847 20.7608 13.5804 20.3547 15.3128 20.0021C15.4794 19.9682 15.6435 19.9347 15.8045 19.9016C20.3352 18.97 23.6402 18.1978 25.8229 17.0455C26.2967 16.7954 26.7067 16.5326 27.0583 16.2534C27.2961 16.0647 27.415 15.9704 27.4966 15.9888C27.5266 15.9956 27.5511 16.0082 27.5741 16.0286C27.6366 16.0841 27.6295 16.2255 27.6154 16.5081C27.6094 16.6276 27.6021 16.7486 27.5936 16.8695C27.5369 17.6692 27.071 18.3776 26.352 18.734C26.0872 18.8652 25.8109 18.9911 25.5221 19.113C23.1535 20.1131 19.9822 20.8229 16.1566 21.6096C16.0054 21.6406 15.854 21.6716 15.7025 21.7025C13.9125 22.0683 12.1196 22.4346 10.8308 23.0274C10.3628 23.2427 10.0135 23.4657 9.76827 23.6908C11.4671 25.0619 13.6262 25.8812 15.9804 25.8812C18.4113 25.8812 20.6381 25.0051 22.3609 23.5518C23.025 22.9917 23.7623 22.5114 24.5886 22.2423C24.8428 22.1595 25.0925 22.0746 25.3373 21.9874Z" fill="white"/></svg>',
                ],[
                    'id' => 2,
                    'name' => 'Тинькофф All Games',
                    'desc' => 'Игровой никнейм на карте, кэшбэк за игры, технику и развлечения',
                    'link' => 'https://pxl.leads.su/click/d8b8416dbab73bc393092535118871ab?erid=LjN8JzyjC',
                    'class' => 'black tinkoff_all_games',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<img src="https://acdn.tinkoff.ru/params/common_front/resourses/icons/apple-touch-icon-120x120.png">',
                ],[
                    'id' => 3,
                    'name' => 'Дебетовая карта ВТБ',
                    'desc' => 'Бесплатное обслуживание и кешбэк за покупки у партнеров до 50%',
                    'link' => 'https://pxl.leads.su/click/0a9cc4c19ea09be0ce47ee975ee3192b?erid=LjN8KHA7e',
                    'class' => 'vtb',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<img src="https://cc.vtb.ru/favicon-180x180.png">',
                ],[
                    'id' => 4,
                    'name' => 'Дебетовая карта «My Life»',
                    'desc' => 'Теперь вы сами выбираете, за какие покупки получать кешбэк',
                    'link' => 'https://pxl.leads.su/click/858c30d623ee3b442750cc41f607070e?erid=LjN8K4LYe',
                    'class' => 'black ubrr',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<img src="https://www.ubrr.ru/manifest/apple-touch-icon-120x120.png">',
                ],[
                    'id' => 5,
                    'name' => 'МТС Банк',
                    'desc' => 'Дебетовая карта Скидка везде',
                    'link' => 'https://pxl.leads.su/click/c5e128493a9bad5ec2b5ec06848b9d04?erid=LjN8K2k6c',
                    'class' => 'black mts',
                    'reward1' => 500,
                    'reward2' => 40000,
                    'logo' => '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="120" height="120" viewBox="0 0 120 120" fill="none" version="1.1" id="svg78" sodipodi:docname="icon.svg" inkscape:version="1.0.1 (c497b03c, 2020-09-10)"><script xmlns=""/><defs id="defs82"/><sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="1514" inkscape:window-height="1066" id="namedview80" showgrid="false" scale-x="1" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0" inkscape:zoom="2" inkscape:cx="-93.25" inkscape:cy="20" inkscape:window-x="0" inkscape:window-y="23" inkscape:window-maximized="0" inkscape:current-layer="svg78"/><rect width="120" height="120" fill="#ffffff" id="rect74" x="0" y="0" style="stroke-width:3"/><path fill-rule="evenodd" clip-rule="evenodd" d="M 36,66.9669 C 36,79.9287 43.4091,93 60,93 76.5762,93 84,79.9287 84,66.9669 84,58.113 80.9556,47.9175 75.8742,39.6825 70.9326,31.7301 64.9953,27 60,27 54.9906,27 49.0506,31.7301 44.1519,39.6825 39.0465,47.9175 36,58.113 36,66.9669 Z" fill="#e30611" id="path76" style="stroke-width:3"/></svg>',
                ],[
                    'id' => 6,
                    'name' => 'Своя Кредитка',
                    'desc' => 'Это 20% кешбэк на ВСЁ и целых 120 дней без %!',
                    'link' => 'https://pxl.leads.su/click/24b6c75388b9c63e89cd6203f19cf39a?erid=LjN8K79BY',
                    'class' => 'svoi',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<img src="https://svoi.ru/icons/icon-96x96.png">',
                ],[
                    'id' => 7,
                    'name' => 'Тинькофф Platinum',
                    'desc' => 'Получите кэшбэк до 30% рублями за траты на маркетплейсах!',
                    'link' => 'https://pxl.leads.su/click/d2245dc00976cbb4b6f117c20193c13f?erid=LjN8KcE9k',
                    'class' => 'black tinkoff_platinum',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<img src="https://acdn.tinkoff.ru/params/common_front/resourses/icons/apple-touch-icon-120x120.png">',
                ],[
                    'id' => 8,
                    'name' => 'Альфа банк - «365 дней без %»',
                    'desc' => 'Целый год без %. А так же скидка на Яндекс Маркете до 30%',
                    'link' => 'https://pxl.leads.su/click/6a8e40ef5c7a0e6445dd9893e39891f7?erid=LjN8KWDA2',
                    'class' => 'alfabank-credit',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<svg height="47" viewBox="0 0 33 50" width="34" class="d3HYp" data-test-id="Main-Header-Main-DesktopLogo"><path clip-rule="evenodd" d="M0 49.982v-6.825h32.61v6.825H0zm11.646-28.764h9.064L16.39 7.526h-.17l-4.573 13.692h-.001zm10.587-16.22l9.615 28.887h-7.115l-2.16-6.866H9.698l-2.33 6.867H.679l10.09-28.887C11.746 2.197 12.887 0 16.559 0s4.744 2.206 5.674 4.999v-.001z" fill="currentColor" fill-rule="evenodd"></path></svg>',
                ],[
                    'id' => 9,
                    'name' => 'Карта «Халва»',
                    'desc' => '24 месяца рассрочки; 15% на остаток по карте; Кэшбэк до 10%; Обслуживание 0 ₽',
                    'link' => 'https://pxl.leads.su/click/20cca974fec02b323688c8a2d0daa1d7?erid=LjN8KFjYf',
                    'class' => 'black halva',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<img src="https://halvacard.ru/img/app-icons/favicon-114.png">',
                ],[
                    'id' => 10,
                    'name' => 'Кредитная карта МТС банка',
                    'desc' => 'Льготный период до 415 дней без % по всем покупкам в первый месяц',
                    'link' => 'https://pxl.leads.su/click/14f98891a2705c81551efa8060c0480b?erid=LjN8K844Y',
                    'class' => 'mts-credit black',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="120" height="120" viewBox="0 0 120 120" fill="none" version="1.1" id="svg78" sodipodi:docname="icon.svg" inkscape:version="1.0.1 (c497b03c, 2020-09-10)"><script xmlns=""/><defs id="defs82"/><sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="1514" inkscape:window-height="1066" id="namedview80" showgrid="false" scale-x="1" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0" inkscape:zoom="2" inkscape:cx="-93.25" inkscape:cy="20" inkscape:window-x="0" inkscape:window-y="23" inkscape:window-maximized="0" inkscape:current-layer="svg78"/><rect width="120" height="120" fill="#ffffff" id="rect74" x="0" y="0" style="stroke-width:3"/><path fill-rule="evenodd" clip-rule="evenodd" d="M 36,66.9669 C 36,79.9287 43.4091,93 60,93 76.5762,93 84,79.9287 84,66.9669 84,58.113 80.9556,47.9175 75.8742,39.6825 70.9326,31.7301 64.9953,27 60,27 54.9906,27 49.0506,31.7301 44.1519,39.6825 39.0465,47.9175 36,58.113 36,66.9669 Z" fill="#e30611" id="path76" style="stroke-width:3"/><script xmlns=""/></svg>',
                ],[
                    'id' => 11,
                    'name' => 'Кредитная карта ВТБ',
                    'desc' => 'До 200 дней без % на всё: покупки, переводы, снятие наличных.',
                    'link' => 'https://pxl.leads.su/click/c83dbe66a096a07d2806c8b67e29a696?erid=LjN8KZxCC',
                    'class' => 'black vtb-credit',
                    'reward1' => 500,
                    'reward2' => 80000,
                    'logo' => '<img src="https://cc.vtb.ru/favicon-180x180.png">',
                ]
            ];

            $production = 1;
            $coin = Vite::asset('resources/img/coin_0.png');

            if ( Pay::where([['user_id', '=', Auth::user()->id], ['status', '=', 'PAID'], ['product_name', '=', NULL]])->count() > 0 ) {
                $payedBoostId = 0;
                foreach (Pay::where([['user_id', '=', Auth::user()->id], ['status', '=', 'PAID']])->get() as $payItem) {
                    if ($payItem['boost_id'] > $payedBoostId) {
                        $payedBoostId = $payItem['boost_id'];
                    }
                }

                $coin = $boostsList[$payedBoostId]['image'];
                $production = $boostsList[$payedBoostId]['production'];
            }

            $autobot = false;
            $autobotTodaySum = 0;

            if (Auth::user()->autobot > Carbon::now()) {
                $autobot = true;

                if (Avtobot::where([['user_id', '=', Auth::user()->id], ['created_at', '>=', Carbon::today()]])->count() > 0) {
                    $autobotTodaySum = Avtobot::where([['user_id', '=', Auth::user()->id], ['created_at', '>=', Carbon::today()]])->first()->mining;
                }

            }

            return view('main', ['balance' => $balance, 'balanceGfc' => $balanceGfc, 'weekBalanceAccrual' => $weekBalanceAccrual, 'referralsTree' => $referralsTree, 'products' => $products, 'orders' => $orders, 'partnersList' => $partnersList, 'boostsList' => $boostsList, 'coin' => $coin, 'production' => $production, 'autobotList' => $autobotList, 'autobot' => $autobot, 'autobotTodaySum' => $autobotTodaySum]);
        }

        return view('main');
    }
}
