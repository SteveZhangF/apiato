<?php

namespace App\Containers\Authentication\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\Authentication\Transporters\ProxyRefreshTransporter;
use App\Ship\Parents\Actions\Action;

/**
 * Class ProxyApiRefreshAction.
 */
class ProxyApiRefreshAction extends Action
{

    /**
     * @param \App\Containers\Authentication\Transporters\ProxyRefreshTransporter $data
     *
     * @return  array
     */
    public function run(ProxyRefreshTransporter $data): array
    {
        $requestData = [
            'grant_type'    => $data->grant_type ?? 'refresh_token',
            'refresh_token' => $data->refresh_token,
            'client_id'     => $data->client_id,
            'client_secret' => $data->client_password,
            'scope'         => $data->scope ?? '',
        ];

        $responseContent = Apiato::call('Authentication@CallOAuthServerTask', [$requestData]);

        $refreshCookie = Apiato::call('Authentication@MakeRefreshCookieTask', [$responseContent['refresh_token']]);

        return [
            'response-content' => $responseContent,
            'refresh-cookie'   => $refreshCookie,
        ];
    }
}
