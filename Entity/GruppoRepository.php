<?php

namespace Ephp\TagBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GruppoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GruppoRepository extends EntityRepository {

    public function removeAllTag($sigla) {
        $connection = $this->getEntityManager()->getConnection();
        $q = "DELETE FROM tag_tag_gruppi WHERE gruppo_id = (SELECT id FROM tag_gruppi WHERE sigla = ?)";
        $connection->executeUpdate($q, array($sigla));
    }

}