<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController {

    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
        $code = $exception->getStatusCode();
        $exception = $exception->getMessage();

        switch ($code){
            case 505:
                $msg = 'Ops, se ha producido un error en el servidor.';
                break;
            case 404:
                $msg = 'Ops, no se ha encontrado la página.';
                break;
            case 403:
                $msg = 'Ops, no tienes autorización para acceder al módulo.';
                break;
            default:
                $msg = 'Ops, se ha producido un error en el servidor.';
                break;
        }
        return new Response($this->renderView('error/error.html.twig', array('code' => $code, 'msg' => $msg, 'exception' => $exception)));
    }

    public function error403(Request $request){
        return new Response($this->renderView('error/error403.html.twig'));
    }

    public function errorDescargaResumenRevision(Request $request){
        return new Response($this->renderView('error/errorDownResumenRevision.html.twig'));
    }
}