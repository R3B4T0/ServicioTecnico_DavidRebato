<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Incidencia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                ->add('insertar', SubmitType::class, 
                    array(
                        'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar cliente')
                    ),
                )
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cliente = $form->getData();

            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();
            $this->addFlash(
                'notice',
                'Cliente añadido correctamente!'
            );

            return $this->redirectToRoute('cliente');
        }
        return $this->render('cliente/insertar_cliente.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/verCliente/{id}", name="verCliente", requirements={"id"="\d+"})
     * @IsGranted("ROLE_TECNICO")
     * @param int id
     */
    public function verCliente(Cliente $cliente)
    {
        $repositorio = $this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findAll();
        return $this->render('cliente/ver_cliente.html.twig',
                            ['cliente' => $cliente,]);
    }

    /**
     * @Route("/borrarCliente/{id}", name="borrarCliente")
     * @IsGranted("ROLE_TECNICO")
     * @return Response
     */
    public function borrarCliente(Cliente $cliente): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cliente);
        $em->flush();
        $this->addFlash(
            'danger',
            'Cliente borrado correctamente.'
        );
        return $this->redirectToRoute('clientes');
    }
}
