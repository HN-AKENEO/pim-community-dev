import React from 'react';
import {useAttributeOptions} from '../hooks';
import {AttributeOption} from '../model';
import ToggleButton from './ToggleButton';
import AttributeOptionItem from './AttributeOptionItem';
import {useTranslate} from '@akeneo-pim-community/legacy-bridge';

const List = () => {
    const attributeOptions = useAttributeOptions();
    const __ = useTranslate();

    return (
        <div className="AknSubsection AknAttributeOption-list">
            <div className="AknSubsection-title AknSubsection-title--glued tabsection-title">
                <span>{__('pim_enrich.entity.attribute_option.module.edit.options_codes')}</span>
                <div className="AknButton AknButton--micro">{__('pim_enrich.entity.product.module.attribute.add_option')}</div>
            </div>

            <div>{__('pim_enrich.entity.attribute.property.auto_option_sorting')}</div>
            <ToggleButton />

            <div role="attribute-options-list">
                {attributeOptions !== null && attributeOptions.map((attributeOption: AttributeOption) => {
                    return (
                        <AttributeOptionItem key={attributeOption.code} data={attributeOption} />
                    );
                })}
            </div>
        </div>
    );
};

export default List;
