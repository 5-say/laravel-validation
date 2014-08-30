<?php namespace FiveSay\LaravelValidation;

use Input;
use Validator;
use Illuminate\Support\MessageBag;
use FiveSay\ValidationException;

class LaravelValidation
{
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


}
