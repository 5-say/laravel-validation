<?php namespace FiveSay;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * 引导
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        # 注册模型事件观察者
        $observer = get_called_class().'Observer';
        if (class_exists($observer)) {
            self::observe(new $observer);
        }
    }

    /**
     * 复写系统方法
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|Collection|static
     */
    public static function find($id, $columns = array('*'))
    {
        # 异常化编程
        if ($result = parent::find($id, $columns))
            return $result;
        $e = get_called_class().'NotFindException';
        throw new $e;
    }

    /**
     * 复写系统方法
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        # 异常化编程
        if ($saved = parent::save($options))
            return $saved;
        $e = get_called_class().'SaveFailException';
        throw new $e;
    }

    /**
     * 复写系统方法
     * @return bool|null
     */
    public function delete()
    {
        # 异常化编程
        if ($deleted = parent::delete())
            return $deleted;
        $e = get_called_class().'DeleteFailException';
        throw new $e;
    }


}

# 需手动为每个模型编写3个异常类，插入在模型文件的最底部。
# 下面这3行就是 User 模型的例子：

// class UserNotFindException    extends Exception {}
// class UserSaveFailException   extends Exception {}
// class UserDeleteFailException extends Exception {}
