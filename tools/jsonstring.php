<?php

/**
 * Return JSON_PRETTY_PRINT of `$json_string`
 *
 * @param string $jsonstring An unpretty json string
 * @return string Return pretty-print json string
 */
function pretty( $jsonstring ){
    $json = json_decode( $jsonstring, true );
    if( JSON_ERROR_NONE !== json_last_error() ){
        return json_last_error_msg();
    }
    $json = json_encode( $json, JSON_PRETTY_PRINT );
    return $json;
}