import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Save({ attributes }) {
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
    
    const blockProps = useBlockProps.save({
        className: 'reforestamos-contacto'
    });
    
    // Generate unique form ID
    const uniqueFormId = formId || `contact-form-${Date.now()}`;
    
    return (
        <div {...blockProps}>
            <div className="container">
                <div className="row g-4">
                    {/* Contact Form Column */}
                    <div className="col-lg-6">
                        <div className="reforestamos-contacto__form">
                            <h3 className="reforestamos-contacto__form-title">{formTitle}</h3>
                            
                            <form 
                                className="reforestamos-contact-form" 
                                data-form-id={uniqueFormId}
                                noValidate
                            >
                                {/* Honeypot field for spam protection */}
                                <input 
                                    type="text" 
                                    name="website" 
                                    className="reforestamos-honeypot" 
                                    tabIndex="-1" 
                                    autoComplete="off"
                                    aria-hidden="true"
                                />
                                
                                <div className="mb-3">
                                    <label htmlFor={`name-${uniqueFormId}`} className="form-label">
                                        {__('Name', 'reforestamos')} <span className="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        className="form-control" 
                                        id={`name-${uniqueFormId}`}
                                        name="name"
                                        required
                                        placeholder={__('Your name', 'reforestamos')}
                                    />
                                    <div className="invalid-feedback">
                                        {__('Please enter your name', 'reforestamos')}
                                    </div>
                                </div>
                                
                                <div className="mb-3">
                                    <label htmlFor={`email-${uniqueFormId}`} className="form-label">
                                        {__('Email', 'reforestamos')} <span className="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        className="form-control" 
                                        id={`email-${uniqueFormId}`}
                                        name="email"
                                        required
                                        placeholder={__('your@email.com', 'reforestamos')}
                                    />
                                    <div className="invalid-feedback">
                                        {__('Please enter a valid email address', 'reforestamos')}
                                    </div>
                                </div>
                                
                                <div className="mb-3">
                                    <label htmlFor={`subject-${uniqueFormId}`} className="form-label">
                                        {__('Subject', 'reforestamos')} <span className="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        className="form-control" 
                                        id={`subject-${uniqueFormId}`}
                                        name="subject"
                                        required
                                        placeholder={__('Message subject', 'reforestamos')}
                                    />
                                    <div className="invalid-feedback">
                                        {__('Please enter a subject', 'reforestamos')}
                                    </div>
                                </div>
                                
                                <div className="mb-3">
                                    <label htmlFor={`message-${uniqueFormId}`} className="form-label">
                                        {__('Message', 'reforestamos')} <span className="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        className="form-control" 
                                        id={`message-${uniqueFormId}`}
                                        name="message"
                                        rows="5"
                                        required
                                        placeholder={__('Your message...', 'reforestamos')}
                                    ></textarea>
                                    <div className="invalid-feedback">
                                        {__('Please enter your message', 'reforestamos')}
                                    </div>
                                </div>
                                
                                <div className="reforestamos-contacto__form-messages">
                                    <div className="alert alert-success d-none" role="alert">
                                        {__('Thank you! Your message has been sent successfully.', 'reforestamos')}
                                    </div>
                                    <div className="alert alert-danger d-none" role="alert">
                                        {__('Sorry, there was an error sending your message. Please try again.', 'reforestamos')}
                                    </div>
                                </div>
                                
                                <button type="submit" className="btn btn-primary btn-lg">
                                    <span className="button-text">{__('Send Message', 'reforestamos')}</span>
                                    <span className="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {/* Contact Info Column */}
                    <div className="col-lg-6">
                        <div className="reforestamos-contacto__info">
                            <h3 className="reforestamos-contacto__info-title">{infoTitle}</h3>
                            
                            {showEmail && (
                                <div className="reforestamos-contacto__info-item">
                                    <div className="reforestamos-contacto__info-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </div>
                                    <div className="reforestamos-contacto__info-content">
                                        <strong>{__('Email', 'reforestamos')}</strong>
                                        <p><a href={`mailto:${emailAddress}`}>{emailAddress}</a></p>
                                    </div>
                                </div>
                            )}
                            
                            {showPhone && (
                                <div className="reforestamos-contacto__info-item">
                                    <div className="reforestamos-contacto__info-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </div>
                                    <div className="reforestamos-contacto__info-content">
                                        <strong>{__('Phone', 'reforestamos')}</strong>
                                        <p><a href={`tel:${phoneNumber.replace(/\s/g, '')}`}>{phoneNumber}</a></p>
                                    </div>
                                </div>
                            )}
                            
                            {showAddress && (
                                <div className="reforestamos-contacto__info-item">
                                    <div className="reforestamos-contacto__info-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div className="reforestamos-contacto__info-content">
                                        <strong>{__('Address', 'reforestamos')}</strong>
                                        <p>{address}</p>
                                    </div>
                                </div>
                            )}
                            
                            {showMap && mapEmbedUrl && (
                                <div className="reforestamos-contacto__map">
                                    <iframe
                                        src={mapEmbedUrl}
                                        width="100%"
                                        height="300"
                                        style={{ border: 0 }}
                                        allowFullScreen=""
                                        loading="lazy"
                                        referrerPolicy="no-referrer-when-downgrade"
                                        title={__('Location Map', 'reforestamos')}
                                    ></iframe>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
