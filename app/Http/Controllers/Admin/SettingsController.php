<?php

namespace App\Http\Controllers\Admin;

use App\Open;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SettingsController extends Controller
{
    public function load()
    {
        return $this->config;
    }

    public function save(Request $r)
    {
        $this->config->update($r->config);

        return [
            'type' => 'success',
            'message' => 'Настройки сохранены'
        ];
    }

    public function socketRun() {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';

        if ($_SERVER['SERVER_NAME'] === '_') {
        	$server = $protocol.''.$_SERVER['SERVER_ADDR'];
        } else {
        	$server = $protocol.''.$_SERVER['SERVER_NAME'];
		}

        $process = new Process('pm2 start app.js -- '.$server, '/var/www/html/storage/app/server');

        try {
            $process->mustRun();

            //echo $process->getOutput();
            return [
                'type' => 'success',
                'message' => 'Сокет запущен'
            ];
        } catch (ProcessFailedException $exception) {
            //echo $exception->getMessage();
            return [
                'type' => 'error',
                'message' => 'Не смогли запустить сокет'
            ];
        }
    }

    public function socketOff() {
        $process = new Process('pm2 stop app.js', '/var/www/html/storage/app/server');
        $process2 = new Process('pm2 delete app', '/var/www/html/storage/app/server');

        try {
            $process->mustRun();
            $process2->mustRun();

            //echo $process->getOutput();
            return [
                'type' => 'success',
                'message' => 'Сокет выключен'
            ];
        } catch (ProcessFailedException $exception) {
            //echo $exception->getMessage();
            return [
                'type' => 'error',
                'message' => 'Не смогли выключить сокет'
            ];
        }
    }

    public function statistic()
    {
        $payments = [
            'today' => Payment::where('created_at', '>=', Carbon::today())->where('status', 1)->sum('sum'),
            'week' => Payment::where('created_at', '>=', Carbon::now()->subDays(7))->where('status', 1)->sum('sum'),
            'month' => Payment::where('created_at', '>=', Carbon::now()->subDays(31))->where('status', 1)->sum('sum'),
            'all' => Payment::where('status', 1)->sum('sum')
        ];

        $opens = Open::with(['item'])->where('status', Open::SEND)->get();
        $sumSell = 0;

        foreach ($opens as $open) {
            $sumSell += $open->item->price;
        }

        $stats = [
            'users' => User::count('id'),
            'opens' => Open::count('id'),
            'profit' => Payment::where('status', 1)->sum('sum') - $sumSell
        ];

        $chart = [
            'users' => User::select(\DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), \DB::raw('count(*) as count'))
                ->whereMonth('created_at', '=', date('m'))
                ->groupBy('date')
                ->get(),
            'payments' => Payment::select(\DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), \DB::raw('SUM(sum) as sum'))
                ->whereMonth('created_at', '=', date('m'))
                ->where('status', 1)
                ->groupBy('date')
                ->get()
        ];

        $lastPayments = Payment::with(['user'])->orderBy('id', 'desc')->where('status', 1)->limit(10)->get();

        foreach ($lastPayments as $payment) {
            $payment->time = Carbon::parse($payment->created_at)->diffForHumans();
        }

        $lastUsers = User::orderBy('id', 'desc')->limit(10)->get();

        foreach ($lastUsers as $user) {
            $user->time = Carbon::parse($user->created_at)->diffForHumans();
        }

        $topBalance = User::orderBy('balance', 'desc')->limit(10)->get();

        foreach ($topBalance as $user) {
            $user->time = Carbon::parse($user->created_at)->diffForHumans();
        }

        return [
            'payments' => $payments,
            'stats' => $stats,
            'chart' => $chart,
            'lastPayments' => $lastPayments,
            'lastUsers' => $lastUsers,
            'topBalance' => $topBalance
        ];
    }

    public function getBalance()
    {
        $url = json_decode(file_get_contents('https://market.dota2.net/api/v2/get-money?key='.$this->config->bot_key), true);

        if (!$url['success']) return ['success' => false];

        return ['success' => true, 'balance' => $url['money']];
    }
}
