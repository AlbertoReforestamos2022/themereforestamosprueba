import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, Button, SelectControl, TextControl, IconButton } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { events, orientation } = attributes;
    
    const blockProps = useBlockProps({
        className: `reforestamos-timeline timeline-${orientation}`
    });
    
    const addEvent = () => {
        const newEvents = [...events, {
            date: '',
            title: '',
            description: '',
            icon: '',
            image: { url: '', alt: '', id: null }
        }];
        setAttributes({ events: newEvents });
    };
    
    const updateEvent = (index, field, value) => {
        const newEvents = [...events];
        newEvents[index][field] = value;
        setAttributes({ events: newEvents });
    };
    
    const removeEvent = (index) => {
        const newEvents = events.filter((_, i) => i !== index);
        setAttributes({ events: newEvents });
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Timeline Settings', 'reforestamos')}>
                    <SelectControl
                        label={__('Orientation', 'reforestamos')}
                        value={orientation}
                        options={[
                            { label: __('Vertical', 'reforestamos'), value: 'vertical' },
                            { label: __('Horizontal', 'reforestamos'), value: 'horizontal' }
                        ]}
                        onChange={(value) => setAttributes({ orientation: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="timeline-container">
                    {events.length === 0 ? (
                        <div className="timeline-empty">
                            <p>{__('No events added yet. Click "Add Event" to start.', 'reforestamos')}</p>
                        </div>
                    ) : (
                        <div className="timeline-events">
                            {events.map((event, index) => (
                                <div key={index} className={`timeline-event timeline-event-${index % 2 === 0 ? 'left' : 'right'}`}>
                                    <div className="timeline-event-content">
                                        <div className="timeline-event-controls">
                                            <Button
                                                isDestructive
                                                isSmall
                                                onClick={() => removeEvent(index)}
                                            >
                                                {__('Remove', 'reforestamos')}
                                            </Button>
                                        </div>
                                        
                                        <TextControl
                                            label={__('Date', 'reforestamos')}
                                            value={event.date}
                                            onChange={(value) => updateEvent(index, 'date', value)}
                                            placeholder={__('e.g., 2020', 'reforestamos')}
                                        />
                                        
                                        <RichText
                                            tagName="h3"
                                            value={event.title}
                                            onChange={(value) => updateEvent(index, 'title', value)}
                                            placeholder={__('Event title...', 'reforestamos')}
                                            className="timeline-event-title"
                                        />
                                        
                                        <RichText
                                            tagName="p"
                                            value={event.description}
                                            onChange={(value) => updateEvent(index, 'description', value)}
                                            placeholder={__('Event description...', 'reforestamos')}
                                            className="timeline-event-description"
                                        />
                                        
                                        <TextControl
                                            label={__('Icon (optional)', 'reforestamos')}
                                            value={event.icon}
                                            onChange={(value) => updateEvent(index, 'icon', value)}
                                            placeholder={__('e.g., dashicons-calendar', 'reforestamos')}
                                            help={__('Enter a Dashicons class name', 'reforestamos')}
                                        />
                                        
                                        <div className="timeline-event-image">
                                            <label>{__('Image (optional)', 'reforestamos')}</label>
                                            <MediaUpload
                                                onSelect={(media) => updateEvent(index, 'image', {
                                                    url: media.url,
                                                    alt: media.alt,
                                                    id: media.id
                                                })}
                                                allowedTypes={['image']}
                                                value={event.image.id}
                                                render={({ open }) => (
                                                    <div>
                                                        {event.image.url && (
                                                            <img src={event.image.url} alt={event.image.alt} style={{ maxWidth: '100%', marginBottom: '10px' }} />
                                                        )}
                                                        <Button onClick={open} variant="secondary">
                                                            {event.image.url ? __('Change Image', 'reforestamos') : __('Select Image', 'reforestamos')}
                                                        </Button>
                                                        {event.image.url && (
                                                            <Button
                                                                onClick={() => updateEvent(index, 'image', { url: '', alt: '', id: null })}
                                                                isDestructive
                                                                style={{ marginLeft: '10px' }}
                                                            >
                                                                {__('Remove Image', 'reforestamos')}
                                                            </Button>
                                                        )}
                                                    </div>
                                                )}
                                            />
                                        </div>
                                    </div>
                                    <div className="timeline-event-marker">
                                        {event.icon && <span className={`dashicons ${event.icon}`}></span>}
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                    
                    <div className="timeline-add-event">
                        <Button isPrimary onClick={addEvent}>
                            {__('Add Event', 'reforestamos')}
                        </Button>
                    </div>
                </div>
            </div>
        </>
    );
}
