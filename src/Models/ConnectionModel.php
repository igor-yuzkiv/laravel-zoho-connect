<?php

namespace ZohoConnect\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class ConnectionModel extends Model
{
    /**
     * @var string
     */
    protected $table = "zoho_connection";

    /**
     * @var string[]
     */
    protected $fillable = [
        "client_id",
        "client_secret",
        "access_token",
        "refresh_token",
        "data_center",
        "expire",
    ];
}
