<?php
namespace Minds\Core\Translation\Services;

interface TranslationServiceInterface
{
    /**
     * Returns a list of available. If the service supports it, $target
     * should localize the human-readable names, if not it should
     * list using English names.
     *
     * @param string $target
     * @return array [ [ 'language' => ..., 'name' => ... ], ... ]
     */
    public function languages($target = null);

    /**
     * Translates $content to $target language (SHOULD default to English).
     * If the service supports it, omitting $source will attempt to
     * auto-detect $content's language.
     *
     * @param string $content
     * @param string $target
     * @param string $source
     * @return array [ 'content' => ..., 'source' => ... ]
     */
    public function translate($content, $target = null, $source = null);
}
