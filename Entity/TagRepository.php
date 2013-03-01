<?php

namespace Ephp\TagBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends EntityRepository {

    public function cerca($term, $only, $exclude, $descrizione) {
        $q = $this->createQueryBuilder('t');
        if ($descrizione) {
            $q->where('(t.descrizione LIKE :tag OR t.tag LIKE :tag)')
                    ->setParameter('tag', "%{$term}%");
        } else {
            $q->where('t.tag LIKE :tag')
                    ->setParameter('tag', "%{$term}%");
        }

        if ($only) {
            $only = urldecode($only);
            $q->innerJoin('t.gruppi', 'g');
            if (strpos($only, '|')) {
                $q->andWhere($q->expr()->in('g.sigla', explode('|', $only)));
            } else {
                $q->andWhere('g.sigla = :sigla')
                        ->setParameter('sigla', $only);
            }
        }
        if ($exclude) {
            $exclude = urldecode($exclude);
            $connection = $this->getEntityManager()->getConnection();
            $sq = "
SELECT tag.id
  FROM tag_tags tag
 INNER JOIN tag_tag_gruppi tg ON tg.tag_id = tag.id
 INNER JOIN tag_gruppi g ON tg.gruppo_id = g.id
  LEFT JOIN post_tag p ON p.tag_id = tag.id
 WHERE g.sigla IN ('" . implode("', '", explode('|', $exclude)) . "')";
            $out = $connection->executeQuery($sq);

            $id = array();
            foreach ($out->fetchAll() as $row) {
                $id[] = $row['id'];
            }
            $q->andWhere($q->expr()->notIn('t.id', $id));
        }
//        \Ephp\WebBundle\Functions\Funzioni::pr($q->getQuery()->getSQL(), true);
//        \Ephp\WebBundle\Functions\Funzioni::pr($q->getQuery()->getParameters());
        return $q->getQuery()->execute();
    }

    public function cercaDaSlug($slugs) {
        $out = array();
        if (!empty($slugs)) {
            $slugs = explode(' ', str_replace('+', ' ', $slugs));
            $connection = $this->getEntityManager()->getConnection();
            $q = "
SELECT t.id
  FROM tag_tags t
 WHERE t.slug IN ('" . implode("', '", $slugs) . "')";
            $stmt = $connection->executeQuery($q);
            foreach ($stmt->fetchAll() as $row) {
                $out[] = $row['id'];
            }
        }
        return $out;
    }

}