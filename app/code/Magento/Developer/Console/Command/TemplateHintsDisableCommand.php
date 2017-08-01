<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Developer\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

/**
 * Class \Magento\Developer\Console\Command\TemplateHintsDisableCommand
 *
 * @since 2.2.0
 */
class TemplateHintsDisableCommand extends Command
{
    /**
     * command name
     */
    const COMMAND_NAME = 'dev:template-hints:disable';

    /**
     * Success message
     */
    const SUCCESS_MESSAGE = "Template hints disabled. Refresh cache types";

    /**
     * @var ConfigInterface
     * @since 2.2.0
     */
    private $resourceConfig;

    /**
     * Initialize dependencies.
     *
     * @param ConfigInterface $resourceConfig
     * @since 2.2.0
     */
    public function __construct(ConfigInterface $resourceConfig)
    {
        parent::__construct();
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * {@inheritdoc}
     * @since 2.2.0
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Disable frontend template hints. A cache flush might be required.');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     * @since 2.2.0
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->resourceConfig->saveConfig('dev/debug/template_hints_storefront', 0, 'default', 0);
        $output->writeln("<info>". self::SUCCESS_MESSAGE . "</info>");
    }
}
