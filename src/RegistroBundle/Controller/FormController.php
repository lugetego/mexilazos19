<?php

namespace RegistroBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RegistroBundle\Entity\Form;
use RegistroBundle\Form\FormType;

/**
 * Form controller.
 *
 * @Route("/form")
 */
class FormController extends Controller
{
    /**
     * Lists all Form entities.
     *
     * @Route("/", name="form_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registros = $em->getRepository('RegistroBundle:Form')->findAll();

        return $this->render('form/index.html.twig', array(
            'registros' => $registros,
        ));
    }

    /**
     * Creates a new Form entity.
     *
     * @Route("/new", name="form_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $now = new \DateTime();
        $deadline = new \DateTime('2019-11-25');
        if($now >= $deadline){
            return $this->render('form/newClosed.html.twig');
        }

        $registro = new Form();


        $form = $this->createForm('RegistroBundle\Form\FormType', $registro);
        $form->remove('cartaFile');
        $form->remove('comentarios');
        $form->remove('recomendacion');
        $form->remove('confirmado');


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $registro->setCreatedAt(new \DateTime());
            $em->persist($registro);
            $em->flush();

            // Obtiene correo y msg de la forma de contacto
            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Registro - '.$this->getParameter('evento'))
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMail()))
                ->setBcc(array('gerardo@matmor.unam.mx'))
                ->setBody($this->renderView('form/mail.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);

            if ($registro->getMailprofesor()){
            $message = \Swift_Message::newInstance()
                ->setSubject('Carta de recomendación - '. $this->getParameter('evento'))
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMailprofesor()))
                ->setBcc(array('gerardo@matmor.unam.mx'))
                ->setBody($this->renderView('form/mailprof.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);
            }

            //return $this->redirectToRoute('registro_show', array('id' => $registro->getId()));

            return $this->render('form/confirm.html.twig', array('id' => $registro->getId(),'entity'=>$registro));



            //return $this->redirectToRoute('form_show', array('id' => $registro->getId()));
        }

        return $this->render('form/new.html.twig', array(
            'form' => $form,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Form entity.
     *
     * @Route("/{id}", name="form_show")
     * @Method("GET")
     */
    public function showAction(Form $registro)
    {
        $deleteForm = $this->createDeleteForm($registro);

        return $this->render('form/show.html.twig', array(
            'form' => $registro,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to send recommendation file.
     *
     * @Route("/{slug}/{mail}/recom", name="form_recom")
     * @Method({"GET", "POST"})
     */
    public function recomAction(Request $request, Form $registro, $mail, $slug)
    {
        //$deleteForm = $this->createDeleteForm($registro);
        $editForm = $this->createForm('RegistroBundle\Form\FormType', $registro);

        $editForm->remove('nombre');
        $editForm->remove('apellidos');
        $editForm->remove('sexo');
        $editForm->remove('mail');
        $editForm->remove('institucion');
        $editForm->remove('statuses');
        $editForm->remove('status');
        $editForm->remove('profesor');
        $editForm->remove('instprofesor');
        $editForm->remove('mailprofesor');
        $editForm->remove('historialFile');
        $editForm->remove('infoadicional');
        $editForm->remove('comentarios');
        $editForm->remove('confirmado');
        $editForm->remove('titulo');
        $editForm->remove('resumen');





        $editForm->handleRequest($request);

//        else {

            if ($editForm->isSubmitted() && $editForm->isValid()) {


                $em = $this->getDoctrine()->getManager();

                $registro->setUpdatedAt(new \DateTime());

                $em->persist($registro);
                $em->flush();

                // Obtiene correo y msg de la forma de contacto
                $mailer = $this->get('mailer');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Carta de recomendación - ' .$this->getParameter('evento'))
                    ->setFrom('webmaster@matmor.unam.mx')
                    ->setTo(array($registro->getMailprofesor()))
                    ->setCc(array($registro->getMail()))
                    ->setBcc(array('gerardo@matmor.unam.mx'))
                    ->setBody($this->renderView('form/mailCarta.txt.twig', array('entity' => $registro)));
                $mailer->send($message);

                //return $this->redirectToRoute('form_edit', array('id' => $registro->getId()));
                return $this->render('form/confirmCarta.html.twig', array('id' => $registro->getId(), 'entity' => $registro));

            }
  //      }

        if( $registro->getMail() == $registro->getMailprofesor() ||  $mail != $registro->getMail() || $slug != $registro->getSlug()){

            throw $this->createNotFoundException('Existe algún problema con la información de registro favor de contactar a webmaster@matmor.unam.mx');
        }

        if( $registro->getCartaName() != null || $registro->getRecomendacion() != null)
        {
            return $this->render('form/confirmCarta.html.twig', array('id' => $registro->getId(),'entity'=>$registro));
        }

        return $this->render('form/recom.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Displays a form to send recommendation file.
     *
     * @Route("/{slug}/{mail}/confirma", name="form_confirma")
     * @Method({"GET", "POST"})
     */
    public function confirmaAction(Request $request, Form $registro, $mail, $slug)
    {
        //$deleteForm = $this->createDeleteForm($registro);
        $editForm = $this->createForm('RegistroBundle\Form\FormType', $registro);

        $editForm->remove('nombre');
        $editForm->remove('paterno');
        $editForm->remove('materno');
        $editForm->remove('sexo');
        $editForm->remove('nacimiento');
        $editForm->remove('mail');
        $editForm->remove('tel');
        $editForm->remove('procedencia');
        $editForm->remove('carrera');
        $editForm->remove('semestre');
        $editForm->remove('porcentaje');
        $editForm->remove('promedio');
        $editForm->remove('profesor');
        $editForm->remove('univprofesor');
        $editForm->remove('mailprofesor');
        $editForm->remove('historialFile');
        $editForm->remove('participado');
        $editForm->remove('evento');
        $editForm->remove('beca');
        $editForm->remove('razones');
        $editForm->remove('comentarios');
        $editForm->remove('cartaFile');
        $editForm->remove('recomendacion');
        $editForm->remove('examen');
        $editForm->remove('cursos');
        $editForm->remove('vegetariano');
        $editForm->remove('areas');



        $editForm->handleRequest($request);

//        else {

        if ($editForm->isSubmitted() && $editForm->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $registro->setUpdatedAt(new \DateTime());
            $em->persist($registro);
            $em->flush();

            // Obtiene correo y msg de la forma de contacto
            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Confirmación de asistencia - '. $this->getParameter('evento'))
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMail()))
                ->setBcc(array('gerardo@matmor.unam.mx'))
                ->setBody($this->renderView('form/mailConfirma.txt.twig', array('entity' => $registro)));
            $mailer->send($message);

            //return $this->redirectToRoute('form_edit', array('id' => $registro->getId()));
            return $this->render('form/confirmConfirma.html.twig', array('id' => $registro->getId(), 'entity' => $registro));

        }
        //      }

        if( $mail != $registro->getMail() ||
            $slug != $registro->getSlug() ||
            $registro->isAceptado() == false
            ) {

            throw $this->createNotFoundException('Existe algún problema con la información de registro favor de contactar a webmaester@matmor.unam.mx');
        }

        if( $registro->isConfirmado() || $registro->isExamen() )
        {
            return $this->render('form/confirmConfirma.html.twig', array('id' => $registro->getId(),'entity'=>$registro));
        }

        return $this->render('form/confirma.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Registro entity.
     *
     * @Route("/{id}/edit", name="form_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $registro = $em->getRepository('RegistroBundle:Form')->find($id);

        //$deleteForm = $this->createDeleteForm($registro);
        $editForm = $this->createForm('RegistroBundle\Form\FormType', $registro);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

          $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            $this->addFlash(
                'notice',
                'El registro se ha modificado'
            );

            return $this->redirectToRoute('form_edit', array('id' => $registro->getId()));
        }

        return $this->render('form/edit.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a Form entity.
     *
     * @Route("/{id}", name="form_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Form $form)
    {
        $form = $this->createDeleteForm($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($form);
            $em->flush();
        }

        return $this->redirectToRoute('form_index');
    }

    /**
     * Creates a form to delete a Form entity.
     *
     * @param Form $form The Form entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Form $form)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('form_delete', array('id' => $form->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Download file
     *
     * @Route("/descarga/constancia", name="form_constancia")
     * @Method({"GET", "POST"})
     */
    public function constanciaAction(Request $request)
    {

        $defaultData = array('message' => 'Type your message here');
        $formail = $this->createFormBuilder($defaultData)
            ->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType',
                array('label'=>'Ingresa el correo con el que te registraste para descargar tu constancia'))
            ->getForm();

        $formail->handleRequest($request);

        if ($formail->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $mail = $formail->getData('mail');
            $em = $this->getDoctrine()->getManager();
            $registro = $em->getRepository('RegistroBundle:Form')->findOneByMail($mail);

            if (!$registro) {
                throw $this->createNotFoundException(
                    'Registro no encontrado'
                );
            }

//            $mail= strtolower($mail['email']);
            $pdf= "http://gaspacho.matmor.unam.mx/esver19/files/".$registro->getSlug().".pdf";

            $headers=get_headers($pdf, 1);
            if ($headers[0]!='HTTP/1.1 200 OK') {
                throw $this->createNotFoundException(
                    'Archivo no encontrado'
                );
            }
            else {

                $mailer = $this->get('mailer');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Descarga de constancia - '. $this->getParameter('eventoc'))
                    ->setFrom('webmaster@matmor.unam.mx')
//                    ->setTo(array($registro->getMail()))
                    ->setBcc(array('gerardo@matmor.unam.mx'))
                    ->setBody($this->renderView('form/descargaConstancia.txt.twig', array('entity' => $registro,'pdf'=>$pdf)));
                $mailer->send($message);

                return $this->redirect($pdf);

            }


        }

        return $this->render('form/constancia.html.twig', array(
            'form' => $formail->createView(),

        ));
    }
}
