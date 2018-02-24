<?php
$config = [
    'host'=>'127.0.0.1',
    'db'=>'test',
    'user'=>'root',
    'password'=>'root',
];


$sqls = [
    'tables'=>'SHOW TABLE STATUS FROM {db}',
    'columns'=>'SHOW FULL COLUMNS FROM {table}'
];

$dsn = 'mysql:host='.$config['host'].';dbname='.$config['db'];
//echo $dsn;
try{
    $pdo = new PDO($dsn,$config['user'],$config['password']);
}catch (Exception $e){
    echo $e->getMessage();
    exit;
}
$pdo->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
$pdo->exec('set names utf8');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
$tables = $pdo->query(str_replace('{db}',$config['db'],$sqls['tables']))->fetchAll();

?>
<html>
<head>
    <title>数据字典-<?= $config['db'] ?></title>
    <style>
        #main{
            width: 100%;
            min-width: 1100px;
            margin: 15px 0;
            position: absolute;
            padding: 0;
        }
        #main div{
            width: 100%;
            text-align: center;
            margin: 30px 0;
        }
        #main div h3{
            text-align: left;
        }
        #main table{
            border-collapse: collapse;
            width: 100%;
        }
        #main table th,#main table td{
            text-align: center;
            border: solid thin #CCC;
        }
        #main table th:first-child{
            width: 220px;
        }
        #main table th:nth-child(2){
            width: 250px;
        }
        #main table th:nth-child(3){
            width: 80px;
        }
        #main table th:nth-child(4){
            width: 250px;
        }
        #main table th:last-child{
            width: auto;
        }
        #main table th{
            background-color: #DFDFDF;
        }
        #main table tr:hover{
            background-color: #5CA9FF;
        }
    </style>
</head>
<body>
<div id="main">
    <h1>数据库：<?= $config['db'] ?></h1>
    <?php
    foreach ($tables as $table){
        echo '<div>';
        echo '<h3>'.$table['name'].(empty($table['comment']) ? '':'（'.$table['comment'].'）').'</h3>';
        $colums = $pdo->query(str_replace('{table}',$table['name'],$sqls['columns']))->fetchAll();
        echo '<table><tr><th>字段</th><th>类型</th><th>空</th><th>默认</th><th>注释</th></tr>';
        foreach ($colums as $colum){
            echo '<tr>';
            echo '<td>'.$colum['field'].'</td>';
            echo '<td>'.$colum['type'].'</td>';
            echo '<td>'.($colum['null']=='NO' ? 'N':'Y').'</td>';
            echo '<td>'.$colum['default'].'</td>';
            echo '<td>'.$colum['comment'].'</td>';
            echo '</tr>';
        }
        echo '</table></div>';
    }
    ?>
</div>
</body>
</html>
