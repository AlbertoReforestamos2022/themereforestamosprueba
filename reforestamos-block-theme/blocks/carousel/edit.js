import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, Button, Placeholder } from '@wordpress/components';
import { DndContext, closestCenter } from '@dnd-kit/core';
import { arrayMove, SortableContext, useSortable, verticalListSortingStrategy } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

// Sortable Image Item Component
function SortableImageItem({ image, onRemove, onUpdateCaption }) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
    } = useSortable({ id: image.id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    };

    return (
        <div ref={setNodeRef} style={style} className="carousel-image-item">
            <div className="carousel-image-preview">
                <img src={image.url} alt={image.alt || ''} />
                <div className="carousel-image-controls">
                    <Button
                        {...attributes}
                        {...listeners}
                        icon="move"
                        label={__('Reorder', 'reforestamos')}
                        className="carousel-drag-handle"
                    />
                    <Button
                        icon="trash"
                        label={__('Remove', 'reforestamos')}
                        onClick={onRemove}
                        isDestructive
                    />
                </div>
            </div>
            <input
                type="text"
                value={image.caption || ''}
                onChange={(e) => onUpdateCaption(e.target.value)}
                placeholder={__('Add caption...', 'reforestamos')}
                className="carousel-caption-input"
            />
        </div>
    );
}

export default function Edit({ attributes, setAttributes, clientId }) {
    const { images, autoplay, interval, showControls, showIndicators } = attributes;
    
    const blockProps = useBlockProps({
        className: 'reforestamos-carousel-editor'
    });

    const onSelectImages = (newImages) => {
        const formattedImages = newImages.map(img => ({
            id: img.id,
            url: img.url,
            alt: img.alt || '',
            caption: img.caption || ''
        }));
        setAttributes({ images: formattedImages });
    };

    const removeImage = (indexToRemove) => {
        const newImages = images.filter((_, index) => index !== indexToRemove);
        setAttributes({ images: newImages });
    };

    const updateImageCaption = (index, caption) => {
        const newImages = [...images];
        newImages[index] = { ...newImages[index], caption };
        setAttributes({ images: newImages });
    };

    const handleDragEnd = (event) => {
        const { active, over } = event;

        if (active.id !== over.id) {
            const oldIndex = images.findIndex(img => img.id === active.id);
            const newIndex = images.findIndex(img => img.id === over.id);
            const newImages = arrayMove(images, oldIndex, newIndex);
            setAttributes({ images: newImages });
        }
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Carousel Settings', 'reforestamos')}>
                    <ToggleControl
                        label={__('Autoplay', 'reforestamos')}
                        checked={autoplay}
                        onChange={(value) => setAttributes({ autoplay: value })}
                        help={__('Automatically cycle through slides', 'reforestamos')}
                    />
                    {autoplay && (
                        <RangeControl
                            label={__('Interval (milliseconds)', 'reforestamos')}
                            value={interval}
                            onChange={(value) => setAttributes({ interval: value })}
                            min={1000}
                            max={10000}
                            step={500}
                            help={__('Time between slide transitions', 'reforestamos')}
                        />
                    )}
                    <ToggleControl
                        label={__('Show Controls', 'reforestamos')}
                        checked={showControls}
                        onChange={(value) => setAttributes({ showControls: value })}
                        help={__('Show previous/next navigation arrows', 'reforestamos')}
                    />
                    <ToggleControl
                        label={__('Show Indicators', 'reforestamos')}
                        checked={showIndicators}
                        onChange={(value) => setAttributes({ showIndicators: value })}
                        help={__('Show slide position indicators', 'reforestamos')}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                {images.length === 0 ? (
                    <MediaUploadCheck>
                        <Placeholder
                            icon="images-alt2"
                            label={__('Carousel', 'reforestamos')}
                            instructions={__('Select images to create a carousel', 'reforestamos')}
                        >
                            <MediaUpload
                                onSelect={onSelectImages}
                                allowedTypes={['image']}
                                multiple
                                gallery
                                value={images.map(img => img.id)}
                                render={({ open }) => (
                                    <Button onClick={open} variant="primary">
                                        {__('Select Images', 'reforestamos')}
                                    </Button>
                                )}
                            />
                        </Placeholder>
                    </MediaUploadCheck>
                ) : (
                    <div className="carousel-editor-container">
                        <div className="carousel-editor-header">
                            <h3>{__('Carousel Images', 'reforestamos')}</h3>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={onSelectImages}
                                    allowedTypes={['image']}
                                    multiple
                                    gallery
                                    value={images.map(img => img.id)}
                                    render={({ open }) => (
                                        <Button onClick={open} variant="secondary">
                                            {__('Edit Gallery', 'reforestamos')}
                                        </Button>
                                    )}
                                />
                            </MediaUploadCheck>
                        </div>
                        <DndContext
                            collisionDetection={closestCenter}
                            onDragEnd={handleDragEnd}
                        >
                            <SortableContext
                                items={images.map(img => img.id)}
                                strategy={verticalListSortingStrategy}
                            >
                                <div className="carousel-images-list">
                                    {images.map((image, index) => (
                                        <SortableImageItem
                                            key={image.id}
                                            image={image}
                                            onRemove={() => removeImage(index)}
                                            onUpdateCaption={(caption) => updateImageCaption(index, caption)}
                                        />
                                    ))}
                                </div>
                            </SortableContext>
                        </DndContext>
                        <div className="carousel-preview-info">
                            <p>
                                {__('Preview:', 'reforestamos')} {images.length} {images.length === 1 ? __('image', 'reforestamos') : __('images', 'reforestamos')}
                                {autoplay && ` • ${__('Autoplay', 'reforestamos')}: ${interval}ms`}
                            </p>
                        </div>
                    </div>
                )}
            </div>
        </>
    );
}
