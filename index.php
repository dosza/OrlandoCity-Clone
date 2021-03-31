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

        header_remove();
        header('Content-Type: application/json');

        $string_query = "SELECT id_prod, nome_prod_longo,foto_principal,preco FROM tb_produtos WHERE preco_promorcional > 0 ORDER BY  preco_promorcional DESC LIMIT 3;";
        $sql = new Sql();
        $data = $sql->select($string_query);


        foreach ($data as &$produto) {
            $preco = $produto['preco'];
            $centavos = explode(".",$preco);
            $produto['preco'] = number_format($preco,0,",",".");
            $produto['centavos'] = end($centavos);
            $produto['parcelas'] = 10;
            $produto['parcela'] = number_format($preco/$produto['parcelas'],2, ",", ".");
            $produto['total'] = number_format($preco, 2, ",", ".");
        }
        echo json_encode($data);
    }
);


$app->get('/produtos-mais-buscados', 
    function (){

        header_remove();
        header('Content-Type: application/json');
        $string_query="
        SELECT 
        tb_produtos.id_prod,
        tb_produtos.nome_prod_curto,
        tb_produtos.nome_prod_longo,
        tb_produtos.codigo_interno,
        tb_produtos.id_cat,
        tb_produtos.preco,
        tb_produtos.peso,
        tb_produtos.largura_centimetro,
        tb_produtos.altura_centimetro,
        tb_produtos.quantidade_estoque,
        tb_produtos.preco_promorcional,
        tb_produtos.foto_principal,
        tb_produtos.visivel,
        cast(avg(review) as dec(10,2)) as media, 
        count(id_prod) as total_reviews
        FROM tb_produtos 
        INNER JOIN tb_reviews USING(id_prod) 
        GROUP BY 
        tb_produtos.id_prod,
        tb_produtos.nome_prod_curto,
        tb_produtos.nome_prod_longo,
        tb_produtos.codigo_interno,
        tb_produtos.id_cat,
        tb_produtos.preco,
        tb_produtos.peso,
        tb_produtos.largura_centimetro,
        tb_produtos.altura_centimetro,
        tb_produtos.quantidade_estoque,
        tb_produtos.preco_promorcional,
        tb_produtos.foto_principal,
        tb_produtos.visivel
        LIMIT 4;
        ";

        $sql = new Sql();
        $data = $sql->select($string_query);


        foreach ($data as &$produto) {
            $preco = $produto['preco'];
            $centavos = explode(".",$preco);
            $produto['preco'] = number_format($preco,0,",",".");
            $produto['centavos'] = end($centavos);
            $produto['parcelas'] = 10;
            $produto['parcela'] = number_format($preco/$produto['parcelas'],2, ",", ".");
            $produto['total'] = number_format($preco, 2, ",", ".");
        }

        echo json_encode($data);


});


$app->get('/produto-:id_prod',
    function ($id_prod){
        $string_query = "SELECT id_prod, nome_prod_longo,foto_principal,preco FROM tb_produtos WHERE id_prod = $id_prod";
        $sql = new Sql();
        $produtos = $sql->select($string_query);
        
        $produto = $produtos[0];
        $preco = $produto['preco'];
        $centavos = explode(".",$preco);
        $produto['preco'] = number_format($preco,0,",",".");
        $produto['centavos'] = end($centavos);
        $produto['parcelas'] = 10;
        $produto['parcela'] = number_format($preco/$produto['parcelas'],2, ",", ".");
        $produto['total'] = number_format($preco, 2, ",", ".");


        require_once("view/shop-produto.php");

});


$app->get('/cart', 
    function(){
        require_once('view/cart.php');

});


$app->get('/carrinho-dados', 
    function(){
        $request_body  = json_decode(file_get_contents('php://input'),true);
        var_dump($request_body);

});

$app->post('/carrinho', 
    function(){
        $request_body  = json_decode(file_get_contents('php://input'),true);
        var_dump($request_body);

});


$app->run();

?>
