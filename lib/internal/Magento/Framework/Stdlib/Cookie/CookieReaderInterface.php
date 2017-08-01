<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Stdlib\Cookie;

/**
 * CookieReaderInterface provides the ability to read cookies sent in a request.
 * @api
 * @since 2.0.0
 */
interface CookieReaderInterface
{
    /**
     * Retrieve a value from a cookie.
     *
     * @param string $name
     * @param string|null $default The default value to return if no value could be found for the given $name.
     * @return string|null
     * @since 2.0.0
     */
    public function getCookie($name, $default = null);
}
