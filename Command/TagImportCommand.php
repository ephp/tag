<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ephp\TagBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Ephp\TagBundle\Command\Helper\DialogHelper;
use Doctrine\ORM\Mapping\MappingException;
use Ephp\WebBundle\Functions\Funzioni;
use Doctrine\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Ephp\PortletBundle\Entity\Portlet;

/**
 * Generates a CRUD for a Doctrine entity.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TagImportCommand extends DoctrineCommand {

    /**
     * @see Command
     */
    protected function configure() {
        $this
                ->setDefinition(array())
                ->setDescription('Importa i tag')
                ->setHelp(<<<EOT
The <info>ephp:tag:import</info> command import new tags in defined table.

<info>php app/console b2s:tag:import</info>
EOT
                )
                ->setName('ephp:tag:import')
                ->setAliases(array('ephp:tag:import'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Import Tag', 'bg=white;fg=black');

        $gruppoClass = $this->getContainer()->get('doctrine')->getEntityNamespace('EphpTagBundle') . '\\Gruppo';
        $entityClass = $this->getContainer()->get('doctrine')->getEntityNamespace('EphpTagBundle') . '\\Tag';
        $conn = $this->getDoctrineConnection('default');
        $em = $this->getEntityManager('default');

        $_gruppo = $em->getRepository($gruppoClass);
        $_tag = $em->getRepository($entityClass);

        $exclude = array('.', '..', 'index.html.twig', 'portlets.xml');

        $bundle = $this->getContainer()->get('kernel')->getBundle('EphpTagBundle');
        $bundle_namespace = get_class($bundle);
        echo "\n\n" . $bundle_namespace;

        $path = $bundle->getPath();
        $sep = $path{0} == '/' ? '/' : '\\';
        $path = $path . "{$sep}..{$sep}..{$sep}..{$sep}..{$sep}..{$sep}app{$sep}Resources{$sep}tags";
        echo "\n";
        if ($handle_action = opendir($path)) {
            while (false !== ($file = readdir($handle_action))) {
                if (!in_array($file, $exclude)) {
                    echo "- $file\n";
                    if (($handle = fopen($path.$sep.$file, "r")) !== false) {
                        $sigla = str_replace('.csv', '', $file);
                        $gruppo = $_gruppo->findOneBy(array('sigla' => $sigla));
                        if(!$gruppo) {
                            $gruppo = new \Ephp\TagBundle\Entity\Gruppo();
                            $gruppo->setSigla($sigla);
                            $gruppo->setTag($sigla);
                            $gruppo->setDescrizione($sigla);
                            $em->persist($gruppo);
                            $em->flush();
                        }
                        $tags = array();
                        foreach ($gruppo->getTags() as $tag) {
                            $tags[] = $tag;
                        }
                        $_gruppo->removeAllTag($sigla);
                        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                            $tag = $_tag->findOneBy(array('tag' => $data[0]));
                            if(!$tag) {
                                $data[0] = trim($data[0]);
                                foreach ($gruppo->getTags() as $tagg) {
                                    if(trim($tagg->getTag()) == $data[0]) {
                                        $tag = $tagg;
                                    }
                                }
                                if(!$tag) {
                                    $tag = new \Ephp\TagBundle\Entity\Tag();
                                    $tag->setTag($data[0]);
                                }
                            }
                            $tag->setPubblico(true);
                            if(isset($data[1])) {
                                $tag->setDescrizione($data[1]);
                            }
                            if(isset($data[2])) {
                                $tag->setFavicon($data[2]);
                            }
                            $em->persist($tag);
                            $em->flush();
                            $gruppo->addTags($tag);
                        }
                        fclose($handle);
                        $em->persist($gruppo);
                        $em->flush();
                    }
                }
            }
            closedir($handle_action);
        }

        $dialog->writeSection($output, $entityClass);
        ;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        return $em;
    }

    /**
     * 
     * @return \Ephp\PortletBundle\Command\Helper\DialogHelper
     */
    protected function getDialogHelper() {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Ephp\TagBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }

}
