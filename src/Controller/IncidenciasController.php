<?php

namespace App\Controller;

use App\Entity\Incidencia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IncidenciasController extends AbstractController
{
    /**
     * @Route("/incidencias", name="incidencias")
     */
    public function index(): Response
    {
        $repositorio = $this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findAll();
        return $this->render('incidencias/index.html.twig',
                        ['incidencias' => $incidencias]);
    }
}
