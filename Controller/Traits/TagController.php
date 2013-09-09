<?php

namespace Ephp\TagBundle\Controller\Traits;

trait TagController {

    /**
     * @param type $q termine cercato
     * @param type $o cerca nel/nei guppo/i (es: azienda|professionista)
     * @param type $e non cerca nel/nei guppo/i (es: azienda|professionista)
     * @param type $d cerca anche nella secrizione di tag
     * @param type $n permette la creazione di nuovi tag
     * @param type $c associa css ai tag creati
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cercaTag($q, $o, $e, $d, $n, $c) {
        $request = $this->getRequest();
        $out = array();
        if (strlen($q) >= 2) {
            $em = $this->getEm();
            $_tag = $em->getRepository('Ephp\TagBundle\Entity\Tag');
            $tags = $_tag->cerca($q, $o, $e, $d);
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    $out[] = array(
                        'id' => $tag->getId(),
                        'name' => $tag->getTag(),
                        'css' => $tag->getFavicon() ? : 'tag_default',
                        'descrizione' => $tag->getDescrizione(),
                    );
                }
            }
            if($n) {
                $insert = true;
                foreach ($out as $t) {
                    if(strtolower($t['name']) == strtolower($q)) {
                        $insert = false;
                    }
                }
                if($insert) {
                    $q = strtoupper($q{0}) . substr($q, 1);
                    $out[] = array(
                        'id' => $q,
                        'name' => $q,
                        'css' => $c ? urldecode($c) : 'tag_default',
                        'descrizione' => 'Stai inserendo una nuova etichetta',
                    );
                }
            }
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * @return \Doctrine\ORM\EntityManager 
     */
    protected function getEm() {
        return $this->getDoctrine()->getEntityManager();
    }

}
