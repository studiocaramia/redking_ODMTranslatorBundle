ODM Translator Bundle
=====================

This bundle allow translations to be saved in the document

## Installation

Add bundle to composer.json

```js
{
    "require": {
        "redking/odm-translator-bundle": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:redkingteam/redkingodmtranslatorbundle.git"
        }
    ]
}
```

Register the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new Redking\Bundle\ODMTranslatorBundle\RedkingODMTranslatorBundle(),
    );
}
```

## Configuration

Add handled locales in Configuration

```yaml
# app/config/config.yml
redking_odm_translator:
    locales: [fr, en, de]
```


## Usage

Use annotation in document model to specify translatable attributes

```php
<?php

// Import annotation definition
use Redking\Bundle\ODMTranslatorBundle\Mapping\Annotation\Translatable;

//.....
class Email
{
    /**
     * @var string $subject
     *
     * @MongoDB\String
     * @Translatable
     */
    protected $subject;

    /**
     * @var string $subject
     *
     * @MongoDB\String
     * @Translatable
     */
    protected $body;

    // Import traits (define a "translations" hash attribute to save data)
    use \Redking\Bundle\ODMTranslatorBundle\Document\TranslatableTrait;

}

```

## Forms

In forms, we can use this new type : 

```php
<?php

//....

->add('translations', 'translation', [
    'options' => [
        'fields' => [
            'body' => [
                'type' => 'textarea',
                'options' => array('attr' => array('class' => 'ckeditor'))
            ]
        ]
    ]
])
```

We can optionnaly pass custom type and options to specific translated fields


## Get new translations

The object is loaded with the values corresponding of the default locale.
In order to use a different locale, we have to reload it : 


```php
<?php

// ...

$document->setTranslatableLocale($new_locale);
$dm->refresh($document);
```

