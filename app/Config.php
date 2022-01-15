<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'domain', 'sitename', 'title', 'description', 'keywords',
        'game_id', 'bot_steamid', 'bot_key', 'bot_username', 'bot_password', 'bot_shared_secret', 'bot_identity_secret',
        'vk_group', 'support_email', 'site_name_protocol',
        'payment',
        'freekassa_id', 'freekassa_secret1', 'freekassa_secret2',
        'unitpay_public', 'unitpay_secret',
        'bonus_free'
    ];
}
