<?php
require 'inc/Slim-2.x/Slim/Slim.php';
require 'inc/Sql.php'; 

session_start();   

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

        $string_query = "SELECT id_prod, nome_prod_longo,foto_principal,preco FROM tb_produtos WHERE preco_promorcional > ? ORDER BY  preco_promorcional DESC LIMIT 3;";
        $sql = new SqlPreparated();
        $data = $sql->select($string_query,'i',array(0));


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
        $string_query = "SELECT id_prod, nome_prod_longo,foto_principal,preco FROM tb_produtos WHERE id_prod = ?";
        $sql = new SqlPreparated();
        $produtos = $sql->select($string_query,'i',array($id_prod));
        
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

        $sql = new SqlPreparated();

        $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
        $carrinho = $result[0];
        $carrinho['total_car'] = number_format((float)$carrinho['total_car'],2,',','.');
        $carrinho['subtotal_car'] =number_format((float)$carrinho['subtotal_car'],2,',','.');
        $carrinho['frete_car'] = number_format((float)$carrinho['frete_car'],2,',','.');

        $id_car = $carrinho['id_car'];


        $sql = new SqlPreparated();

        $string_query ="CALL sp_carrinhosprodutos_list(?)";
        $carrinho['produtos'] = $sql->select($string_query,'i',array($id_car));
        echo json_encode($carrinho);

});


$app->get('/carrinhoAdd-:id_prod', function ($id_prod){
    
    $sql = new SqlPreparated();
    
    $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
    
    $carrinho = $result[0];
    
    $sql  = new SqlPreparated();
    
    $string_query =    'CALL sp_carrinhosprodutos_add(?,?)';
    
    $sql->query($string_query,'ii',array(
            (int) $carrinho['id_car'],
            (int) $id_prod
        )
    );

    header("Location: cart");
    exit;

});

$app->delete("/carrinhoRemoveAll-:id_prod", function($id_prod){
     $sql = new SqlPreparated();
    
    $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
    
    $carrinho = $result[0];
    
    $sql  = new SqlPreparated();

    $sql->query("CALL sp_carrinhosprodutostodos_rem(?,?)",'ii',array(
        $carrinho['id_car'],
        $id_prod
    ));
    echo json_encode(array('success' =>  true));

});


$app->post('/carrinho-produto', function(){
    $data = json_decode(file_get_contents("php://input"),true);
    $sql = new SqlPreparated();
    
    $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
    $carrinho = $result[0];

    $sql = new SqlPreparated();

    $sql->query("CALL sp_carrinhosprodutos_add(?,?)",'ii',array(
            (int) $carrinho['id_car'],
            (int) $data['id_prod']
        )
    );

    echo json_encode(array('success'=> true));
    


});


$app->delete('/carrinho-produto', function (){ 
    $data = json_decode(file_get_contents("php://input"),true);
    $sql = new SqlPreparated();
    
    $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
    $carrinho = $result[0];

    $sql = new SqlPreparated();

    $sql->query("CALL sp_carrinhosprodutos_rem(?,?)",'ii',array(
            (int) $carrinho['id_car'],
            (int) $data['id_prod']
        )
    );

    echo json_encode(array('success'=> true));
    

});

$app->get("/calcula-frete-:cep", function ($cep){
    require_once("inc/php-calcular-frete-correios-master//Frete.php");

     $sql = new SqlPreparated();
    
    $result = $sql->select("CALL sp_carrinhos_get(?)",'i',array(session_id()));
    $carrinho = $result[0];


    $sql = new SqlPreparated();
    $produtos = $sql->select("CALL sp_carrinhosprodutosfrete_list(?)", 'i',array($carrinho['id_car']));
    $p_valor['peso'] =0;
    $p_valor['comprimento'] = 0;
    $p_valor['altura'] = 0;
    $p_valor['largura'] = 0;
    $p_valor['valor'] = 0;

    foreach ($produtos as $produto) {

        $p_valor['peso']=+ $produto['peso'];
        $p_valor['comprimento']=+ $produto['comprimento'];
        $p_valor['altura']=+ $produto['altura'];
        $p_valor['largura']=+ $produto['largura'];
        $p_valor['valor']=+ $produto['preco'];
    }

    $cep = trim(str_replace('-', '', $cep));
    $valor = $p_valor['valor'];
    $frete = new Frete(
        $CEPorigem ='07170350',
        $CEPdestino = $cep,
        $peso = $p_valor['peso'],
        $comprimento = $p_valor['comprimento'],
        $altura = $p_valor['altura'],
        $largura = $p_valor['largura'],
        $valor
    );

    $sql = new SqlPreparated();
    $string_query = "UPDATE tb_carrinhos SET cep_car = ?, frete_car = ?, prazo_car = ? WHERE id_car = ?";


    $sql->query($string_query,'sdii',array(
        $cep,
        $frete->getValor(),
        $frete->getPrazoEntrega(),
        $carrinho['id_car']
    ));

 
   echo json_encode(array(
       "success"=>true
    ));
});


$app->run();

?>
