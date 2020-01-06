<?php 

namespace App\Controller;

use App\Entity\Job;
use App\Form\SearchJobType;
use App\Repository\JobRepository;
use App\Service\SearchPaginator;
use Doctrine\Common\Collections;
use Doctrine\Common\Collections\Criteria as Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
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
    /**
     * @var SearchPaginator
     */
    private $searchPaginator;
    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(EntityManagerInterface $entityManager, SearchPaginator $searchPaginator, JobRepository $jobRepository)
    {
        $this->em = $entityManager;
        $this->searchPaginator = $searchPaginator;
        $this->jobRepository = $jobRepository;
        $this->criteria = new \Doctrine\Common\Collections\Criteria();
    }

    /**
     * @Route("/search/{page}", name = "search")
     * @param Request $request
     * @param Session $session
     * @return Response
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function main(Request $request, Session $session): Response
    {
        $form = $this->createForm(SearchJobType::class);
        $form->handleRequest($request);
        $page = $request->get('page');
        $data = NULL;

        if($page>=1){
            $data = $session->get('searchData');
            $queryBuilder = $this->prepareQuery($data);

            $this->searchPaginator->init($queryBuilder);
            $resultCount = $this->searchPaginator->numberOfEntries;

            $firstData=$this->searchPaginator->getData($page);
            $data['page']= $page;
            $session->set('searchData',$data);
            $data['dbData']=$firstData;
            $data['dbData_count'] = $resultCount;
        }

        if($form->isSubmitted() && $form->isValid()) {
            $page= 1;
            $data = $form->getData();

            $queryBuilder = $this->prepareQuery($data);

            $this->searchPaginator->init($queryBuilder);
            $resultCount = $this->searchPaginator->numberOfEntries;

            $firstData=$this->searchPaginator->getData($page);

            $data['page']= $page;
            $session->set('searchData',$data);
            $data['dbData']=$firstData;
            $data['dbData_count'] = $resultCount;

            //return $this->render('search.html.twig',['my_form'=>$form->createView(),'data'=>$data, 'page'=>$page]);
        }

        return $this->render('search.html.twig',['my_form'=>$form->createView(),'data'=>$data,'page'=>$page]);
    }

    public function addMyCriteria($field, $value)
    {
        if($value !== NULL) {
            $this->criteria->andWhere($this->criteria->expr()->eq($field, $value));
        }
    }

    public function prepareQuery($data)
    {
        $queryBuilder = $this->jobRepository->createQueryBuilder('job');
        $this->addMyCriteria('city',$data['city']);
        $this->addMyCriteria('experience_level', $data['experience_level']);
        $this->addMyCriteria('marker_icon',$data['tech']);

        $queryBuilder->addCriteria($this->criteria);
        $queryBuilder->addOrderBy('job.published_at','DESC');

        return $queryBuilder;
    }
}