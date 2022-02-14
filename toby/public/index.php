<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './src/connexion.php';
$app = new \Slim\App;

$app-> get('/test',function(Request $request,Response $response, array $params){
    echo('hello juditth');
});
//pour afficher les elements de la table dans l'api
$app->get('/products',function(Request $request,Response $response, array $params)use ($db){
	// var_dump(' la liste des produits');
	$stmt=$db->query('select * from products');
	$rs=$stmt->fetchAll();
	echo(json_encode($rs));

});
$app->post('/products',function(Request $request,Response $response, array $params)use ($db)
{

	$body=$request->getbody();
	$data = json_decode($body,true);
	$name=$data['name'];
	$designation=$data['designation'];
	$price=$data['price'];
// $name=$request->getparam('name');
// $designation=$request->getparam('designation');
// $price=$request->getparam('price');

$stmt=$db->prepare("INSERT INTO products(name,designation,price) VALUES(:name,:designation,:price)");
$stmt->execute([
	'name'=>$name,
	'designation'=>$designation,
	'price'=>$price

]);
echo('insertion reussi'.$name .$designation  .$price);
// echo('vous envoyer :' .$name .$designation  .$price);
});


// $app->get('/products/{id}',function(Request $request,Response $response, array $params){
// 	var_dump('le parametre est',$params['id']);

// });
$app->put('/products/{id}',function(Request $request,Response $response, array $params)use ($db)
{
	$body=$request->getbody();
	$data = json_decode($body,true);
	$name=$data['name'];
	$designation=$data['designation'];
	$price=$data['price'];
$id=$params['id'];

$stmt=$db->prepare("UPDATE  products SET name=:name,designation=:designation,price=:price WHERE id=:id");
$stmt->execute([
	'name'=>$name,
	'designation'=>$designation,
	'price'=>$price,
	'id'=>$id

]);
echo('modification reussi'.$name .$designation  .$price);
// echo('vous envoyer :' .$name .$designation  .$price);
});

$app->delete('/products/{id}',function(Request $request,Response $response, array $params)use ($db)
{
// $name=$request->getparam('name');
// $designation=$request->getparam('designation');
// $price=$request->getparam('price');
$id=$params['id'];

$stmt=$db->prepare("DELETE   FROM products  WHERE id=:id");
$stmt->execute([

	'id'=>$id

]);
echo('supression reussi avec succes le produit:' .$id);
// echo('vous envoyer :' .$name .$designation  .$price);
});
$app->run();


