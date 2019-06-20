<?php declare(strict_types= 1);

namespace App\Controller;

use App\Model\JobOfferContent;
use App\Model\JobOfferWriter;
use App\Entity\Job;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SkillController extends Controller
{
    public function saveSkill($skill_name, $skill_level)
    {
        
    }
}