<?php

register_shutdown_function('fatalErrorShutdownHandler');

function fatalErrorShutdownHandler() {
    $bypass_error_list = Array(
        E_DEPRECATED,
    );

    $last_error = error_get_last();
    if ($last_error != NULL && !in_array($last_error['type'], $bypass_error_list)) {
        ob_start();
        var_dump($last_error);
        $error_dump = ob_get_clean();
        echo $error_dump;
        //return $error_dump;
    }
}
