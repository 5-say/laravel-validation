<?php
/*
|--------------------------------------------------------------------------
| 模型观察者
|--------------------------------------------------------------------------
| 模型事件触发顺序
|--------------------------------------------------------------------------
|
| 创建 & 更新
|          |-- creating -- created --|
| saving --|                         |-- saved
|          |-- updating -- updated --|
| 
| 软删除 & 强制删除
|            |-- softing -- softed --|
| deleting --|                       |-- deleted
|            |-- forcing -- forced --|
| 
| 恢复
| restoring -- saving -- updating -- updated -- saved -- restored
| 
*/
class DemoObserver
{

    public function saving($model)
    {
        // ...
    }
    
}
