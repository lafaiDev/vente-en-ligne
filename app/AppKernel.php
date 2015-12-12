<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //bundle de template twig
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            //bundle de vente en ligne
            new Catalogue\CatalogueBundle\CatalogueBundle(),
            //bundle pour gestion utilisateur
            new FOS\UserBundle\FOSUserBundle(),
            //bundle extende de FOSUSER
            new Catalogue\UtilisateurBundle\UtilisateurBundle(),
            //bundle pour pagination
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            //les filter
            //new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            //$bundles[] = new PUGX\GeneratorBundle\PUGXGeneratorBundle();
            //$bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();

            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
