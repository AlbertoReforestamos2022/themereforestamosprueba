import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Button, TextControl, TextareaControl, IconButton } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { initiatives, layout } = attributes;
    
    const blockProps = useBlockProps({
        className: `reforestamos-cards-iniciativas reforestamos-cards-iniciativas--${layout}`
    });
    
    const addInitiative = () => {
        const newInitiatives = [...initiatives, {
            title: '',
            description: '',
            image: { url: '', alt: '', id: null },
            link: '',
            category: '',
            stats: []
        }];
        setAttributes({ initiatives: newInitiatives });
    };
    
    const updateInitiative = (index, field, value) => {
        const newInitiatives = [...initiatives];
        newInitiatives[index] = {
            ...newInitiatives[index],
            [field]: value
        };
        setAttributes({ initiatives: newInitiatives });
    };
    
    const removeInitiative = (index) => {
        const newInitiatives = initiatives.filter((_, i) => i !== index);
        setAttributes({ initiatives: newInitiatives });
    };
    
    const addStat = (initiativeIndex) => {
        const newInitiatives = [...initiatives];
        if (!newInitiatives[initiativeIndex].stats) {
            newInitiatives[initiativeIndex].stats = [];
        }
        newInitiatives[initiativeIndex].stats.push({ label: '', value: '' });
        setAttributes({ initiatives: newInitiatives });
    };
    
    const updateStat = (initiativeIndex, statIndex, field, value) => {
        const newInitiatives = [...initiatives];
        newInitiatives[initiativeIndex].stats[statIndex][field] = value;
        setAttributes({ initiatives: newInitiatives });
    };
    
    const removeStat = (initiativeIndex, statIndex) => {
        const newInitiatives = [...initiatives];
        newInitiatives[initiativeIndex].stats = newInitiatives[initiativeIndex].stats.filter((_, i) => i !== statIndex);
        setAttributes({ initiatives: newInitiatives });
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Layout Settings', 'reforestamos')}>
                    <SelectControl
                        label={__('Layout', 'reforestamos')}
                        value={layout}
                        options={[
                            { label: __('Grid', 'reforestamos'), value: 'grid' },
                            { label: __('List', 'reforestamos'), value: 'list' },
                            { label: __('Masonry', 'reforestamos'), value: 'masonry' },
                            { label: __('Featured', 'reforestamos'), value: 'featured' }
                        ]}
                        onChange={(value) => setAttributes({ layout: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="reforestamos-cards-iniciativas__editor">
                    <div className="reforestamos-cards-iniciativas__header">
                        <h3>{__('Iniciativas', 'reforestamos')}</h3>
                        <Button 
                            variant="primary" 
                            onClick={addInitiative}
                        >
                            {__('Add Initiative', 'reforestamos')}
                        </Button>
                    </div>
                    
                    {initiatives.length === 0 && (
                        <div className="reforestamos-cards-iniciativas__empty">
                            <p>{__('No initiatives added yet. Click "Add Initiative" to get started.', 'reforestamos')}</p>
                        </div>
                    )}
                    
                    {initiatives.map((initiative, index) => (
                        <div key={index} className="reforestamos-cards-iniciativas__item">
                            <div className="reforestamos-cards-iniciativas__item-header">
                                <h4>{__('Initiative', 'reforestamos')} {index + 1}</h4>
                                <Button 
                                    variant="secondary" 
                                    isDestructive 
                                    onClick={() => removeInitiative(index)}
                                >
                                    {__('Remove', 'reforestamos')}
                                </Button>
                            </div>
                            
                            <TextControl
                                label={__('Title', 'reforestamos')}
                                value={initiative.title}
                                onChange={(value) => updateInitiative(index, 'title', value)}
                                placeholder={__('Enter initiative title...', 'reforestamos')}
                            />
                            
                            <TextareaControl
                                label={__('Description', 'reforestamos')}
                                value={initiative.description}
                                onChange={(value) => updateInitiative(index, 'description', value)}
                                placeholder={__('Enter initiative description...', 'reforestamos')}
                                rows={4}
                            />
                            
                            <TextControl
                                label={__('Category', 'reforestamos')}
                                value={initiative.category}
                                onChange={(value) => updateInitiative(index, 'category', value)}
                                placeholder={__('e.g., Reforestación, Educación...', 'reforestamos')}
                            />
                            
                            <TextControl
                                label={__('Link URL', 'reforestamos')}
                                value={initiative.link}
                                onChange={(value) => updateInitiative(index, 'link', value)}
                                placeholder={__('https://...', 'reforestamos')}
                                type="url"
                            />
                            
                            <div className="reforestamos-cards-iniciativas__image-control">
                                <label>{__('Image', 'reforestamos')}</label>
                                <MediaUpload
                                    onSelect={(media) => updateInitiative(index, 'image', {
                                        url: media.url,
                                        alt: media.alt,
                                        id: media.id
                                    })}
                                    allowedTypes={['image']}
                                    value={initiative.image?.id}
                                    render={({ open }) => (
                                        <div className="reforestamos-cards-iniciativas__image-upload">
                                            {initiative.image?.url ? (
                                                <div className="reforestamos-cards-iniciativas__image-preview">
                                                    <img src={initiative.image.url} alt={initiative.image.alt || ''} />
                                                    <Button 
                                                        variant="secondary" 
                                                        onClick={open}
                                                    >
                                                        {__('Change Image', 'reforestamos')}
                                                    </Button>
                                                </div>
                                            ) : (
                                                <Button 
                                                    variant="secondary" 
                                                    onClick={open}
                                                >
                                                    {__('Select Image', 'reforestamos')}
                                                </Button>
                                            )}
                                        </div>
                                    )}
                                />
                            </div>
                            
                            <div className="reforestamos-cards-iniciativas__stats">
                                <div className="reforestamos-cards-iniciativas__stats-header">
                                    <label>{__('Statistics', 'reforestamos')}</label>
                                    <Button 
                                        variant="secondary" 
                                        isSmall 
                                        onClick={() => addStat(index)}
                                    >
                                        {__('Add Stat', 'reforestamos')}
                                    </Button>
                                </div>
                                
                                {initiative.stats && initiative.stats.map((stat, statIndex) => (
                                    <div key={statIndex} className="reforestamos-cards-iniciativas__stat">
                                        <TextControl
                                            label={__('Value', 'reforestamos')}
                                            value={stat.value}
                                            onChange={(value) => updateStat(index, statIndex, 'value', value)}
                                            placeholder={__('e.g., 10,000', 'reforestamos')}
                                        />
                                        <TextControl
                                            label={__('Label', 'reforestamos')}
                                            value={stat.label}
                                            onChange={(value) => updateStat(index, statIndex, 'label', value)}
                                            placeholder={__('e.g., árboles plantados', 'reforestamos')}
                                        />
                                        <Button 
                                            variant="secondary" 
                                            isDestructive 
                                            isSmall 
                                            onClick={() => removeStat(index, statIndex)}
                                        >
                                            {__('Remove', 'reforestamos')}
                                        </Button>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
