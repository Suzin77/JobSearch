<?php declare(strict_types =1);

namespace App\Model;

use App\Entity\Job;
use App\Entity\Skills;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManager;

class SkillsWriter extends AbstractController
{
    public function setSkills(array $skills)
    {
        $skills = new Skill();

    }

    public function saveSkill($skill)
    {

    }
}