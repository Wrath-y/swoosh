<?php
namespace app\index\controller;

use zkami\frame\Controller;

class ItemController extends Controller
{
    // 首页方法，测试框架自定义DB查询
    public function index()
    {
        $items = (new \app\index\model\Item)->selectAll();
        $this->assign('title', '全部条目');
        $this->assign('items', $items);
        $this->view();
    }
    
    // 添加记录，测试框架DB记录创建（Create）
    public function add()
    {
        $data['item_name'] = $_POST['value'];
        $count = (new \app\index\model\Item)->add($data);

        $this->assign('title', '添加成功');
        $this->assign('count', $count);
        $this->view();
    }
    
    // 查看记录，测试框架DB记录读取（Read）
    public function read($id = null)
    {
        $item = (new \app\index\model\Item)->select($id);

        $this->assign('title', '正在查看' . $item['item_name']);
        $this->assign('item', $item);
        $this->view();
    }
    
    // 更新记录，测试框架DB记录更新（Update）
    public function update()
    {
        $data = array('id' => $_POST['id'], 'item_name' => $_POST['value']);
        $count = (new \app\index\model\Item)->update($data['id'], $data);

        $this->assign('title', '修改成功');
        $this->assign('count', $count);
        $this->view();
    }
    
    // 删除记录，测试框架DB记录删除（Delete）
    public function delete($id = null)
    {
        $count = (new \app\index\model\Item)->delete($id);

        $this->assign('title', '删除成功');
        $this->assign('count', $count);
        $this->view();
    }
}