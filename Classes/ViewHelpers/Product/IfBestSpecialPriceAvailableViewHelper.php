<?php

namespace Extcode\Cart\ViewHelpers\Product;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * If SpecialPrice Available ViewHelper
 *
 * @package cart
 * @author Daniel Lorenz <ext.cart@extco.de>
 */
class IfBestSpecialPriceAvailableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{

    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @param \Extcode\Cart\Domain\Model\Product\Product $product
     *
     * @return float
     */
    public function render(
        \Extcode\Cart\Domain\Model\Product\Product $product
    ) {
        $bestSpecialPrice = $product->getBestSpecialPrice($this->getFrontendUserGroupIds());

        if ($bestSpecialPrice < $product->getMinPrice()) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }

    /**
     * Get Frontend User Group
     *
     * @return array
     */
    protected function getFrontendUserGroupIds()
    {
        if (!$this->objectManager) {
            $this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        }
        $feGroupIds = [];
        $feUserId = (int)$GLOBALS['TSFE']->fe_user->user['uid'];
        if ($feUserId) {
            $frontendUserRepository = $this->objectManager->get(
                \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository::class
            );
            $feUser = $frontendUserRepository->findByUid($feUserId);
            $feGroups = $feUser->getUsergroup();
            if ($feGroups) {
                foreach ($feGroups as $feGroup) {
                    $feGroupIds[] = $feGroup->getUid();
                }
            }
        }
        return $feGroupIds;
    }
}