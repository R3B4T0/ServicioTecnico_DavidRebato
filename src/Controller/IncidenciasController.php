<?php

namespace App\Controller;

use App\Entity\Incidencia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class IncidenciasController extends AbstractController
{
    /**
     * @Route("/incidencias", name="incidencias")
     * IsGranted("ROLE_TECNICO)
     */
    public function index(): Response
    {
        $repositorio = $this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findAll();
        return $this->render('incidencias/index.html.twig',
                        ['incidencias' => $incidencias]);
    }
}
