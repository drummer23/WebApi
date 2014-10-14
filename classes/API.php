<?php
/**
 * Created by PhpStorm.
 * User: rspielmann
 * Date: 14.10.2014
 * Time: 10:28
 */

namespace WebApi;


interface API {
    public function __construct($data);
    public function isGET();
    public function isPOST();
    public function isPUT();
    public function isDELETE();
} 