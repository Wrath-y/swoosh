<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<form action="/<?php APP_PATH ?>item/update" method="post">
    <input type="text" name="value" value="<?php echo $item['item_name'] ?>">
    <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
    <input type="submit" value="修改">
</form>

<a class="big" href="/item/index">返回</a>