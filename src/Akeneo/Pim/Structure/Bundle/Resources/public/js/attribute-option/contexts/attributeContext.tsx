import React, {createContext, FC, useContext} from 'react';
export type AttributeContextState = {
    attributeId: number;
};
export const AttributeContext = createContext<AttributeContextState | undefined>(undefined);
AttributeContext.displayName = 'AttributeContext';

export const useAttributeContext = () => {
    return useContext(AttributeContext);
};

export const AttributeContextProvider: FC<AttributeContextState> = ({children, ...attribute}) => {
    return (
        <AttributeContext.Provider value={attribute}>
            {children}
        </AttributeContext.Provider>
    );
};
