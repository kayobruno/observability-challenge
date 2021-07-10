<?php

/**
 * @param string $input
 * @return string
 */
function convertCamelCaseToSnakeCase(string $input): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}
