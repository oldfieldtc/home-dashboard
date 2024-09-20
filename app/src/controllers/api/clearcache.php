<?php

apcu_clear_cache();

if ( $_GET['origin'] === 'clock' ) {
    http_response_code(200);
} else {
    header('Location:' . $_GET['origin']);
}
