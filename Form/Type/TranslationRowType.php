<?php

namespace Redking\Bundle\ODMTranslatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Redking\Bundle\ODMTranslatorBundle\Form\DataTransformer\TranslationTransformer;

class TranslationRowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $e) use ($options) {
            $input = $e->getData();
            $form = $e->getForm();
            
            foreach($input as $field => $value) {
                $field_type = (isset($options['fields'][$field]) && isset($options['fields'][$field]['type'])) ? $options['fields'][$field]['type'] : 'text';
                $field_options = (isset($options['fields'][$field]) && isset($options['fields'][$field]['options'])) ? $options['fields'][$field]['options'] : array();
                if (!isset($field_options['label'])) {
                    $field_options['label'] = 'form.label_'.$field;
                }

                $form->add($field, $field_type, $field_options);
            }
        });
    }

    public function getName()
    {
        return 'translation_row';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'fields' => array()
        ));
    }
}
