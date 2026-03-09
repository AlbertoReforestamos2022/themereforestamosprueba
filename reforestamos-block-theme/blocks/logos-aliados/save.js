import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { logos, columns, linkable } = attributes;

    const blockProps = useBlockProps.save({
        className: 'reforestamos-logos-aliados'
    });

    if (!logos || logos.length === 0) {
        return null;
    }

    return (
        <div {...blockProps}>
            <div className="container">
                <div className={`logos-grid row row-cols-2 row-cols-md-${Math.min(columns, 3)} row-cols-lg-${columns} g-4`}>
                    {logos.map((logo) => {
                        if (!logo.imageUrl) {
                            return null;
                        }

                        const logoImage = (
                            <img 
                                src={logo.imageUrl} 
                                alt={logo.alt || logo.name || 'Logo aliado'} 
                                className="logo-image"
                                loading="lazy"
                            />
                        );

                        return (
                            <div key={logo.id} className="col">
                                <div className="logo-item">
                                    {linkable && logo.url ? (
                                        <a 
                                            href={logo.url} 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            className="logo-link"
                                            title={logo.name || logo.alt}
                                        >
                                            {logoImage}
                                        </a>
                                    ) : (
                                        <div className="logo-wrapper">
                                            {logoImage}
                                        </div>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
