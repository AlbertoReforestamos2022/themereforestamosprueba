import { useBlockProps } from '@wordpress/block-editor';

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

export default function Save({ attributes }) {
    const { items, icon, listStyle, iconColor } = attributes;
    
    const blockProps = useBlockProps.save({
        className: `reforestamos-list reforestamos-list--${listStyle} reforestamos-list--icon-${iconColor}`
    });
    
    if (!items || items.length === 0) {
        return null;
    }
    
    return (
        <div {...blockProps}>
            <ul className="reforestamos-list__items">
                {items.map((item, index) => (
                    <li key={index} className="reforestamos-list__item">
                        <span 
                            className="reforestamos-list__icon"
                            dangerouslySetInnerHTML={{ __html: getIconSVG(icon) }}
                        />
                        <span className="reforestamos-list__text">{item}</span>
                    </li>
                ))}
            </ul>
        </div>
    );
}
