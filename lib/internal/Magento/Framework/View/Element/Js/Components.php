<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\View\Element\Js;

use Magento\Framework\App\State;
use Magento\Framework\View\Element\Template;

/**
 * @api
 * @since 2.0.0
 */
class Components extends Template
{
    /**
     * Developer mode
     *
     * @return bool
     * @since 2.0.0
     */
    public function isDeveloperMode()
    {
        return $this->_appState->getMode() == State::MODE_DEVELOPER;
    }
}
