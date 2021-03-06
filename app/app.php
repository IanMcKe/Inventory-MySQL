<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Inventory.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=inventory';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array (
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('inventory' => Inventory::getAll()));
    });

    $app->post("/create", function() use ($app) {
        $inventory = new Inventory($_POST['item']);
        $inventory->save();
        return $app['twig']->render('index.html.twig',
            array('inventory' => Inventory::getAll()));
    });

    $app->post("/delete", function() use ($app) {
        Inventory::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/search", function() use ($app){
        $search = Inventory::find($_GET['search']);
        return $app['twig']->render('search.html.twig', array('search' => $search, 'search_term' => $_GET['search']));
    });

    return $app;
 ?>
