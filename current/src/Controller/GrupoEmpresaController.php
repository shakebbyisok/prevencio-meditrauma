<?php

namespace App\Controller;

use App\Entity\Centro;
use App\Entity\EmpresaGrupo;
use App\Entity\GrupoEmpresa;
use App\Form\CentroType;
use App\Form\GrupoEmpresaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class GrupoEmpresaController extends AbstractController {

    public function createGrupoEmpresa(Request $request, TranslatorInterface $translator)
    {

        $session = $request->getSession();
        $privilegios = $session->get('privilegiosRol');
        if(!is_null($privilegios)){
            if(!$privilegios->getAddGrupoEmpresaSn()){
                return $this->redirectToRoute('error_403');
            }
        }

        $grupoEmpresa = new GrupoEmpresa();
        $form = $this->createForm(GrupoEmpresaType::class, $grupoEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $grupoEmpresa = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($grupoEmpresa);
            $em->flush();

            $traduccion = $translator->trans('TRANS_CREATE_OK');
            $this->addFlash('success',  $traduccion);
            return $this->redirectToRoute('tecnico_grupo_empresa_update', array('id' => $grupoEmpresa->getId()));
        }
        return $this->render( 'grupoempresa/edit.html.twig', array('empresasGrupo' => null, 'form' => $form->createView()) );
    }

    public function showGrupoEmpresa(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $privilegios = $session->get('privilegiosRol');
        if(!is_null($privilegios)){
            if(!$privilegios->getGrupoEmpresaSn()){
                return $this->redirectToRoute('error_403');
            }
        }

        $grupoEmpresa = $em->getRepository('App\Entity\GrupoEmpresa')->findBy(array('anulado' => false));

        return new Response($this->renderView('grupoempresa/show.html.twig', array('grupoEmpresa' => $grupoEmpresa)));
    }

    public function updateGrupoEmpresa(Request $request, $id, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $privilegios = $session->get('privilegiosRol');
        if(!is_null($privilegios)){
            if(!$privilegios->getEditGrupoEmpresaSn()){
                return $this->redirectToRoute('error_403');
            }
        }

        $grupoEmpresa = $em->getRepository('App\Entity\GrupoEmpresa')->find($id);

        if (!$grupoEmpresa) {
            throw $this->createNotFoundException(
                'El grupo de empresa con id ' . $id.' no existe'
            );
        }

        //Buscamos las empresas del grupo
        $empresasGrupo = $em->getRepository('App\Entity\Empresa')->findBy(array('grupoEmpresa' => $grupoEmpresa, 'anulado' => false));

        $form = $this->createForm(GrupoEmpresaType::class, $grupoEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $grupoEmpresa = $form->getData();
            $em->persist($grupoEmpresa);
            $em->flush();

            $traduccion = $translator->trans('TRANS_UPDATE_OK');
            $this->addFlash('success', $traduccion);

            return $this->redirectToRoute('tecnico_grupo_empresa_update', array('id' => $id));
        }

        return $this->render('grupoempresa/edit.html.twig',  array('form' => $form->createView(), 'empresasGrupo' => $empresasGrupo));
    }

    public function deleteGrupoEmpresa(Request $request, $id, TranslatorInterface $translator){

        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $privilegios = $session->get('privilegiosRol');
        if(!is_null($privilegios)){
            if(!$privilegios->getDeleteGrupoEmpresaSn()){
                return $this->redirectToRoute('error_403');
            }
        }

        $grupoEmpresa = $em->getRepository('App\Entity\GrupoEmpresa')->find($id);

        //Buscamos las empresas del grupo
        $empresasGrupo = $em->getRepository('App\Entity\EmpresaGrupo')->findBy(array('grupoEmpresa' => $grupoEmpresa, 'anulado' => false));

        foreach ($empresasGrupo as $empresaGrupo){
            $empresaGrupo->setAnulado(true);
            $em->persist($empresaGrupo);
            $em->flush();
        }

        $grupoEmpresa->setAnulado(true);
        $em->persist($grupoEmpresa);
        $em->flush();

        $traduccion = $translator->trans('TRANS_DELETE_OK');
        $this->addFlash('success', $traduccion);

        return $this->redirectToRoute('tecnico_grupo_empresa_show');
    }

    public function addEmpresaGrupo(Request $request, TranslatorInterface $translator){
        $em = $this->getDoctrine()->getManager();

        $empresaId = $_REQUEST['empresaId'];
        $grupoEmpresaId = $_REQUEST['grupoEmpresaId'];

        $empresa = $em->getRepository('App\Entity\Empresa')->find($empresaId);
        $grupoEmpresa = $em->getRepository('App\Entity\GrupoEmpresa')->find($grupoEmpresaId);

        $empresa->setGrupoEmpresa($grupoEmpresa);
        $em->persist($empresa);
        $em->flush();

        $traduccion = $translator->trans('TRANS_CREATE_OK');
        $this->addFlash('success', $traduccion);

        $data = array();
        array_push($data, "OK");

        return new JsonResponse($data);
    }

    public function deleteEmpresaGrupo(Request $request, TranslatorInterface $translator){
        $em = $this->getDoctrine()->getManager();

        $empresaId = $_REQUEST['empresaId'];

        $empresa = $em->getRepository('App\Entity\Empresa')->find($empresaId);
        $empresa->setGrupoEmpresa(null);
        $em->persist($empresa);
        $em->flush();

        $traduccion = $translator->trans('TRANS_DELETE_OK');
        $this->addFlash('success', $traduccion);

        $data = array();
        array_push($data, "OK");

        return new JsonResponse($data);
    }

}