<?php

namespace spec\Pim\Bundle\CatalogBundle\Doctrine\MongoDBODM\Filter;

use Doctrine\MongoDB\Query\Expr;
use Doctrine\ODM\MongoDB\Query\Builder;
use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Doctrine\InvalidArgumentException;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Prophecy\Argument;

/**
 * @require Doctrine\ODM\MongoDB\Query\Builder
 */
class OptionFilterSpec extends ObjectBehavior
{
    function let(Builder $qb)
    {
        $this->beConstructedWith(['pim_catalog_simpleselect'], ['IN', 'EMPTY']);
        $this->setQueryBuilder($qb);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Bundle\CatalogBundle\Doctrine\MongoDBODM\Filter\OptionFilter');
    }

    function it_is_a_filter()
    {
        $this->shouldImplement('Pim\Bundle\CatalogBundle\Doctrine\Query\AttributeFilterInterface');
    }

    function it_supports_operators()
    {
        $this->getOperators()->shouldReturn(['IN', 'EMPTY']);
        $this->supportsOperator('IN')->shouldReturn(true);
        $this->supportsOperator('FAKE')->shouldReturn(false);
    }

    function it_supports_simple_select_attribute(AttributeInterface $attribute)
    {
        $attribute->getAttributeType()->willReturn('pim_catalog_simpleselect');
        $this->supportsAttribute($attribute)->shouldReturn(true);

        $attribute->getAttributeType()->willReturn(Argument::any());
        $this->supportsAttribute($attribute)->shouldReturn(false);
    }

    function it_adds_a_filter_to_the_query($qb, AttributeInterface $attribute, Expr $expr)
    {
        $attribute->isLocalizable()->willReturn(false);
        $attribute->isScopable()->willReturn(false);
        $attribute->getBackendType()->willReturn('option');
        $attribute->getCode()->willReturn('option_code');

        $qb->expr()->willReturn($expr);
        $expr->field('normalizedData.option_code.id')->shouldBeCalled()->willReturn($expr);
        $expr->in([118, 270])->shouldBeCalled()->willReturn($expr);
        $qb->addAnd($expr)->shouldBeCalled();

        $this->addAttributeFilter($attribute, 'IN', ['118', '270']);
    }

    function it_adds_an_empty_filter_to_the_query($qb, AttributeInterface $attribute, Expr $expr)
    {
        $attribute->isLocalizable()->willReturn(false);
        $attribute->isScopable()->willReturn(false);
        $attribute->getBackendType()->willReturn('option');
        $attribute->getCode()->willReturn('option_code');

        $qb->expr()->willReturn($expr);
        $expr->field('normalizedData.option_code.id')->shouldBeCalled()->willReturn($expr);
        $expr->exists(false)->shouldBeCalled()->willReturn($expr);
        $qb->addAnd($expr)->shouldBeCalled();

        $this->addAttributeFilter($attribute, 'EMPTY', null);
    }

    function it_throws_an_exception_if_value_is_not_an_array(AttributeInterface $attribute)
    {
        $attribute->getCode()->willReturn('option_code');
        $this->shouldThrow(InvalidArgumentException::arrayExpected('option_code', 'filter', 'option'))
            ->during('addAttributeFilter', [$attribute, 'IN', 'WRONG']);
    }

    function it_throws_an_exception_if_the_content_of_value_are_not_numeric(AttributeInterface $attribute)
    {
        $attribute->getCode()->willReturn('option_code');
        $this->shouldThrow(InvalidArgumentException::numericExpected('option_code', 'filter', 'option'))
            ->during('addAttributeFilter', [$attribute, 'IN', [123, 'not numeric']]);
    }
}
