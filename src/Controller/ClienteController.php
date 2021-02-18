<?php

namespace App\Controller;

use App\Entity\Cliente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ClienteController extends AbstractController
{
    /**
     * @Route("/cliente", name="cliente")
     * @IsGranted("ROLE_TECNICO")
     */
    public function index(): Response
    {
        $repositorio = $this->getDoctrine()->getRepository(Cliente::class);
        $clientes = $repositorio->findAll();
        return $this->render('cliente/index.html.twig',
                        ['clientes' => $clientes]);
    }


    /**
     * @Route("/nuevoCliente", name="nuevoCliente")
     * @IsGranted("ROLE_TECNICO")
     */
    public function nuevoCliente(Request $request): Response
    {
        $cliente = new Cliente();
        $form = $this->createFormBuilder($cliente)
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('telefono', TextType::class)
                ->add('direccion', TextType::class)
                ->add('insertar', SubmitType::class, ['label' => 'Insertar Cliente'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cliente = $form->getData();

            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            return $this->redirectToRoute('inicio');
        }
        return $this->render('cliente/index.html.twig', [
            'controller_name' => 'ClienteController',
        ]);
    }

     /**
     * @Route("/verCliente", name="verCliente")
     * @IsGranted("ROLE_TECNICO")
     */
    public function verCliente(Cliente $cliente)
    {
        return $this->render('cliente/ver_cliente.html.twig',
                            ['cliente' => $cliente]);
    }
}
