<?php

namespace Redking\Bundle\ODMTranslatorBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait TranslatableTrait 
{

    /**
     * @MongoDB\Hash
     * @var array
     */
    protected $translations;

    protected $locale;

    public function getTranslations()
    {
        return $this->translations;
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;
        return $this;
    }

    public function getTranslationsByLocale($locale)
    {
        return (isset($this->translations[$locale])) ? $this->translations[$locale] : null;
    }

    /**
     * Set the locale to use for translation listener
     *
     * @param string $locale
     *
     * @return static
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getTranslatableLocale()
    {
        return $this->locale;
    }

    /**
     * Retourne les traductions pour un attribut
     * @param  [type] $field [description]
     * @return [type]        [description]
     */
    public function getTranslationsFor($field)
    {
        $translations = [];
        foreach($this->translations as $locale => $fields) {
            if (isset($fields[$field])) {
                $translations[$locale] = $fields[$field];
            }
        }
        if (count($translations) > 0) {
            return $translations;
        }
    }
}
