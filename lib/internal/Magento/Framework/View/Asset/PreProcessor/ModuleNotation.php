<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\View\Asset\PreProcessor;

use Magento\Framework\View\Asset;
use Magento\Framework\View\Url\CssResolver;

/**
 * Support of notation "Module_Name::file/path.ext" in CSS-files
 *
 * This implementation is specific to CSS, despite that the actual algorithm of calculating offsets is generic.
 * The part specific to CSS is the fact that a CSS file may refer to another file and the relative path has to be
 * based off the current location of CSS-file. So context of base path can be known ONLY at the moment
 * of traversing the CSS contents in context of the file location.
 * Other than that, the algorithm of resolving notation "Module_Name::file/path.ext" is generic
 * @since 2.0.0
 */
class ModuleNotation implements Asset\PreProcessorInterface
{
    /**
     * @var \Magento\Framework\View\Url\CssResolver
     * @since 2.0.0
     */
    private $cssResolver;

    /**
     * @var \Magento\Framework\View\Asset\NotationResolver\Module
     * @since 2.0.0
     */
    private $notationResolver;

    /**
     * @param CssResolver $cssResolver
     * @param \Magento\Framework\View\Asset\NotationResolver\Module $notationResolver
     * @since 2.0.0
     */
    public function __construct(
        CssResolver $cssResolver,
        \Magento\Framework\View\Asset\NotationResolver\Module $notationResolver
    ) {
        $this->cssResolver = $cssResolver;
        $this->notationResolver = $notationResolver;
    }

    /**
     * {@inheritdoc}
     * @since 2.0.0
     */
    public function process(Chain $chain)
    {
        $asset = $chain->getAsset();
        $callback = function ($path) use ($asset) {
            return $this->notationResolver->convertModuleNotationToPath($asset, $path);
        };
        $chain->setContent($this->cssResolver->replaceRelativeUrls($chain->getContent(), $callback));
    }
}
