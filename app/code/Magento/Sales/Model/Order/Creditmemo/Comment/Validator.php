<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Model\Order\Creditmemo\Comment;

use Magento\Sales\Model\Order\Creditmemo\Comment;

/**
 * Class Validator
 * @since 2.0.0
 */
class Validator
{
    /**
     * Required field
     *
     * @var array
     * @since 2.0.0
     */
    protected $required = [
        'parent_id' => 'Parent Creditmemo Id',
        'comment' => 'Comment',
    ];

    /**
     * Validate data
     *
     * @param \Magento\Sales\Model\Order\Creditmemo\Comment $comment
     * @return array
     * @since 2.0.0
     */
    public function validate(Comment $comment)
    {
        $errors = [];
        $commentData = $comment->getData();
        foreach ($this->required as $code => $label) {
            if (!$comment->hasData($code)) {
                $errors[$code] = sprintf('%s is a required field', $label);
            } elseif (empty($commentData[$code])) {
                $errors[$code] = sprintf('%s can not be empty', $label);
            }
        }

        return $errors;
    }
}
