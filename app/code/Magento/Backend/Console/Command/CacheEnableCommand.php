<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Backend\Console\Command;

/**
 * Command for enabling cache
 *
 * @api
 * @since 2.0.0
 */
class CacheEnableCommand extends AbstractCacheSetCommand
{
    /**
     * {@inheritdoc}
     * @since 2.0.0
     */
    protected function configure()
    {
        $this->setName('cache:enable');
        $this->setDescription('Enables cache type(s)');
        parent::configure();
    }

    /**
     * Is enable cache
     *
     * @return bool
     * @since 2.0.0
     */
    protected function isEnable()
    {
        return true;
    }
}
