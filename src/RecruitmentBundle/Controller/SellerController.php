<?php

namespace RecruitmentBundle\Controller;

use EntityBundle\Entity\Seller;
use EntityBundle\Form\SellerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class SellerController extends Controller
{

    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function ListSellerAction(){
        $em = $this->getDoctrine()->getManager();

        $sellerlist = $em->getRepository('EntityBundle:Seller')->findAll();
        return $this->render('@Recruitment/Seller/listSeller.html.twig', array(
            "seller" => $sellerlist,
        ));
    }


    /**
     * @IsGranted("ROLE_MEMBER")
     */
    public function AddSellerAction(Request $request)
    {
        $seller = new Seller();
        $form = $this->createForm(SellerType::class, $seller);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $seller->uploadProfilePicture();
            $em->persist($seller);
            $em->flush();

           return $this->redirectToRoute("question_answer");
            //return $this->render('@Recruitment/Seller/continue.html.twig');

        }

        return $this->render('@Recruitment/Seller/add_seller.html.twig', array("form" => $form->createView()
        ));
    }
    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function UpdateSellerAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $seller = $em->getRepository('EntityBundle:Seller')->find($id);
        $form = $this->createForm(SellerType::class, $seller);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seller);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
            return $this->redirectToRoute('list_seller');
        }
        return $this->render('@Recruitment/Seller/update_seller.html.twig', array("form" => $form->createView()
        ));

    }


    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function DeleteSellerAction($id)
    {
        $seller = $this -> getDoctrine() -> getRepository(Seller::class) -> find($id);
        $em = $this -> getDoctrine() -> getManager();
        $em -> remove($seller);
        $em -> flush();
        return $this -> redirectToRoute("list_seller");
    }
   /* public function SearchAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $seller= $em->getRepository('EntityBundle:Category')->findAll();
        if($request->isMethod('POST'))
        {
            $nomcat=$request->get('nomcat');
            $category= $em->getRepository('EntityBundle:Category')->findBy(array("nomcat"=>$nomcat));
        }
        return $this->render('@Product/Category/search.html.twig', array("category"=>$category));

    }*/

}
