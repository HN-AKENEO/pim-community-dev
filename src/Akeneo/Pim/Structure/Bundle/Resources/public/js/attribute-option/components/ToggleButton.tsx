import React from 'react';
import {useTranslate} from '@akeneo-pim-community/legacy-bridge';

const ToggleButton = () => {
    const __ = useTranslate();

    return (
        <div>
            <input type="checkbox" name="auto-sort"/> {__('Yes')}
        </div>
    );
};

export default ToggleButton;
