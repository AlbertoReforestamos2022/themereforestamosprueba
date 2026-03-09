import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl, TextareaControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { 
        formId, 
        showPhone, 
        showAddress, 
        showEmail, 
        showMap,
        phoneNumber,
        emailAddress,
        address,
        mapEmbedUrl,
        formTitle,
        infoTitle
    } = attributes;
    
    const blockProps = useBlockProps({
        className: 'reforestamos-contacto'
    });
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Contact Form Settings', 'reforestamos')}>
                    <TextControl
                        label={__('Form ID', 'reforestamos')}
                        help={__('Unique identifier for future integration with Communication Plugin', 'reforestamos')}
                        value={formId}
                        onChange={(value) => setAttributes({ formId: value })}
                        placeholder="contact-form-1"
                    />
                    <TextControl
                        label={__('Form Title', 'reforestamos')}
                        value={formTitle}
                        onChange={(value) => setAttributes({ formTitle: value })}
                    />
                </PanelBody>
                
                <PanelBody title={__('Contact Information', 'reforestamos')} initialOpen={false}>
                    <TextControl
                        label={__('Info Section Title', 'reforestamos')}
                        value={infoTitle}
                        onChange={(value) => setAttributes({ infoTitle: value })}
                    />
                    
                    <ToggleControl
                        label={__('Show Email', 'reforestamos')}
                        checked={showEmail}
                        onChange={(value) => setAttributes({ showEmail: value })}
                    />
                    {showEmail && (
                        <TextControl
                            label={__('Email Address', 'reforestamos')}
                            type="email"
                            value={emailAddress}
                            onChange={(value) => setAttributes({ emailAddress: value })}
                        />
                    )}
                    
                    <ToggleControl
                        label={__('Show Phone', 'reforestamos')}
                        checked={showPhone}
                        onChange={(value) => setAttributes({ showPhone: value })}
                    />
                    {showPhone && (
                        <TextControl
                            label={__('Phone Number', 'reforestamos')}
                            type="tel"
                            value={phoneNumber}
                            onChange={(value) => setAttributes({ phoneNumber: value })}
                        />
                    )}
                    
                    <ToggleControl
                        label={__('Show Address', 'reforestamos')}
                        checked={showAddress}
                        onChange={(value) => setAttributes({ showAddress: value })}
                    />
                    {showAddress && (
                        <TextareaControl
                            label={__('Address', 'reforestamos')}
                            value={address}
                            onChange={(value) => setAttributes({ address: value })}
                            rows={3}
                        />
                    )}
                </PanelBody>
                
                <PanelBody title={__('Map Settings', 'reforestamos')} initialOpen={false}>
                    <ToggleControl
                        label={__('Show Map', 'reforestamos')}
                        checked={showMap}
                        onChange={(value) => setAttributes({ showMap: value })}
                    />
                    {showMap && (
                        <TextareaControl
                            label={__('Map Embed URL', 'reforestamos')}
                            help={__('Paste the iframe src URL from Google Maps embed code', 'reforestamos')}
                            value={mapEmbedUrl}
                            onChange={(value) => setAttributes({ mapEmbedUrl: value })}
                            rows={3}
                        />
                    )}
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="reforestamos-contacto__editor-notice">
                    <p>
                        <strong>{__('Contact Block (Editor Preview)', 'reforestamos')}</strong>
                    </p>
                    <p>
                        {__('This block will display a contact form and contact information on the frontend.', 'reforestamos')}
                    </p>
                    {formId && (
                        <p>
                            <em>{__('Form ID:', 'reforestamos')} {formId}</em>
                        </p>
                    )}
                </div>
                
                <div className="container">
                    <div className="row g-4">
                        {/* Contact Form Column */}
                        <div className="col-lg-6">
                            <div className="reforestamos-contacto__form-preview">
                                <h3>{formTitle}</h3>
                                <div className="mb-3">
                                    <label className="form-label">{__('Name', 'reforestamos')}</label>
                                    <input type="text" className="form-control" disabled placeholder={__('Your name', 'reforestamos')} />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">{__('Email', 'reforestamos')}</label>
                                    <input type="email" className="form-control" disabled placeholder={__('your@email.com', 'reforestamos')} />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">{__('Subject', 'reforestamos')}</label>
                                    <input type="text" className="form-control" disabled placeholder={__('Message subject', 'reforestamos')} />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label">{__('Message', 'reforestamos')}</label>
                                    <textarea className="form-control" rows="4" disabled placeholder={__('Your message...', 'reforestamos')}></textarea>
                                </div>
                                <button type="button" className="btn btn-primary" disabled>
                                    {__('Send Message', 'reforestamos')}
                                </button>
                            </div>
                        </div>
                        
                        {/* Contact Info Column */}
                        <div className="col-lg-6">
                            <div className="reforestamos-contacto__info-preview">
                                <h3>{infoTitle}</h3>
                                
                                {showEmail && (
                                    <div className="reforestamos-contacto__info-item">
                                        <span className="dashicons dashicons-email"></span>
                                        <div>
                                            <strong>{__('Email', 'reforestamos')}</strong>
                                            <p>{emailAddress}</p>
                                        </div>
                                    </div>
                                )}
                                
                                {showPhone && (
                                    <div className="reforestamos-contacto__info-item">
                                        <span className="dashicons dashicons-phone"></span>
                                        <div>
                                            <strong>{__('Phone', 'reforestamos')}</strong>
                                            <p>{phoneNumber}</p>
                                        </div>
                                    </div>
                                )}
                                
                                {showAddress && (
                                    <div className="reforestamos-contacto__info-item">
                                        <span className="dashicons dashicons-location"></span>
                                        <div>
                                            <strong>{__('Address', 'reforestamos')}</strong>
                                            <p>{address}</p>
                                        </div>
                                    </div>
                                )}
                                
                                {showMap && mapEmbedUrl && (
                                    <div className="reforestamos-contacto__map-preview">
                                        <p><em>{__('Map will be displayed here on frontend', 'reforestamos')}</em></p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
