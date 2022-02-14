<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './src/connexion.php';
$app = new \Slim\App;

$mw = function($request,$response,$next){
    $body =$request->getbody();
    $data=json_decode($body,true);
    

    if($data['age']<=0){
        $response->getbody()->write('age incorrect');

    }
    else{
        $response=$next($request,$response);
    }
     return $response;
};

$app->get('/personnes',function(Request $request,Response $response, array $params) use ($db)
{
	
	$stmt=$db->query('select * from personnes');
	$rs=$stmt->fetchAll();
	echo(json_encode($rs));
    echo("la liste des personnes" .$name .$sexe .$age);


});
$app->post('/personnes',function(Request $request,Response $response, array $params)use ($db)
{

	$body=$request->getbody();
	$data = json_decode($body,true);
	$name=$data['name'];
	$sexe=$data['sexe'];
	$age=$data['age'];


$stmt=$db->prepare("INSERT INTO personnes(name,sexe,age) VALUES(:name,:sexe,:age)");
$stmt->execute([
	'name'=>$name,
	'sexe'=>$sexe,
	'age'=>$age

])->add($mw);
echo('insertion reussi'.$name .$sexe  .$age);
});



$app->run();


