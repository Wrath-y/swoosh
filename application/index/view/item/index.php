<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>


<form action="add" method="post">
    <input type="text" value="点击添加" onclick="this.value=''" name="value">
    <input type="submit" value="添加">
</form>
<br/><br/>

<?php $number = 0; ?>
 
<?php foreach ($items as $item): ?>
    <a class="big" href="/<?php APP_PATH ?>item/read/<?php echo $item['id'] ?>" title="点击修改">
        <span class="item">
            <?php echo ++$number ?>
            <?php echo $item['item_name'] ?>
        </span>
    </a>
    ----
    <a class="big" href="/<?php APP_PATH ?>item/delete/<?php echo $item['id']?>">删除</a>
<br/>
<?php endforeach ?>
</body>
</html>