<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Form\SearchJobType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use \Doctrine\Common\Util\Debug;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name = "search")
     */
    public function main(Request $request)
    {
        $form = $this->createForm(SearchJobType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $dbData = $this->getJob($data['city']);
            $data['dbData']=$dbData;
            $data['dbData_count'] = count($dbData);
            dump($data);
            return $this->render('search.html.twig', ['my_form'=>$form->createView(), 'data'=>$data]);
        }
        return $this->render('search.html.twig',['my_form'=>$form->createView()]);
    }

    public function getJob(string $city="")
    {
        $response = $this->getDoctrine()->getRepository(Job::class)->findBy(['city'=>$city]);
        return $response;
    }

}