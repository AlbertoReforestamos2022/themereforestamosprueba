import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, SelectControl, IconButton } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
    const { galleries, defaultTab } = attributes;
    const [activeTab, setActiveTab] = useState(defaultTab);
    
    const blockProps = useBlockProps({
        className: 'reforestamos-galeria-tabs'
    });

    const addGallery = () => {
        const newGalleries = [...galleries, {
            id: Date.now(),
            title: __('Nueva Categoría', 'reforestamos'),
            images: []
        }];
        setAttributes({ galleries: newGalleries });
    };

    const updateGallery = (index, field, value) => {
        const newGalleries = [...galleries];
        newGalleries[index][field] = value;
        setAttributes({ galleries: newGalleries });
    };

    const removeGallery = (index) => {
        const newGalleries = galleries.filter((_, i) => i !== index);
        setAttributes({ galleries: newGalleries });
        if (activeTab >= newGalleries.length) {
            setActiveTab(Math.max(0, newGalleries.length - 1));
        }
    };

    const addImages = (index, newImages) => {
        const newGalleries = [...galleries];
        const existingImages = newGalleries[index].images || [];
        newGalleries[index].images = [
            ...existingImages,
            ...newImages.map(img => ({
                id: img.id,
                url: img.url,
                alt: img.alt || '',
                caption: img.caption || ''
            }))
        ];
        setAttributes({ galleries: newGalleries });
    };

    const removeImage = (galleryIndex, imageIndex) => {
        const newGalleries = [...galleries];
        newGalleries[galleryIndex].images = newGalleries[galleryIndex].images.filter((_, i) => i !== imageIndex);
        setAttributes({ galleries: newGalleries });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Configuración de Galería', 'reforestamos')}>
                    <SelectControl
                        label={__('Pestaña por defecto', 'reforestamos')}
                        value={defaultTab}
                        options={galleries.map((gallery, index) => ({
                            label: gallery.title || `${__('Categoría', 'reforestamos')} ${index + 1}`,
                            value: index
                        }))}
                        onChange={(value) => setAttributes({ defaultTab: parseInt(value) })}
                    />
                    <Button 
                        variant="secondary" 
                        onClick={addGallery}
                        style={{ marginTop: '10px' }}
                    >
                        {__('Añadir Categoría', 'reforestamos')}
                    </Button>
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div className="galeria-tabs-editor">
                    {galleries.length === 0 ? (
                        <div className="galeria-tabs-placeholder">
                            <p>{__('No hay categorías. Añade una categoría para comenzar.', 'reforestamos')}</p>
                            <Button variant="primary" onClick={addGallery}>
                                {__('Añadir Primera Categoría', 'reforestamos')}
                            </Button>
                        </div>
                    ) : (
                        <>
                            {/* Tabs Navigation */}
                            <ul className="nav nav-tabs" role="tablist">
                                {galleries.map((gallery, index) => (
                                    <li className="nav-item" key={gallery.id}>
                                        <button
                                            className={`nav-link ${activeTab === index ? 'active' : ''}`}
                                            onClick={() => setActiveTab(index)}
                                            type="button"
                                        >
                                            {gallery.title}
                                        </button>
                                    </li>
                                ))}
                            </ul>

                            {/* Tab Content */}
                            <div className="tab-content">
                                {galleries.map((gallery, galleryIndex) => (
                                    <div
                                        key={gallery.id}
                                        className={`tab-pane ${activeTab === galleryIndex ? 'active' : ''}`}
                                        style={{ display: activeTab === galleryIndex ? 'block' : 'none' }}
                                    >
                                        <div className="gallery-controls">
                                            <TextControl
                                                label={__('Nombre de la categoría', 'reforestamos')}
                                                value={gallery.title}
                                                onChange={(value) => updateGallery(galleryIndex, 'title', value)}
                                            />
                                            <Button
                                                variant="tertiary"
                                                isDestructive
                                                onClick={() => removeGallery(galleryIndex)}
                                            >
                                                {__('Eliminar Categoría', 'reforestamos')}
                                            </Button>
                                        </div>

                                        <MediaUploadCheck>
                                            <MediaUpload
                                                onSelect={(images) => addImages(galleryIndex, images)}
                                                allowedTypes={['image']}
                                                multiple
                                                gallery
                                                value={gallery.images?.map(img => img.id) || []}
                                                render={({ open }) => (
                                                    <Button 
                                                        variant="secondary" 
                                                        onClick={open}
                                                        style={{ marginBottom: '15px' }}
                                                    >
                                                        {__('Añadir Imágenes', 'reforestamos')}
                                                    </Button>
                                                )}
                                            />
                                        </MediaUploadCheck>

                                        {gallery.images && gallery.images.length > 0 && (
                                            <div className="gallery-grid">
                                                {gallery.images.map((image, imageIndex) => (
                                                    <div key={image.id} className="gallery-item">
                                                        <img src={image.url} alt={image.alt} />
                                                        <Button
                                                            className="remove-image"
                                                            isDestructive
                                                            onClick={() => removeImage(galleryIndex, imageIndex)}
                                                        >
                                                            ×
                                                        </Button>
                                                    </div>
                                                ))}
                                            </div>
                                        )}

                                        {(!gallery.images || gallery.images.length === 0) && (
                                            <p className="no-images-message">
                                                {__('No hay imágenes en esta categoría.', 'reforestamos')}
                                            </p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </>
                    )}
                </div>
            </div>
        </>
    );
}
