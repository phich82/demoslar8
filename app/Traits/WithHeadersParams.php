<?php

namespace App\Traits;

trait WithHeadersParams
{
    /**
     * Keys of headers will be validated
     *
     * @var array
     */
    protected $propsHeaders = ['lang'];

    /**
     * @override
     *
     * Validation for headers parameters
     *
     * @return array
     */
    protected function validationData()
    {
        $paramsInput = parent::validationData();
        if (empty($this->propsHeaders)) {
            return $paramsInput;
        }
        return array_reduce($this->headers->keys(), function ($carry, $key) {
            // override keys that they existed in query string and body parameters
            if (in_array($key, $this->propsHeaders)) {
                $carry[$key] = $this->header($key);
            }
            return $carry;
        }, $paramsInput);
    }

    /**
     * Default rules for validating the headers parameters
     *
     * @return array
     */
    protected function rulesDefaultHeadersParams()
    {
        return [
            'lang'     => 'nullable|in:en,jp',
            'sortType' => 'nullable|in:asc,desc',
        ];
    }
}
