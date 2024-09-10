<?php
namespace App\Providers;

use Illuminate\Translation\Translator;

class DefaultParamTranslator extends Translator
{

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        return parent::get($key, [
            'count' => '',
            'resource' => '',
            ...$replace
        ], $locale, $fallback);
    }

}