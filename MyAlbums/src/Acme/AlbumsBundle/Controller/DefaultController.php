<?php

namespace Acme\AlbumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\AlbumsBundle\Entity\Album;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //return $this->render('AcmeAlbumsBundle:Default:index.html.twig');
        return $this->albumAction();
    }

    /**
     * @Route("/album", name="acme_albums")
     * @Template()
    */
    public function albumAction()
    {
    	$repository = $this->getDoctrine()->getRepository('AcmeAlbumsBundle:Album');

    	// Get albums from database
    	$albums = $repository->findAll();

    	return $this->render('AcmeAlbumsBundle:Default:index.html.twig',
    							array('albumRecords' => $albums));
    }

    /**
     * @Route("/album/add", name="acme_addAlbums")
     * @Template()
    */
    public function addAction(Request $request)
    {
        // First create a blank Album object
        $album = new Album();

        // Have controller create a form object based on album's members
        $form = $this->createFormBuilder($album)
            ->add('artist', 'text')
            ->add('title', 'text')
            ->add('Add', 'submit')
            ->getForm();

        // Handle request
        $form->handleRequest($request);
        if ($form->isValid()) {
            // Add album to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirect($this->generateUrl('acme_albums_homepage'));
        }

        return $this->render('AcmeAlbumsBundle:Default:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/album/edit/{id}", name="acme_editAlbum")
     * @Template()
    */
    public function editAction($id)
    {
        // First get database & table
        $repository = $this->getDoctrine()->getRepository('AcmeAlbumsBundle:Album');

        // Get album from database
        $album = $repository->find($id);

        if (!$album) {
            throw $this->createNotFoundException(
                'No album found for id: '.$id
            );
        }
        
        $form = $this->createFormBuilder($album)
            ->add('artist', 'text')
            ->add('title', 'text')
            ->add('Update', 'submit')
            ->getForm();

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            // Update Album in database
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('acme_albums_homepage'));
        }


        // Now display Edit page
        return $this->render('AcmeAlbumsBundle:Default:edit.html.twig',
                                array('form' => $form->createView()));
    }

    /**
     * @Route("/album/delete/{id}", name="acme_deleteAlbum")
     * @Template()
    */
    public function deleteAction($id)
    {
        // First specific album from database
        $repository = $this->getDoctrine()->getRepository('AcmeAlbumsBundle:Album');
        $albumToDelete = $repository->find($id);
        if (!$albumToDelete) {
            throw $this->createNotFoundException('No album found for id: ' . $id);
        }

        $form = $this->createFormBuilder($albumToDelete)
            ->add('Yes', 'submit')
            ->add('No', 'submit')
            ->getForm();

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            if ($form->get('Yes')->isClicked()) {
                // Delete album from database
                $em = $this->getDoctrine()->getManager();
                $em->remove($albumToDelete);
                $em->flush();
            }
                
            return $this->redirect($this->generateUrl('acme_albums_homepage'));



        }

        return $this->render('AcmeAlbumsBundle:Default:delete.html.twig',
                            array('albumToDelete' => $albumToDelete, 'form' => $form->createView()));
    }
}
