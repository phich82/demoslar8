<?php

namespace App\Validation;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class Validator
{
    /**
     * Make a validation
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function make()
    {
        $data = [];
        $rules = [];
        $messages = [];
        $customAttributes = [];

        $args = func_get_args();

        if (count($args) < 1) {
            return FacadesValidator::make(...$args);
        }

        switch (count($args)) {
            case 1:
                $data = request()->all();
                $rules = $args[0];
                break;
            case 2:
                $data = $args[0];
                $rules = $args[1];
                break;
            case 3:
                $data = $args[0];
                $rules = $args[1];
                $messages = $args[2];
                break;
            default:
                $data = $args[0];
                $rules = $args[1];
                $messages = $args[2];
                $customAttributes = $args[3];
        }

        return FacadesValidator::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Validate request
     *
     * @param  array $params
     * @return bool|\Illuminate\Contracts\Validation\Validator
     */
    public function validate($input = [])
    {
        if (empty($input)) {
            $input = !empty($this->input()) ? $this->input() : request()->all();
        }

        $validator = FacadesValidator::make($input, $this->rules(), $this->messages(), $this->attributes());

        if ($validator->fails()) {
            return $validator;
        }

        return true;
    }

    /**
     * Declare input for validation
     *
     * @return array
     */
    public function input()
    {
        return [];
    }

