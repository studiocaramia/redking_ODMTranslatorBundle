<?php
/**
 * Subscriber pour le cycle de vie des documents uploadable
 */
namespace Redking\Bundle\ODMTranslatorBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Events as MongoDBEvents;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Gedmo\Mapping\MappedEventSubscriber;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DocumentEventSubscriber extends MappedEventSubscriber
{

    protected $default_locale;
    protected $locales;
    protected $current_locale;

    public function __construct($default_locale, $locales)
    {
        $this->default_locale  = $default_locale;
        $this->locales         = $locales;
        $this->current_locale = $default_locale;
    }

    public function getSubscribedEvents()
    {
        return array(
            MongoDBEvents::loadClassMetadata,
            MongoDBEvents::postLoad,
            );
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $ea = $this->getEventAdapter($eventArgs);
        $this->loadMetadataForObjectClass($ea->getObjectManager(), $eventArgs->getClassMetadata());
    }

    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $ea = $this->getEventAdapter($eventArgs);
        $om = $ea->getObjectManager();
        $object = $ea->getObject();
        
        $meta = $om->getClassMetadata(get_class($object));
        $config = $this->getConfiguration($om, $meta->name);
        
        if (isset($config['fields'])) {
            if (!is_null($object->getTranslatableLocale()) && $object->getTranslatableLocale() != $this->current_locale) {
                $this->current_locale = $object->getTranslatableLocale();
            }
            $translations = $object->getTranslationsByLocale($this->current_locale);
            $accessor = PropertyAccess::createPropertyAccessor();

            if (!is_null($translations)) {
                foreach($config['fields'] as $field) {
                    if (isset($translations[$field])) {
                        $accessor->setValue($object, $field, $translations[$field]);
                    }
                }
            }
        }
    }
    
    protected function getNamespace()
    {
        // mapper must know the namespace of extension
        return 'Redking\Bundle\ODMTranslatorBundle';
    }

    

}
