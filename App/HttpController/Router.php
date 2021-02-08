<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter{
    function initialize(RouteCollector $routeCollector){
        $routeCollector->get('/user', '/Api/Index/index');

        $routeCollector->get('/setShowAmount','/Api/Index/setShowAmount');
        $routeCollector->get('/setClickAmount','/Api/Index/setClickAmount');
        $routeCollector->get('/setDetailsShowAmount','/Api/Index/setDetailsShowAmount');
        $routeCollector->get('/setTestClickAmount','/Api/Index/setTestClickAmount');
        $routeCollector->get('/setTestAmount','/Api/Index/setTestAmount');
        $routeCollector->get('/setChannelTestAmount','/Api/Index/setChannelTestAmount');
        $routeCollector->get('/setTestResultShowAmount','/Api/Index/setTestResultShowAmount');
        $routeCollector->get('/setShareAmount','/Api/Index/setShareAmount');
        $routeCollector->get('/setRecommendDetailsShowAmout','/Api/Index/setRecommendDetailsShowAmout');
        $routeCollector->get('/setRecommendDetailsTestClickAmout','/Api/Index/setRecommendDetailsTestClickAmout');
        $routeCollector->get('/setRecommendResultTestClickAmount','/Api/Index/setRecommendResultTestClickAmount');
        $routeCollector->get('/setRecommendResultShowAmount','/Api/Index/setRecommendResultShowAmount');
        $routeCollector->post('translate','api/Translate/Translate');


        $routeCollector->get('/setGameIndexShowAmount','/Api/Index/setGameIndexShowAmount');
        $routeCollector->get('/setGameDetailsShowAmount','/Api/Index/setGameDetailsShowAmount');
        $routeCollector->get('/setGamePlayShowAmount','/Api/Index/setGamePlayShowAmount');
        $routeCollector->get('/setGameIndexClickAmount','/Api/Index/setGameIndexClickAmount');
        $routeCollector->get('/setGameDetailsClickAmount','/Api/Index/setGameDetailsClickAmount');
        $routeCollector->get('/setGamePlayClickAmount','/Api/Index/setGamePlayClickAmount');
        $routeCollector->get('/setGamePlayAmount','/Api/Index/setGamePlayAmount');
        $routeCollector->get('/setGamePlay2Amount','/Api/Index/setGamePlay2Amount');

        $routeCollector->get('/setGameNewShowAmount','/Api/Index/setGameNewShowAmount');
        $routeCollector->get('/setGameNewClickAmount','/Api/Index/setGameNewClickAmount');
        $routeCollector->get('/setGameNewPlayAmount','/Api/Index/setGameNewPlayAmount');
        $routeCollector->get('/setGameHotShowAmount','/Api/Index/setGameHotShowAmount');
        $routeCollector->get('/setGameHotClickAmount','/Api/Index/setGameHotClickAmount');
        $routeCollector->get('/setGameHotPlayAmount','/Api/Index/setGameHotPlayAmount');
        $routeCollector->get('/setGameSearchShowAmount','/Api/Index/setGameSearchShowAmount');
        $routeCollector->get('/setGameSearchClickAmount','/Api/Index/setGameSearchClickAmount');
        $routeCollector->get('/setGameSearchPlayAmount','/Api/Index/setGameSearchPlayAmount');

        $routeCollector->get('/setGameCategoryShowAmount','/Api/Index/setGameCategoryShowAmount');
        $routeCollector->get('/setGameCategoryClickAmount','/Api/Index/setGameCategoryClickAmount');
        $routeCollector->get('/setGameCategoryPlayAmount','/Api/Index/setGameCategoryPlayAmount');


        $routeCollector->get('/setInvitationamountamount','/Api/Index/setInvitationamountamount');
        $routeCollector->get('/setWebhotOpenAmount','/Api/Index/setWebhotOpenAmount');

        $routeCollector->get('/setRetestAmount','/Api/Index/setRetestAmount');
        $routeCollector->get('/setTipsAmount','/Api/Index/setTipsAmount');
        $routeCollector->get('/setDownImgAmount','/Api/Index/setDownImgAmount');

        $routeCollector->post('/setShowAmount','/Api/Index/setShowAmount');
        $routeCollector->post('/setClickAmount','/Api/Index/setClickAmount');
        $routeCollector->post('/setDetailsShowAmount','/Api/Index/setDetailsShowAmount');
        $routeCollector->post('/setTestResultShowAmount','/Api/Index/setDetailsShowAmount');
    }
}