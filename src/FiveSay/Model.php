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
        if ($result = parent::find($id, $columns)) {
            return $result;
        }

        $modelClassName = get_called_class();
        $e = $modelClassName.'NotFindException';
        if (! class_exists($e)) {
            eval('class '.$e.' extends \FiveSay\ModelNotFoundException {}');
        }
        
        throw with(new $e)->setModel($modelClassName);
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

    /**
     * 复写系统方法
     * @return void
     */
    protected function performDeleteOnModel()
    {
        $e = get_called_class().'DeleteFailException';
        # 拓展软删除事件监听
        if ($this->softDelete) {
            if ($this->fireModelEvent('softing') === false) throw new $e;
            parent::performDeleteOnModel();
            $this->fireModelEvent('softed', false);
        } else {
            if ($this->fireModelEvent('forcing') === false) throw new $e;
            parent::performDeleteOnModel();
            $this->fireModelEvent('forced', false);
        }
    }

    /**
     * 复写系统方法
     * @return array
     */
    public function getObservableEvents()
    {
        # 拓展软删除事件监听
        return array_merge(
            array('softing', 'softed', 'forcing', 'forced'),
            parent::getObservableEvents()
        );
    }


}