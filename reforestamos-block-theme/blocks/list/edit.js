import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';

const ICON_OPTIONS = [
    { label: __('Check', 'reforestamos'), value: 'check' },
    { label: __('Arrow', 'reforestamos'), value: 'arrow' },
    { label: __('Star', 'reforestamos'), value: 'star' },
    { label: __('Leaf', 'reforestamos'), value: 'leaf' },
    { label: __('Circle', 'reforestamos'), value: 'circle' }
];

const STYLE_OPTIONS = [
    { label: __('Default', 'reforestamos'), value: 'default' },
    { label: __('Bordered', 'reforestamos'), value: 'bordered' },
    { label: __('Minimal', 'reforestamos'), value: 'minimal' }
];

const COLOR_OPTIONS = [
    { label: __('Verde Reforestamos', 'reforestamos'), value: 'primary' },
    { label: __('Verde Claro', 'reforestamos'), value: 'secondary' },
    { label: __('Naranja Acento', 'reforestamos'), value: 'accent' },
    { label: __('Verde Oscuro', 'reforestamos'), value: 'dark' },
    { label: __('Negro', 'reforestamos'), value: 'black' }
];

const getIconSVG = (iconType) => {
    const icons = {
        check: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
        arrow: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>',
        star: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>',
        leaf: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>',
        circle: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="8"/></svg>'
    };
    return icons[iconType] || icons.check;
};

export default function Edit({ attributes, setAttributes }) {
    const { items, icon, listStyle, iconColor } = attributes;
    const [newItemText, setNewItemText] = useState('');
    
    const blockProps = useBlockProps({
        className: `reforestamos-list reforestamos-list--${listStyle} reforestamos-list--icon-${iconColor}`
    });
    
    const addItem = () => {
        if (newItemText.trim()) {
            setAttributes({ items: [...items, newItemText.trim()] });
            setNewItemText('');
        }
    };
    
    const removeItem = (index) => {
        const newItems = items.filter((_, i) => i !== index);
        setAttributes({ items: newItems });
    };
    
    const updateItem = (index, value) => {
        const newItems = [...items];
        newItems[index] = value;
        setAttributes({ items: newItems });
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('List Settings', 'reforestamos')}>
                    <SelectControl
                        label={__('Icon Type', 'reforestamos')}
                        value={icon}
                        options={ICON_OPTIONS}
                        onChange={(value) => setAttributes({ icon: value })}
                    />
                    <SelectControl
                        label={__('List Style', 'reforestamos')}
                        value={listStyle}
                        options={STYLE_OPTIONS}
                        onChange={(value) => setAttributes({ listStyle: value })}
                    />
                    <SelectControl
                        label={__('Icon Color', 'reforestamos')}
                        value={iconColor}
                        options={COLOR_OPTIONS}
                        onChange={(value) => setAttributes({ iconColor: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="reforestamos-list__editor">
                    <h4>{__('List Items', 'reforestamos')}</h4>
                    
                    {items.length > 0 && (
                        <ul className="reforestamos-list__items">
                            {items.map((item, index) => (
                                <li key={index} className="reforestamos-list__item">
                                    <span 
                                        className="reforestamos-list__icon"
                                        dangerouslySetInnerHTML={{ __html: getIconSVG(icon) }}
                                    />
                                    <TextControl
                                        value={item}
                                        onChange={(value) => updateItem(index, value)}
                                        placeholder={__('List item text...', 'reforestamos')}
                                    />
                                    <Button
                                        icon="trash"
                                        label={__('Remove item', 'reforestamos')}
                                        onClick={() => removeItem(index)}
                                        isDestructive
                                        isSmall
                                    />
                                </li>
                            ))}
                        </ul>
                    )}
                    
                    <div className="reforestamos-list__add-item">
                        <TextControl
                            value={newItemText}
                            onChange={setNewItemText}
                            placeholder={__('Enter new item...', 'reforestamos')}
                            onKeyPress={(e) => {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    addItem();
                                }
                            }}
                        />
                        <Button
                            variant="primary"
                            onClick={addItem}
                            disabled={!newItemText.trim()}
                        >
                            {__('Add Item', 'reforestamos')}
                        </Button>
                    </div>
                    
                    {items.length === 0 && (
                        <p className="reforestamos-list__empty">
                            {__('No items yet. Add your first item above.', 'reforestamos')}
                        </p>
                    )}
                </div>
                
                {items.length > 0 && (
                    <div className="reforestamos-list__preview">
                        <h4>{__('Preview', 'reforestamos')}</h4>
                        <ul className="reforestamos-list__items-preview">
                            {items.map((item, index) => (
                                <li key={index} className="reforestamos-list__item-preview">
                                    <span 
                                        className="reforestamos-list__icon"
                                        dangerouslySetInnerHTML={{ __html: getIconSVG(icon) }}
                                    />
                                    <span className="reforestamos-list__text">{item}</span>
                                </li>
                            ))}
                        </ul>
                    </div>
                )}
            </div>
        </>
    );
}
