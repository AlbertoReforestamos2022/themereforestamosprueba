import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { initiatives, layout } = attributes;
    
    const blockProps = useBlockProps.save({
        className: `reforestamos-cards-iniciativas reforestamos-cards-iniciativas--${layout}`
    });
    
    if (!initiatives || initiatives.length === 0) {
        return null;
    }
    
    return (
        <div {...blockProps}>
            <div className={`reforestamos-cards-iniciativas__container container`}>
                <div className={`reforestamos-cards-iniciativas__grid reforestamos-cards-iniciativas__grid--${layout}`}>
                    {initiatives.map((initiative, index) => {
                        const CardWrapper = initiative.link ? 'a' : 'div';
                        const cardProps = initiative.link ? {
                            href: initiative.link,
                            className: `reforestamos-cards-iniciativas__card reforestamos-cards-iniciativas__card--link ${layout === 'featured' && index === 0 ? 'reforestamos-cards-iniciativas__card--featured' : ''}`,
                            rel: 'noopener noreferrer'
                        } : {
                            className: `reforestamos-cards-iniciativas__card ${layout === 'featured' && index === 0 ? 'reforestamos-cards-iniciativas__card--featured' : ''}`
                        };
                        
                        return (
                            <CardWrapper key={index} {...cardProps}>
                                {initiative.image?.url && (
                                    <div className="reforestamos-cards-iniciativas__card-image">
                                        <img 
                                            src={initiative.image.url} 
                                            alt={initiative.image.alt || initiative.title || ''} 
                                            loading="lazy"
                                        />
                                        {initiative.category && (
                                            <div className="reforestamos-cards-iniciativas__card-category">
                                                {initiative.category}
                                            </div>
                                        )}
                                    </div>
                                )}
                                
                                <div className="reforestamos-cards-iniciativas__card-content">
                                    {initiative.title && (
                                        <h3 className="reforestamos-cards-iniciativas__card-title">
                                            {initiative.title}
                                        </h3>
                                    )}
                                    
                                    {initiative.description && (
                                        <p className="reforestamos-cards-iniciativas__card-description">
                                            {initiative.description}
                                        </p>
                                    )}
                                    
                                    {initiative.stats && initiative.stats.length > 0 && (
                                        <div className="reforestamos-cards-iniciativas__card-stats">
                                            {initiative.stats.map((stat, statIndex) => (
                                                <div key={statIndex} className="reforestamos-cards-iniciativas__stat">
                                                    <div className="reforestamos-cards-iniciativas__stat-value">
                                                        {stat.value}
                                                    </div>
                                                    <div className="reforestamos-cards-iniciativas__stat-label">
                                                        {stat.label}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                    
                                    {initiative.link && (
                                        <div className="reforestamos-cards-iniciativas__card-link">
                                            <span className="reforestamos-cards-iniciativas__card-link-text">
                                                Ver más
                                            </span>
                                            <span className="reforestamos-cards-iniciativas__card-link-icon">
                                                →
                                            </span>
                                        </div>
                                    )}
                                </div>
                            </CardWrapper>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
