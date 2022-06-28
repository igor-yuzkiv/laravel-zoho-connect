<?php

namespace ZohoConnect;

use Spatie\DataTransferObject\DataTransferObject;

class ClientDto extends DataTransferObject
{
    public string $client_id;
    public string $client_secret;
    public string $access_token;
    public string $refresh_token;
    public string $data_center = 'us';
    public string $expire;
}
