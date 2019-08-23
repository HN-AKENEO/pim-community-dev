<?php

namespace Akeneo\Pim\Enrichment\Component\Product\Builder;

use Akeneo\Pim\Enrichment\Component\Product\Factory\ValueFactory;
use Akeneo\Pim\Enrichment\Component\Product\Manager\AttributeValuesResolverInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\EntityWithValuesInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\ValueInterface;
use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;

/**
 * @author    Julien Janvier <julien.janvier@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class EntityWithValuesBuilder implements EntityWithValuesBuilderInterface
{
    /** @var AttributeValuesResolverInterface */
    protected $valuesResolver;

    /** @var ValueFactory */
    protected $productValueFactory;

    /**
     * @param AttributeValuesResolverInterface $valuesResolver
     * @param ValueFactory                     $productValueFactory
     */
    public function __construct(
        AttributeValuesResolverInterface $valuesResolver,
        ValueFactory $productValueFactory
    ) {
        $this->valuesResolver = $valuesResolver;
        $this->productValueFactory = $productValueFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function addAttribute(EntityWithValuesInterface $entityWithValues, AttributeInterface $attribute)
    {
        $requiredValues = $this->valuesResolver->resolveEligibleValues([$attribute]);

        foreach ($requiredValues as $value) {
            $this->addOrReplaceValue($entityWithValues, $attribute, $value['locale'], $value['scope'], null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addOrReplaceValue(
        EntityWithValuesInterface $entityWithValues,
        AttributeInterface $attribute,
        ?string $localeCode,
        ?string $scopeCode,
        $data
    ): ?ValueInterface {
        $formerValue = $entityWithValues->getValue($attribute->getCode(), $localeCode, $scopeCode);
        if (null !== $formerValue) {
            $entityWithValues->removeValue($formerValue);
        }

        $value = null;
        if ($this->isProductIdentifier($attribute, $entityWithValues, $data)) {
            if ('' !== $data) {
                $value = $this->productValueFactory->create($attribute, $scopeCode, $localeCode, $data);
                $entityWithValues->addValue($value);
                $entityWithValues->setIdentifier($value);
            }
        } else {
            $value = $this->productValueFactory->create($attribute, $scopeCode, $localeCode, $data);
            $entityWithValues->addValue($value);
        }

        return $value;
    }

    private function isProductIdentifier(
        AttributeInterface $attribute,
        EntityWithValuesInterface $entityWithValues,
        $data
    ) {
        // TODO: TIP-722: This is a temporary fix, Product identifier should be used only as a field
        return AttributeTypes::IDENTIFIER === $attribute->getType() &&
            null !== $data &&
            $entityWithValues instanceof ProductInterface;
    }
}
