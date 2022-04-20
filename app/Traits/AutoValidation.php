<?php

namespace App\Traits;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

trait AutoValidation
{
    use ApiResponse;

    /**
     * @override For AutoValidation Class
     *
     * Get validation file path [
     *  - Path format: {path.to.file}::{key_1.key_2.key_n}
     *    + path_to_file: path separated with dot without extension (example: app.Http.Controllers)
     *    + `::` mark: optional. It is used to separate file path and keys
     *    + keys: The keys are separated together by `dot` mark
     * ]
     *
     * @return string
     */
    // abstract public function getValidationFilePath();

    protected $validation_file_path = '';

    protected $validation_controller_class = null;
    protected $validation_controller_namespace = null;
    private $default_validation_controller_namespace = 'App\Http\Controllers';

    /**
     * @override For Controller Class
     *
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        // Execute validation
        $invalid = $this->_validateRequest(get_class($this), $method);
        if ($invalid !== true) {
            return $invalid;
        }

        return $this->$method(...array_values($parameters));
    }

    /**
     * Validate the request with specified rules in validation file.
     *
     * @param  string  $class
     * @param  string  $method
     * @return mixed   [true: valid, other: error]
     */
    private function _validateRequest(string $class, string $method)
    {
        $rules = $this->_getValidationRules($class, $method);

        if ($rules) {
            // Validate
            $validator = Validator::make($rules['data'], $rules['rules'], $rules['messages'], $rules['attributes']);
            // Error
            if ($validator->fails()) {
                if (isApi()) {
                    return $this->responseError($validator->errors()->toArray())->getData();
                }
                if (request()->ajax()) {
                    return $this->responseError($validator->errors()->toArray())->getData();
                }
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }

        // Ignore validation (not found any rules)
        return true;
    }

    /**
     * Get the rules to be used for request validation.
     *
     * @param  string  $class
     * @param  string  $method
     * @return array
     */
    private function _getValidationRules(string $class, string $method)
    {
        $namespace = read("{$this->validation_file_path}:namespace", true);

        // If `keys` contain namespace
        if ($namespace === true) {
            return $this->_parseRules(read("{$this->validation_file_path}:rules.{$class}.{$method}"));
        }

        if (!$namespace) {
            $namespace = $this->validation_controller_namespace;
            if (!$namespace && $this->validation_controller_class) {
                $namespace = $this->_extractNamespaceFromClass($this->validation_controller_class);
            }
            if (!$namespace) {
                $namespace = $this->default_validation_controller_namespace;
            }
        }

        $class = substr($class, strlen($namespace) + 1);

        return $this->_parseRules(read("{$this->validation_file_path}:rules.{$class}.{$method}"));
    }

    /**
     * Parse rules
     *
     * @param  array|\Closure $rulesValidation
     * @return array
     */
    private function _parseRules($rulesValidation)
    {
        $request = request();
        $data = $request->all();
        $rules = [];
        $messages = [];
        $attributes = [];

        if (is_callable($rulesValidation)) {
            $rulesValidation = $rulesValidation($request);
        }

        if (!is_array($rulesValidation)) {
            return null;
        }

        // The `_rules_` key is always required
        if (isset($rulesValidation['_rules_'])) {
            // Rules
            $rules = $rulesValidation['_rules_'];
            if (is_callable($rules)) {
                $rules = $rules($request);
            }

            // Input data
            if (isset($rulesValidation['_data_'])) {
                $data = $rulesValidation['_data_'];
                if (is_callable($data)) {
                    $data = $data($request);
                }
                // If input data is empty, get all inputs from request
                if (empty($data)) {
                    $data = $request->all();
                }
            }

            // Custom messages
            if (isset($rulesValidation['_message_'])) {
                $messages = $rulesValidation['_message_'];
                if (is_callable($messages)) {
                    $messages = $messages($request);
                }
            }

            // Custom attributes
            if (isset($rulesValidation['_attributes_'])) {
                $attributes = $rulesValidation['_attributes_'];
                if (is_callable($attributes)) {
                    $attributes = $attributes($request);
                }
            }
        } else {
            $rules = $rulesValidation;
        }

        return [
            'data' => $data,
            'rules' => $rules,
            'messages' => $messages,
            'attributes' => $attributes
        ];
    }

    /**
     * Extract namespace from class
     *
     * @param  string $class
     * @return string
     */
    private function _extractNamespaceFromClass($class)
    {
        // return substr($class, 0, strrpos($class, '\\'));
        return (new \ReflectionClass($class))->getNamespaceName();
    }
}
