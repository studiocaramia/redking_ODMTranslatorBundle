<?php

namespace Redking\Bundle\ODMTranslatorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Common\Annotations\AnnotationRegistry;

class RedkingODMTranslatorBundle extends Bundle
{
    public function boot()
    {
        $kernel = $this->container->get('kernel');

        AnnotationRegistry::registerFile($kernel
            ->locateResource("@RedkingODMTranslatorBundle/Mapping/Annotation/Translatable.php")
        );
    }
}
