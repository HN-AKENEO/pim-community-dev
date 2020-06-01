import {useEffect} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import {AttributeOptionsState} from '../store/store';
import {initializeAttributeOptionsAction} from '../reducers';
import baseFetcher from '../fetchers/baseFetcher';
import {useAttributeContext} from '../contexts';
import {useRoute} from '@akeneo-pim-community/legacy-bridge';

const useAttributeOptions = () => {
    const dispatchAction = useDispatch();
    const attribute = useAttributeContext();
    // @ts-ignore
    const route = useRoute('pim_enrich_attributeoption_index', {attributeId: attribute.attributeId});

    useEffect(() => {
        (async () => {
            const attributeOptions = await baseFetcher(route);
            dispatchAction(initializeAttributeOptionsAction(attributeOptions));
        })();
    }, []);

    return useSelector((state: AttributeOptionsState) => state.attributeOptions);
};

export default useAttributeOptions;
