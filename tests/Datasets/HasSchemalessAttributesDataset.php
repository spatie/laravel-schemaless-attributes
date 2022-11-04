<?php

dataset('non-valid-values', [
    'string' => 'string',
    'not array json' => '""',
    'invalid json format' => "{'name':'value'}",
    'null' => null,
    'false' => false,
    'integer' => 1,
    'float' => 0.1
]);
