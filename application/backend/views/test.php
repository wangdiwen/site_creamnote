
<html>
<head>
    <title>test page partion</title>
</head>
<body>

<table>
<tr>
    <td>序号</td>
    <td>作者</td>
    <td>时间</td>
</tr>

<?php foreach ($week_article as $article): ?>
<tr>
    <td><?php echo $article['article_id'] ?></td>
    <td><?php echo $article['article_author'] ?></td>
    <td><?php echo $article['article_time'] ?></td>
</tr>
<?php endforeach; ?>

<tr>
    <td><?php echo $this->pagination->create_links(); ?></td>
</tr>

</table>

</body>
</html>
