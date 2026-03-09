import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { title, subtitle, backgroundImage, buttonText, buttonUrl, overlayOpacity, minHeight } = attributes;
    
    const blockProps = useBlockProps.save({
        style: {
            backgroundImage: backgroundImage.url ? `url(${backgroundImage.url})` : 'none',
            minHeight: minHeight,
        },
        className: 'reforestamos-hero'
    });
    
    return (
        <div {...blockProps}>
            <div className="hero-overlay" style={{ opacity: overlayOpacity }}></div>
            <div className="hero-content container">
                <RichText.Content tagName="h1" value={title} className="hero-title" />
                <RichText.Content tagName="p" value={subtitle} className="hero-subtitle" />
                {buttonText && buttonUrl && (
                    <a href={buttonUrl} className="btn btn-primary btn-lg">
                        <RichText.Content tagName="span" value={buttonText} />
                    </a>
                )}
            </div>
        </div>
    );
}
