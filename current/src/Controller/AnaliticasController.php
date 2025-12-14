<?php

namespace App\Controller;

use App\Entity\AnaliticasLog;
use phpseclib3\Net\SFTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Logger;

class AnaliticasController extends AbstractController
{
    public function recuperaAnaliticas(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $gdocConfig = $this->getDoctrine()->getRepository('App\Entity\GdocConfig')->find(1);
        $carpetaResultadosAnaliticasTmp = $gdocConfig->getCarpetaResultadoAnaliticaTmp();
        $rutaGdoc = $gdocConfig->getRuta();
        $rutaTmp = $rutaGdoc . $carpetaResultadosAnaliticasTmp;

        $analiticasConfig = $this->getDoctrine()->getRepository('App\Entity\AnaliticasConfig')->find(1);
        $url = $analiticasConfig->getUrl();
        $puerto = $analiticasConfig->getPuerto();
        $usuario = $analiticasConfig->getUsuario();
        $password = $analiticasConfig->getPassword();
        $carpeta = $analiticasConfig->getCarpeta();
        $carpetaResultadosAnaliticas = $analiticasConfig->getCarpetaResultadoAnalitica();

        $countDescargados = 0;
        $hoy = new \DateTime('now');

        //Connectamos con el servidor SFTP
        $sftp = $this->sftpConnect($url, $usuario, $password);

        //Guardamos los resultados en la carpeta temporal
        $this->recuperaResultados($sftp, $url, $carpeta, $rutaTmp);

        //Buscamos las analiticas que tienen el numero de referencia informado y no se ha recuperado el documento
        $query = "select id from revision where numero_peticion is not null and fecha_recuperacion_resultado is null and anulado = false";
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($query);
        $stmt->execute();
        $revisiones = $stmt->fetchAll();

        foreach ($revisiones as $r) {

            $analiticasLog = new AnaliticasLog();
            $analiticasLog->setDtcrea(new \DateTime());

            $revisionId = $r['id'];
            $revision = $this->getDoctrine()->getRepository('App\Entity\Revision')->find($revisionId);
            $numeroPeticion = $revision->getNumeroPeticion();

            $analiticasLog->setRevision($revision);

            if (!file_exists($rutaTmp . "/$numeroPeticion.pdf")) {
                $analiticasLog->setError("Fichero $numeroPeticion.pdf no encontrado");
                $analiticasLog->setDescargado(false);
            } else {
                if (!file_exists("upload/media/$carpetaResultadosAnaliticas/$revisionId")) {
                    mkdir("upload/media/$carpetaResultadosAnaliticas/$revisionId");
                }

                rename($rutaTmp . "/$numeroPeticion.pdf", "upload/media/$carpetaResultadosAnaliticas/$revisionId/$numeroPeticion.pdf");

                $analiticasLog->setDescargado(true);
                $analiticasLog->setNombreFichero($numeroPeticion . '.pdf');
                $revision->setFechaRecuperacionResultado(new \DateTime());
                $countDescargados++;

                $revision->setAnalitica(true);
                $em->persist($revision);
                $em->flush();
            }
            $em->persist($analiticasLog);
            $em->flush();
        }


        $logresponse = $hoy->format('d/m/Y H:i:s') . " (PREVENCION)";
        $logresponse .= " - Resultados recuperados: ";
        $logresponse .= $countDescargados;

        return new Response($logresponse);
    }

    function sftpConnect($url, $usuario, $password)
    {
        $sftp = new SFTP($url);

        if (!$sftp->login($usuario, $password)) {
            $sftp->getSFTPLog();
        }

        return $sftp;
    }

    function recuperaResultados($sftp, $url, $carpeta, $rutaTmp)
    {
        $sftp->chdir($url . '/' . $carpeta);
        $files = $sftp->nlist('.', true);
        foreach ($files as $file) {
            if (!str_contains($file, $carpeta)) {
                continue;
            }

            if (str_contains($file, $carpeta)) {
                $parts = explode("/", $file);
                if (isset($parts[1]) && $parts[1] != '.' && $parts[1] != '..') {
                    if (!file_exists($rutaTmp . "/" . basename($file))) {
                        $sftp->get($file, $rutaTmp . "/" . basename($file));
                        $sftp->delete($file);
                    }
                }
            }
        }
    }
}
