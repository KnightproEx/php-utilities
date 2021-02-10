<?php

class Response {
    public $error = FALSE;
    public $errorMsg = '';
}

class DataResponse extends Response {
    public $count = 0;
    public $data = NULL;
}
