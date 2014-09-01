<?php namespace FiveSay\LaravelValidation;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use FiveSay\ValidationException;

class LaravelValidation
{
    /**
     * @var \Illuminate\Support\MessageBag
     */
    public static $errors;

    /**
     * 异常化表单验证
     * @param  array  $rules    规则
     * @param  array  $messages 消息
     * @throws \FiveSay\LaravelValidation\LaravelValidationException
     */
    public function make(array $rules, array $messages = array())
    {
        // 获取数据
        $data = Input::only(array_keys($rules));
        // 字段验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails())
            throw new ValidationException($validator, 0);
    }

	/**
     * 表单验证并存储错误消息
     * @param  array  $rules    规则
     * @param  array  $messages 消息
     * @return void
     */
    public function saveMessage(array $rules, array $messages = array())
    {
        // 获取数据
        $data = Input::only(array_keys($rules));
        // 字段验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            if (is_null(self::$errors)) {
                self::$errors = $validator->errors();
            } else {
                self::$errors->merge($validator->errors());
            }
        }
    }

    /**
     * 直接抛出异常，并对消息进行打包
     * @param  string|array $key  表单字段名 | 消息列表
     * @param  string $message    错误消息
     * @throws \FiveSay\LaravelValidation\LaravelValidationException
     */
    public function throwIt($key, $message = '')
    {
        if (! is_array($key)) {
            $key = array($key => $message);
        }

        $messageBag = new MessageBag($key);

        throw new ValidationException($messageBag, 1);
    }

    /**
     * 存储错误消息
     * @param  string|array $key  表单字段名 | 消息列表
     * @param  string $message    错误消息
     * @throws void
     */
    public function error($key, $message = '')
    {
        if (! is_array($key)) {
            $key = array($key => $message);
        }

        $messageBag = new MessageBag($key);
        if (is_null(self::$errors)) {
            self::$errors = $messageBag;
        } else {
            self::$errors->merge($messageBag);
        }
    }

    /**
     * 获取所有已经存储的错误消息
     * @throws \Illuminate\Support\MessageBag
     */
    public function messages()
    {
        return self::$errors;
    }

    /**
     * 抛出异常包含所有已经存储的错误消息
     * @throws \FiveSay\LaravelValidation\LaravelValidationException
     */
    public function throwAll()
    {
        throw new ValidationException(self::$errors, 1);
    }


}
