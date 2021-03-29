<?php
require 'inc/Slim-2.x/Slim/Slim.php';
require 'inc/configuration.php';    

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get(
    '/',
    function () {
            require_once('view/index.php');
    }
);


$app->get(
    '/videos', 
    function (){

        require_once('view/videos.php');
    }
);


$app->get('/shop',
    function (){
        require_once('view/shop.php');  
    }
);

$app->get('/produtos', 

    function(){

        $string_query="SELECT nome_prod_longo,foto_principal,preco FROM tb_produtos WHERE preco_promorcional > 0 ORDER BY  preco_promorcional DESC LIMIT 3;";
        $sql = new Sql();
        $data = $sql->select($string_query);
        echo json_encode($data);
    }
);

$app->run();

?>
