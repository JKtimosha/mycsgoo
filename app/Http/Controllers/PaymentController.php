<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\User;
use App\Promocode;
use Auth;

class PaymentController extends Controller
{
    public function create(Request $r)
    {
        $code = $r->promo;
        $sum = $r->sum;

        if (intval($sum) <= 0) $sum = 1;

        if (Auth::check()) {
        	$promo = Promocode::where('name', $code)->first();

        	if ($promo) {
        		$promo = $promo->id;
        	} else {
        		$promo = NULL;
        	}

        	$payment = Payment::create([
        		'user_id' => $this->user->id,
        		'invoice' => 0,
        		'sum' => $sum,
        		'promocode_id' => $promo
            ]);

            if ($this->config->payment === 'freekassa') {
                $sign = md5($this->config->freekassa_id . ':' . $sum . ':' . $this->config->freekassa_secret1 . ':' . $payment->id);

                return 'http://www.free-kassa.ru/merchant/cash.php?m=' . $this->config->freekassa_id . '&oa=' . $sum . '&o=' . $payment->id . '&s=' . $sign;
            } else {
                $signature = $this->getFormSignature($payment->id,'RUB', 'Пополнение баланса', $sum, $this->config->unitpay_secret);

                $url = 'https://unitpay.ru/pay/'.$this->config->unitpay_public.'?sum='.$sum.'&account='.$payment->id.'&desc=Пополнение баланса&currency=RUB&signature='.$signature;

                return $url;
            }
        }
    }

    public function check(Request $r)
    {
        if ($this->config->payment === 'freekassa') {
            if (!in_array($this->getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '136.243.38.108'))) {
                die("hacking attempt!");
            }

            $payment = Payment::find($r['MERCHANT_ORDER_ID']);
            if (!$payment || $payment->status) die('Not found payment');
            if (intval($payment->sum) !== intval($r['AMOUNT'])) die('Invalid sum');

            $sign = md5($this->config->freekassa_id . ':' . $payment->sum . ':' . $this->config->freekassa_secret2 . ':' . $payment->id);
            if ($sign !== $r['SIGN']) die('Invalid sign');

            $promo = Promocode::find($payment->promocode_id);
            $user = User::find($payment->user_id);
            if (!$user) die('Invalid user');

            $bonus = 0;
            if ($promo) $bonus = $promo->percent;

            $sum = $payment->sum + ($payment->sum * ($bonus / 100));

            $payment->update([
                'status' => 1
            ]);

            $user->update([
                'balance' => $user->balance + $sum
            ]);

            die('Success');
        } else {
            if ($r['method'] === 'check') {
                $payment = Payment::find($r['params']['account']);

                if (!$payment || $payment->status) {
                    return [
                        'error' => [
                            "message" => "Платеж не найден или уже обработан"
                        ]
                    ];
                }

                $sign = $this->getSignature($r['method'], $r['params'], $this->config->unitpay_secret);
                $sum = $r['params']['payerSum'];

                if (intval($payment->sum) !== intval($sum)) {
                    return [
                        'error' => [
                            "message" => "Суммы не совпадают"
                        ]
                    ];
                }

                if ($sign !== $r['params']['signature']) {
                    return [
                        'error' => [
                            "message" => "Сигнатура не совпадает"
                        ]
                    ];
                }

                return [
                    'result' => [
                        "message" => "Запрос успешно обработан"
                    ]
                ];
            }

            if ($r['method'] === 'pay') {
                $payment = Payment::find($r['params']['account']);

                if (!$payment || $payment->status) {
                    return [
                        'error' => [
                            "message" => "Платеж не найден или уже обработан"
                        ]
                    ];
                }

                $user = User::find($payment->user_id);

                $promo = Promocode::find($payment->promocode_id);
                $bonus = 0;
                if ($promo) $bonus = $promo->percent;

                $sum = $payment->sum + ($payment->sum * ($bonus / 100));

                $payment->update([
                    'status' => 1
                ]);

                $user->update([
                    'balance' => $user->balance + $sum
                ]);

                return [
                    'result' => [
                        "message" => "Запрос успешно обработан"
                    ]
                ];
            }
        }
    }

    public function getSignature($method, array $params, $secretKey) {
        ksort($params);
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, $secretKey);
        array_unshift($params, $method);
        return hash('sha256', join('{up}', $params));
    }

    public function getFormSignature($account, $currency, $desc, $sum, $secretKey)
    {
        $hashStr = $account . '{up}' . $currency . '{up}' . $desc . '{up}' . $sum . '{up}' . $secretKey;
        return hash('sha256', $hashStr);
    }

    public function getIP() 
    {
    	if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }
}
