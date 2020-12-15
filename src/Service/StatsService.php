<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StatsService.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class StatsService
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    /**
     * StatsService constructeur.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getStats()
    {
        $branchs = $this->getBranchsCount();
        $degrees = $this->getDegreesCount();
        $matters = $this->getMattersCount();
        $professors = $this->getProfessorsCount();
        $students = $this->getStudentsCount();

        return compact('branchs', 'degrees', 'matters', 'professors', 'students');
    }

    public function getBranchsCount()
    {
        return $this->em->createQuery('SELECT COUNT(b) FROM App\Entity\Branch b')->getSingleScalarResult();
    }

    public function getDegreesCount()
    {
        return $this->em->createQuery('SELECT COUNT(d) FROM App\Entity\Degree d')->getSingleScalarResult();
    }

    public function getMattersCount()
    {
        return $this->em->createQuery('SELECT COUNT(m) FROM App\Entity\Matter m')->getSingleScalarResult();
    }

    public function getNotesCount()
    {
        return $this->em->createQuery('SELECT COUNT(n) FROM App\Entity\Note n')->getSingleScalarResult();
    }

    public function getProfessorsCount()
    {
        return $this->em->createQuery('SELECT COUNT(p) FROM App\Entity\Professor p')->getSingleScalarResult();
    }

    public function getStudentsCount()
    {
        return $this->em->createQuery('SELECT COUNT(s) FROM App\Entity\Student s')->getSingleScalarResult();
    }
}
