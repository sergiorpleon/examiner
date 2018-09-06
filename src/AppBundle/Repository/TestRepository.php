<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TestRepository extends EntityRepository
{

    public function findReady($id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT t,r FROM AppBundle:Test t JOIN t.id_reading r
 WHERE  t.id = :id AND r.fecha = :fecha');
        $consulta->setParameter('id',$id);
        $consulta->setParameter('fecha',new \DateTime( (new \DateTime('today'))->format("d-m-Y")));
        $result = $consulta->getOneOrNullResult() ;

            return $result ;

    }

    public function findReadyAll()
    {
        /*
        //$arrayL['fecha'] = $l->getFecha()->format("d-m-Y");
        //$l->setFecha(\DateTime::createFromFormat('d-m-Y', $listening->fecha));
        $fecha = new  \DateTime( (new \DateTime('now'))->format("H:i"));
        $fecha =
            //$fecha->format('Y,')
            $fecha->format('H,')
            //.($fecha->format('m')-1)
            //.$fecha->format(',d,')
            //.($fecha->format('H,'))
            .($fecha->format('i'))
            .$fecha->format(',s');
*/
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT t, r FROM AppBundle:Test t JOIN t.id_reading r
 WHERE  r.fecha = :fecha
ORDER BY r.fecha DESC');
        //$consulta->setMaxResults(5);
        //$consulta->setParameter('fecha', new \DateTime('today'));
        $consulta->setParameter('fecha',new \DateTime( (new \DateTime('today'))->format("d-m-Y")));
        //$consulta->setParameter('time', new \DateTime( (new \DateTime('now'))->format("H:i")));
        //$consulta->setParameter('time2',$fecha);
        return $consulta->getResult();

        /*AND
r.horaComienzo < :time AND
r.horaComienzo > :time2*/
    }
}