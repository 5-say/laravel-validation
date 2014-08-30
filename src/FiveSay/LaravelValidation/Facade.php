<?php namespace FiveSay\LaravelValidation;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor() { return new LaravelValidation; }
}
