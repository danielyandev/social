<?php


namespace App\Helpers;


class ApiHelper
{
    /**
     * @return string
     */
    public static function getVersion()
    {
        // the result will be api/v{VERSION}, the version starts on 6th char
        $prefix = request()->route()->getPrefix();

        return substr($prefix, 5) ?: config('app.api_version_latest');
    }

    public static function getResponseSender()
    {
        $version = self::getVersion();
        $responseClassname = "App\Contracts\Api\V{$version}\SendResponse";
       return new $responseClassname();
    }
}
