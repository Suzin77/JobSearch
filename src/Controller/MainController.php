<?php declare(strict_types= 1);

namespace App\Controller;

use App\Model\JobOfferContent;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MainController extends Controller
{
    /**
     * @Route("", name = "index ")
     */
    public function index()
    {
        $url = 'https://justjoin.it/api/offers';
        $data = [];
        $jobOffers = new JobOfferContent();
        $offers = $jobOffers->getJobOffers($url);
        $this->getFirm($offers);
        var_dump($offers[0]);
        return $this->render('base.html.twig');
    }

    public function getFirm($data)
    {
        echo count($data);
        foreach ($data as $firm){      
               
            echo $firm['title']." ".$firm['company_name']."<a href=\"".$firm['company_url']."\"/></br>" ;
        }

    }


}