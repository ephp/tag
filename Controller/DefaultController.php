<?php

namespace Ephp\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/tag")
 */
class DefaultController extends Controller {

    /**
     * q = termine cercato
     * o = cerca nel/nei guppo/i (es: azienda|professionista)
     * e = non cerca nel/nei guppo/i (es: azienda|professionista)
     * d = cerca anche nella secrizione di tag
     * n = permette la creazione di nuovi tag
     * c = associa css ai tag creati
     * 
     * @Route("/cerca", name="tag_cerca", defaults={"_format"="json"})
     */
    public function cercaAction() {
        $request = $this->getRequest();
        $out = array();
        $q = $request->get('q', '');
        if (strlen($q) >= 2) {
            $em = $this->getEm();
            $_tag = $em->getRepository('Ephp\TagBundle\Entity\Tag');
            $tags = $_tag->cerca($q, $request->get('o'), $request->get('e'), $request->get('d'));
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
            if($request->get('n')) {
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
                        'css' => $request->get('c') ? urldecode($request->get('c')) : 'tag_default',
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
