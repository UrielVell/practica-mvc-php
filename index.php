<?php
use Slim\Factory\AppFactory;
use DI\Container;
use Psr\Container\ContainerInterface;
use App\Settings\Settings;
use App\Data\DataContext;
use Slim\Views\Twig;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();

$container->set('settings', function(){
    $settings = require __DIR__ .'/app/settings.php';
    return new Settings($settings);
});

$container->set('view', function(){
    return Twig::create('src/Views/', ['cache' => false]);
});

$container->set('db', function(ContainerInterface $container){
    return new DataContext($container->get('settings')->get());
});

$app = AppFactory::createFromContainer($container);

$app->addRoutingMiddleware();

$routes = require __DIR__ . '/app/routes.php';
$routes($app);

$app->addErrorMiddleware(true,true, true);

$app->run();

?>
