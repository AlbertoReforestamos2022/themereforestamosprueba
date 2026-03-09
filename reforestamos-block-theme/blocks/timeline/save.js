import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { events, orientation } = attributes;
    
    const blockProps = useBlockProps.save({
        className: `reforestamos-timeline timeline-${orientation}`
    });
    
    if (events.length === 0) {
        return null;
    }
    
    return (
        <div {...blockProps}>
            <div className="timeline-container container">
                <div className="timeline-line"></div>
                <div className="timeline-events">
                    {events.map((event, index) => (
                        <div key={index} className={`timeline-event timeline-event-${index % 2 === 0 ? 'left' : 'right'}`}>
                            <div className="timeline-event-content">
                                {event.date && (
                                    <div className="timeline-event-date">{event.date}</div>
                                )}
                                
                                {event.title && (
                                    <RichText.Content 
                                        tagName="h3" 
                                        value={event.title} 
                                        className="timeline-event-title" 
                                    />
                                )}
                                
                                {event.description && (
                                    <RichText.Content 
                                        tagName="p" 
                                        value={event.description} 
                                        className="timeline-event-description" 
                                    />
                                )}
                                
                                {event.image.url && (
                                    <div className="timeline-event-image">
                                        <img 
                                            src={event.image.url} 
                                            alt={event.image.alt || event.title} 
                                            loading="lazy"
                                        />
                                    </div>
                                )}
                            </div>
                            
                            <div className="timeline-event-marker">
                                {event.icon ? (
                                    <span className={`dashicons ${event.icon}`}></span>
                                ) : (
                                    <span className="timeline-dot"></span>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
