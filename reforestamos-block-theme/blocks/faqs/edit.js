import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl, Button, TextareaControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

const STYLE_OPTIONS = [
    { label: __('Default', 'reforestamos'), value: 'default' },
    { label: __('Bordered', 'reforestamos'), value: 'bordered' },
    { label: __('Minimal', 'reforestamos'), value: 'minimal' }
];

export default function Edit({ attributes, setAttributes, clientId }) {
    const { faqs, openFirst, allowMultiple, accordionStyle } = attributes;
    const [newQuestion, setNewQuestion] = useState('');
    const [newAnswer, setNewAnswer] = useState('');
    
    const blockProps = useBlockProps({
        className: `reforestamos-faqs reforestamos-faqs--${accordionStyle}`
    });
    
    const addFaq = () => {
        if (newQuestion.trim() && newAnswer.trim()) {
            setAttributes({ 
                faqs: [...faqs, { 
                    question: newQuestion.trim(), 
                    answer: newAnswer.trim() 
                }] 
            });
            setNewQuestion('');
            setNewAnswer('');
        }
    };
    
    const removeFaq = (index) => {
        const newFaqs = faqs.filter((_, i) => i !== index);
        setAttributes({ faqs: newFaqs });
    };
    
    const updateFaq = (index, field, value) => {
        const newFaqs = [...faqs];
        newFaqs[index][field] = value;
        setAttributes({ faqs: newFaqs });
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('FAQ Settings', 'reforestamos')}>
                    <ToggleControl
                        label={__('Open First Item', 'reforestamos')}
                        help={__('Open the first FAQ item by default', 'reforestamos')}
                        checked={openFirst}
                        onChange={(value) => setAttributes({ openFirst: value })}
                    />
                    <ToggleControl
                        label={__('Allow Multiple Open', 'reforestamos')}
                        help={__('Allow multiple FAQ items to be open at the same time', 'reforestamos')}
                        checked={allowMultiple}
                        onChange={(value) => setAttributes({ allowMultiple: value })}
                    />
                    <SelectControl
                        label={__('Accordion Style', 'reforestamos')}
                        value={accordionStyle}
                        options={STYLE_OPTIONS}
                        onChange={(value) => setAttributes({ accordionStyle: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="reforestamos-faqs__editor">
                    <h4>{__('FAQ Items', 'reforestamos')}</h4>
                    
                    {faqs.length > 0 && (
                        <div className="reforestamos-faqs__items">
                            {faqs.map((faq, index) => (
                                <div key={index} className="reforestamos-faqs__item-editor">
                                    <div className="reforestamos-faqs__item-header">
                                        <strong>{__('Question', 'reforestamos')} {index + 1}</strong>
                                        <Button
                                            icon="trash"
                                            label={__('Remove FAQ', 'reforestamos')}
                                            onClick={() => removeFaq(index)}
                                            isDestructive
                                            size="small"
                                        />
                                    </div>
                                    <TextareaControl
                                        label={__('Question', 'reforestamos')}
                                        value={faq.question}
                                        onChange={(value) => updateFaq(index, 'question', value)}
                                        placeholder={__('Enter question...', 'reforestamos')}
                                        rows={2}
                                    />
                                    <TextareaControl
                                        label={__('Answer', 'reforestamos')}
                                        value={faq.answer}
                                        onChange={(value) => updateFaq(index, 'answer', value)}
                                        placeholder={__('Enter answer...', 'reforestamos')}
                                        rows={4}
                                    />
                                </div>
                            ))}
                        </div>
                    )}
                    
                    <div className="reforestamos-faqs__add-item">
                        <h5>{__('Add New FAQ', 'reforestamos')}</h5>
                        <TextareaControl
                            label={__('Question', 'reforestamos')}
                            value={newQuestion}
                            onChange={setNewQuestion}
                            placeholder={__('Enter question...', 'reforestamos')}
                            rows={2}
                        />
                        <TextareaControl
                            label={__('Answer', 'reforestamos')}
                            value={newAnswer}
                            onChange={setNewAnswer}
                            placeholder={__('Enter answer...', 'reforestamos')}
                            rows={4}
                        />
                        <Button
                            variant="primary"
                            onClick={addFaq}
                            disabled={!newQuestion.trim() || !newAnswer.trim()}
                        >
                            {__('Add FAQ', 'reforestamos')}
                        </Button>
                    </div>
                    
                    {faqs.length === 0 && (
                        <p className="reforestamos-faqs__empty">
                            {__('No FAQs yet. Add your first FAQ above.', 'reforestamos')}
                        </p>
                    )}
                </div>
                
                {faqs.length > 0 && (
                    <div className="reforestamos-faqs__preview">
                        <h4>{__('Preview', 'reforestamos')}</h4>
                        <div className={`accordion accordion-${accordionStyle}`} id={`accordion-preview-${clientId}`}>
                            {faqs.map((faq, index) => {
                                const itemId = `faq-preview-${clientId}-${index}`;
                                const isOpen = openFirst && index === 0;
                                
                                return (
                                    <div key={index} className="accordion-item">
                                        <h2 className="accordion-header" id={`heading-${itemId}`}>
                                            <button
                                                className={`accordion-button ${!isOpen ? 'collapsed' : ''}`}
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target={`#collapse-${itemId}`}
                                                aria-expanded={isOpen}
                                                aria-controls={`collapse-${itemId}`}
                                            >
                                                {faq.question}
                                            </button>
                                        </h2>
                                        <div
                                            id={`collapse-${itemId}`}
                                            className={`accordion-collapse collapse ${isOpen ? 'show' : ''}`}
                                            aria-labelledby={`heading-${itemId}`}
                                            data-bs-parent={allowMultiple ? '' : `#accordion-preview-${clientId}`}
                                        >
                                            <div className="accordion-body">
                                                {faq.answer}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                )}
            </div>
        </>
    );
}
