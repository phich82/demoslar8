<?php
namespace App\Helpers;

class Constant
{
    # Prefix API
    const PREFIX_API = 'api';
    const PREFIX_WEB_ADMIN = 'admin';

    # Module Name for Admin
    const GUARD_ADMIN = 'admin';
    const MODULE_ADMIN = 'admin';

    # CORS
    const ACCESS_CONTROLL_ALLOW_METHODS = 'POST, GET';
    const ACCESS_CONTROLL_ALLOW_HEADERS = 'Content-Type, X-API-TOKEN';

    // APIs Header
    const X_API_TOKEN = 'X-API-TOKEN';
    const X_API_ACCESS_TOKEN = 'X-API-ACCESS-TOKEN';
    const HEADER_LANG_KEY_API = 'lang';

    // APIs Response
    const API_RESPONSE_SUCCESS = 'success';
    const API_RESPONSE_RESULT = 'data';
    const API_RESPONSE_CODE = 'code';
    const API_RESPONSE_MESSAGE = 'message';
    const API_RESPONSE_DEFAULT_ERROR_DATA = null;
}
