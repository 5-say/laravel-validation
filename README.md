laravel-validation
==================

- 异常化表单验证

---

> 合理的利用异常化编程方法，可以使代码可维护性大幅提高。

## 使用前的准备

在 composer.json 文件中申明依赖：

```json
"five-say/laravel-validation": "1.*"
```

在 `/app/config/app.php` 中设置“别名”

```php
'aliases' => array(
    ...
    'Validation' => 'FiveSay\LaravelValidation\Facade', // 异常化表单验证
),
```

## 使用方法

```php
try {
    Validation::make(array(
        'name' => 'required|in:2'
    ));
    
    // 验证通过后的其它操作 ...

} catch (FiveSay\ValidationException $e) {
    return Redirect::back()->withErrors($e->errors);
}
```

## 特殊用法（直接抛出异常）

```php
    Validation::throwIt('name', 'test error message.');
```

```php
    Validation::throwIt(array(
        'name'  => 'test error message.',
        'email' => 'test error message.',
    ));
```
