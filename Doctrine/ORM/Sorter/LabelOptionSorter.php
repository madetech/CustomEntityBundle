<?php

namespace Pim\Bundle\CustomEntityBundle\Doctrine\ORM\Sorter;

use Pim\Bundle\CatalogBundle\Doctrine\ORM\Sorter\BaseSorter;
use Pim\Bundle\CatalogBundle\Model\AbstractAttribute;

/**
 * Sorter for translatable options
 *
 * @author    Antoine Guigan <antoine@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class LabelOptionSorter extends BaseSorter
{
    /**
     * {@inheritdoc}
     */
    public function addAttributeSorter(AbstractAttribute $attribute, $direction)
    {
        $aliasPrefix = 'sorter';
        $joinAlias   = $aliasPrefix.'V'.$attribute->getCode().$this->aliasCounter++;
        $backendType = $attribute->getBackendType();

        // join to value
        $condition = $this->prepareAttributeJoinCondition($attribute, $joinAlias);
        $this->qb->leftJoin(
            $this->qb->getRootAlias().'.' . $attribute->getBackendStorage(),
            $joinAlias,
            'WITH',
            $condition
        );

        // then to option and option value to sort on
        $joinAliasOpt = $aliasPrefix.'O'.$attribute->getCode().$this->aliasCounter;
        $this->qb->leftJoin($joinAlias.'.'.$backendType, $joinAliasOpt);

        $this->qb->addOrderBy($joinAliasOpt.'.label', $direction);

        return $this;
    }
}
