<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Form\SearchJobType;
use App\Repository\JobRepository;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\Criteria as Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Criteria
     */
    private $criteria;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->criteria = new \Doctrine\Common\Collections\Criteria();
    }

    /**
     * @Route("/search", name = "search")
     */
    public function main(Request $request)
    {
        $form = $this->createForm(SearchJobType::class);
        $form->handleRequest($request);

        $jobs = $this->em->getRepository(Job::class);
        
        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $queryBuilder = $jobs->createQueryBuilder('job');
            $this->addMyCriteria('city',$data['city']);
            $this->addMyCriteria('experience_level', $data['experience_level']);
            $this->addMyCriteria('marker_icon',$data['tech']);

            $queryBuilder->addCriteria($this->criteria);

            $firstData=$queryBuilder->getQuery()->execute();

            $data['dbData']=$firstData;
            $data['dbData_count'] = count($firstData);

            return $this->render('search.html.twig', ['my_form'=>$form->createView(), 'data'=>$data]);
        }
        return $this->render('search.html.twig',['my_form'=>$form->createView()]);
    }

    public function addMyCriteria($field, $value)
    {
        $this->criteria->andWhere($this->criteria->expr()->eq($field, $value));
    }

}