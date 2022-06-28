<?php

namespace ZohoConnect\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use ZohoConnect\Actions\GenerateAccessToken;
use ZohoConnect\Actions\GeneratingGrantToken;
use ZohoConnect\Helpers\URLHelper;
use ZohoConnect\Http\Requests\AuthorizationRequest;
use ZohoConnect\Http\Requests\CallbackRequest;

/**
 *
 */
class AuthenticatorController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        Cache::forget("zoho.connection.credentials.id");
        Cache::forget("zoho.connection.credentials.secret");

        $scopes = file_get_contents(__DIR__ . '/../../../scopes.json');
        $scopes = json_decode($scopes, true);

        return view("zoho.connection.index", [
            'callback_url'        => URLHelper::join(config('app.url'), "/zoho/connection/callback"),
            'data_center'         => config("zoho.connection.data_center"),
            'default_data_center' => config("zoho.connection.default_data_center"),
            'scopes'              => $scopes
        ]);
    }

    /**
     * @param AuthorizationRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function authorization(AuthorizationRequest $request)
    {
        try {
            $generateGrantCode = new GeneratingGrantToken(
                id: $request->id,
                secret: $request->secret,
                scopes: $request->scopes,
            );

            Cache::add("zoho.connection.credentials.id", $request->id, now()->addHour());
            Cache::add("zoho.connection.credentials.secret", $request->secret, now()->addHour());

            return $generateGrantCode
                ->useDataCenter($request->data_center)
                ->handle();

        } catch (\Exception $exception) {
            logger()->error("[zoho-connect] AuthenticatorController::authorization", ["message" => $exception->getMessage()]);
            logger()->error(print_r($request->toArray(), true));
            return view("zoho.connection.error");
        }
    }

    /**
     * @param CallbackRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function callback(CallbackRequest $request)
    {
        try {
            Cache::get("zoho.connection.credentials.id");
            Cache::get("zoho.connection.credentials.secret");

            $generateAccessToken = new GenerateAccessToken(
                id: Cache::get("zoho.connection.credentials.id"),
                secret: Cache::get("zoho.connection.credentials.secret"),
                code: $request->code
            );

            if ($request->get("location")) {
                $generateAccessToken->useDataCenter($request->get("location"));
            }

            $generateAccessToken->handle();

            Cache::forget("zoho.connection.credentials.id");
            Cache::forget("zoho.connection.credentials.secret");

            return view("zoho.connection.success");
        } catch (\Exception $exception) {

            logger()->error("[zoho-connect] AuthenticatorController::callback", ["message" => $exception->getMessage()]);
            logger()->error(print_r($request->toArray(), true));

            return view("zoho.connection.error");
        }
    }
}