    /**
     * Define validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Customize error messages
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Cusomize the field names
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Validate request automatically
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function autoValidate($request = null)
    {
        $request = $request ?: request();
        $action = $request->route()->getAction();
        // if use closure, ignore validation
        if (!array_key_exists('controller', $action)) {
            Log::warning("[Validation][Api] => Controller is not used (Closure). Validation is ignored.");
            return true;
        }
        // Create validation class name
        $classValidation = self::_buildValidationClassName($request);
        // Resolve path & namspace of validation class
        [
            'path' => $requestPath,
            'namespace' => $requestNamespace
        ] = self::_resolveRequestPath($request);

        $pathFileValidation = trim($requestPath, '/').'/'.$classValidation.'.php';
        // Check validation file
        if (file_exists($pathFileValidation)) {
            // include($pathFileValidation);
            // $classValidation = "{$requestNamespace}\\{$classValidation}";
            // return (new $classValidation)->validate();
            return app()->make("{$requestNamespace}\\{$classValidation}")->validate();
        }
        Log::warning("[Validation][Api] => File [{$pathFileValidation}] not exist. Validation is ignored.");
        return true;
    }

    /**
     * Resolve the request path & namespace
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    private static function _resolveRequestPath(Request $request = null)
    {
        $config = config('version');
        if (empty($config)) {
            throw new Exception("Missing file `config/version.php`");
        }
        if (!isset($config['api'])) {
            $config['api'] = self::_defaultConfig('api');
        }
        if (!isset($config['web'])) {
            $config['web'] = self::_defaultConfig('web');
        }

        $request = $request ?: request();
        $routePath = $request->path();

        if (isApi()) {
            // Get basic information from configuration file
            $version = request()->segment(2, '');
            $requestPath = $config['api']['request_path'] ?? self::_defaultConfig('api')['request_path'];
            $requestNamespace = $config['api']['request_namespace'] ?? self::_defaultConfig('api')['request_namespace'];
            // Get append path of request path if found
            $mappingRequestAppendPaths = $config['api']['mapping_request_append_paths'] ?? self::_defaultConfig('api')['mapping_request_append_paths'];
            $requestAppendPath = '';
            if (!empty($mappingRequestAppendPaths)) {
                $requestAppendPath = self::_getRequestAppendPath($mappingRequestAppendPaths, $routePath);
            }
        } else { // Web
            $version = '';
            $requestPath = $config['web']['request_path'] ?? self::_defaultConfig('web')['request_path'];
            $requestNamespace = $config['web']['request_namespace'] ?? self::_defaultConfig('web')['request_namespace'];
            // Get append path of request path if found
            $mappingRequestAppendPaths = $config['web']['mapping_request_append_paths'] ?? self::_defaultConfig('web')['mapping_request_append_paths'];
            $requestAppendPath = '';
            if (!empty($mappingRequestAppendPaths)) {
                $requestAppendPath = self::_getRequestAppendPath($mappingRequestAppendPaths, $routePath);
            }
        }
        // Get request path
        if (is_callable($requestPath)) {
            $requestPath = $requestPath($version, $requestAppendPath);
        } else {
            $requestPath .= $requestAppendPath;
        }
        // Get request namespace
        $requestAppendPath = trim($requestAppendPath, '/');
        if (is_callable($requestNamespace)) {
            $requestNamespace = $requestNamespace($version, str_replace('/', '\\', $requestAppendPath));
        } else {
            $requestNamespace .= str_replace('/', '\\', $requestAppendPath);
        }

        return [
            'path' => $requestPath,
            'namespace' => $requestNamespace,
        ];
    }

    /**
     * Build validation class name automatically
     *
     * @pattern {HttpRequestMethod} + {ControllerName (without 'Controller' postfix)} + {ControllerAction} + {Postfix: 'Request'}
     * - HttpRequestMethod: Get|Put|Post|Delete|Head (Upper First Case)
     * - ControllerName: Controller name without 'Controller' postfix (ControllerTestController => ControllerTest)
     * - ControllerAction: method of this controller (ControllerTest@index, ControllerTest@store,...)
     * - Postfix: Request
     * * Examples:
     *   + If controller is 'TestController' class with `Post` http method and `index` method, Request class will be `PostTestIndexRequest`
     *   + If controller is 'DemoController' class with `Get` http method and `store' method, Request class will be `GetDemoStoreRequest`
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    private static function _buildValidationClassName(Request $request = null)
    {
        // Create validation file
        $request = $request ?: request();
        $requestMethod = $request->getMethod();
        $controllerAction = $request->route()->getActionMethod();
        $controllerClass = get_class($request->route()->getController());
        $controllerClass = str_replace('\\', '/', $controllerClass);
        $controllerParts = explode('/', $controllerClass);
        $controllerName = preg_replace('/^Controller$/i', '', end($controllerParts));

        // HttpRequestMethod + ControllerName (without 'Controller' postfix) + ControllerAction + 'Request'
        return ucfirst(strtolower($requestMethod))
                .ucfirst($controllerName)
                .ucfirst($controllerAction)
                .'Request';
    }

    /**
     * Get api default configuration
     *
     * @param  string $type
     * @return array
     */
    private static function _defaultConfig($type = 'api')
    {
        return [
            'api' => [
                'request_path' => function ($version = '', $module = '') {
                    return base_path(
                        "app/Api"
                        .($version ? "/$version" : "")
                        ."/Requests"
                        .($module ? "/$module" : "")
                    );
                },
                'request_namespace' => function ($version = '') {
                    return "App\\Api".($version ? "\\{$version}" : "")."\\Request";
                },
                'mapping_request_append_paths' => [],
            ],
            'web' => [
                'request_path' => function ($version = '', $module = '') {
                    return base_path(
                        "app/Http"
                        .($version ? "/$version" : "")
                        ."/Requests"
                        .($module ? "/$module" : "")
                    );
                },
                'request_namespace' => function ($version = '') {
                    return "App\\Http".($version ? "\\{$version}" : "")."\\Request";
                },
                'mapping_request_append_paths' => [],
            ],
        ][$type];
    }

    /**
     * Get append path of request path
     *
     * @param  array $appendPaths
     * @param  string $currentRoute
     * @return string
     */
    private static function _getRequestAppendPath($appendPaths = [], $currentRoute = '')
    {
        if (!is_array($appendPaths) || empty($appendPaths)) {
            return '';
        }
        $currentRoute = $currentRoute ?: request()->path();
        foreach ($appendPaths as $routePattern => $appendPath) {
            if (preg_match($routePattern, $currentRoute)) {
                return '/'.trim($appendPath, '/\\');
            }
        }
        return '';
    }
}
