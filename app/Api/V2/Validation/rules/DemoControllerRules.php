<?php

use Illuminate\Http\Request;

return [
    '_data_' => function (Request $request) {
        return $request->all();
    },
    '_rules_' => function (Request $request) {
        return [

        ];
    },
    '_messages_' => function (Request $request) {
        return [

        ];
    },
    '_attributes_' => function (Request $request) {
        return [

        ];
    },
];
