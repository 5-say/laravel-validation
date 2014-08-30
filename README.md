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
        'name' => 'required',
    ));
    
    // 验证通过后的其它操作 ...

} catch (FiveSay\ValidationException $e) {
    return Redirect::back()->withErrors($e->errors);
}
```

## 自定义验证消息

```php
Validation::make(
    array(
        'name' => 'required',
    ),
    array(
        'name.required' => '自定义验证消息',
    )
);
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

## 实际项目中的代码节选，是时候开始引入异常化编程了！

```php
    /**
     * 创建
     * @return Response
     */
    public function store()
    {
        try {
            # 表单验证
            Validation::make(array(
                'account'          => 'required|between:3,50|unique:users',
                'password'         => 'required|between:5,32',
                'password_confirm' => 'required|same:password',
                'name'             => 'required|min:2',
                'mobiles'          => 'multi_mobile',
            ));

            # 创建使用者账号
            $user = User::create(
                array('activated' => true) // 强制激活
                + Input::only('account', 'password', 'name')
            )->setGroupTo('Reception');

            # 创建员工信息
            $staff = Staff::create(
                array(
                    'user_id' => $user->id,
                    'model'   => 'Reception',
                )
                + Input::only('name', 'mobiles')
            );

            # 创建前台
            $reception = Reception::create(
                array(
                    'user_id'  => $user->id,
                    'staff_id' => $staff->id,
                )
                + Input::only('name')
            );

            # 操作成功
            return Redirect::route('home')->withSuccess('操作成功');

        } catch (FiveSay\ValidationException $e) {
            return Redirect::back()->withErrors($e->errors);
        } catch (UserSaveFailException $e) {
            return Redirect::back()->withError('账号信息写入失败');
        } catch (StaffSaveFailException $e) {
            return Redirect::back()->withError('员工信息写入失败');
        } catch (ReceptionSaveFailException $e) {
            return Redirect::back()->withError('前台信息写入失败');
        }
    }
```