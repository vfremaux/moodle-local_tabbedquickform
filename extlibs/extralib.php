<?php

/**
 * Some functions that are not allowed by tests.
 */
function set_globals($key, $value) {
    $GLOBALS[$key] = $value;
}