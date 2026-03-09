import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { images, autoplay, interval, showControls, showIndicators } = attributes;
    
    if (!images || images.length === 0) {
        return null;
    }

    const blockProps = useBlockProps.save({
        className: 'reforestamos-carousel'
    });

    // Generate unique ID for this carousel instance
    const carouselId = `carousel-${Math.random().toString(36).substr(2, 9)}`;
    
    return (
        <div {...blockProps}>
            <div 
                id={carouselId}
                className="carousel slide" 
                data-bs-ride={autoplay ? 'carousel' : 'false'}
                data-bs-interval={autoplay ? interval : 'false'}
            >
                {showIndicators && images.length > 1 && (
                    <div className="carousel-indicators">
                        {images.map((_, index) => (
                            <button
                                key={index}
                                type="button"
                                data-bs-target={`#${carouselId}`}
                                data-bs-slide-to={index}
                                className={index === 0 ? 'active' : ''}
                                aria-current={index === 0 ? 'true' : 'false'}
                                aria-label={`Slide ${index + 1}`}
                            ></button>
                        ))}
                    </div>
                )}
                
                <div className="carousel-inner">
                    {images.map((image, index) => (
                        <div 
                            key={image.id} 
                            className={`carousel-item${index === 0 ? ' active' : ''}`}
                        >
                            <img 
                                src={image.url} 
                                className="d-block w-100" 
                                alt={image.alt || ''} 
                                loading={index === 0 ? 'eager' : 'lazy'}
                            />
                            {image.caption && (
                                <div className="carousel-caption d-none d-md-block">
                                    <p>{image.caption}</p>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
                
                {showControls && images.length > 1 && (
                    <>
                        <button 
                            className="carousel-control-prev" 
                            type="button" 
                            data-bs-target={`#${carouselId}`}
                            data-bs-slide="prev"
                        >
                            <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span className="visually-hidden">Previous</span>
                        </button>
                        <button 
                            className="carousel-control-next" 
                            type="button" 
                            data-bs-target={`#${carouselId}`}
                            data-bs-slide="next"
                        >
                            <span className="carousel-control-next-icon" aria-hidden="true"></span>
                            <span className="visually-hidden">Next</span>
                        </button>
                    </>
                )}
            </div>
        </div>
    );
}
