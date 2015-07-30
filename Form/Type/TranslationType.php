<?php

namespace Redking\Bundle\ODMTranslatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class TranslationType extends AbstractType
{
    protected $locales;

    protected $dm;

    protected $subscriber;

    public function __construct($locales, $doctrine, $subscriber)
    {
        $this->locales = $locales;

        $this->dm = $doctrine->getManager();

        $this->subscriber = $subscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $e) {
            // Récupération de la classe
            $data_class = $e->getForm()->getParent()->getConfig()->getDataClass();

            // Récupération des champs translatable
            $config = $this->subscriber->getConfiguration($this->dm, $data_class);
            if (!$config) {
                return;
            }
            
            $input = $e->getData();

            // Si pas de valeurs, construction des valeurs vides
            if (null === $input) {
                $output = [];
                foreach($this->locales as $locale) {
                    $output[$locale] = array();
                    foreach($config['fields'] as $field) {
                        $output[$locale][$field] = '';
                    }
                }
            } else {
                $output = $input;

                // Si il y a des valeurs, on vérifie qu'il ne manque pas de locales
                $new_locales = array_diff($this->locales, array_keys($input));
                foreach($new_locales as $new_locale) {
                    $output[$new_locale] = array();
                    foreach($config['fields'] as $field) {
                        $output[$new_locale][$field] = '';
                    }
                }
            }

            $e->setData($output);
        }, 1);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_add'    => false,
            'allow_delete' => false,
            'type'         => 'translation_row',
            
        ));
    }

    public function getName()
    {
        return 'translation';
    }

    public function getParent()
    {
        return 'collection';
    }
}
