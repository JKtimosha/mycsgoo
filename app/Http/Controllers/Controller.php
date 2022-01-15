<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $redis;
    protected $config;

    public function __construct()
    {
        $this->redis = Redis::connection();
        $this->config = Config::find(1);

        $settings = (object) [
            'appId' => $this->config->game_id,
            'domain' => $this->config->domain,
            'sitename' => $this->config->sitename,
            'title' => $this->config->title,
            'description' => $this->config->description,
            'keywords' => $this->config->keywords,
            'vk_group' => $this->config->vk_group,
            'support_email' => $this->config->support_email,
            'site_name_protocol' => $this->config->site_name_protocol,
            'free' => $this->config->bonus_free
        ];

        view()->share('settings', $settings);

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('u', $this->user);
            return $next($request);
        });
    }
}
