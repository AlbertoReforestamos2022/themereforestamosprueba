import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { menuId, logo, sticky, backgroundColor, textColor, showLanguageSwitcher, transparentOnTop } = attributes;
    
    const blockProps = useBlockProps.save({
        className: `reforestamos-header-navbar ${sticky ? 'sticky-header' : ''} ${transparentOnTop ? 'transparent-on-top' : ''}`,
        'data-menu-id': menuId,
        'data-sticky': sticky,
        'data-transparent-on-top': transparentOnTop
    });
    
    return (
        <header {...blockProps}>
            <nav className={`navbar navbar-expand-lg navbar-${textColor} bg-${backgroundColor}`}>
                <div className="container-fluid">
                    {/* Logo/Brand */}
                    {logo.url && (
                        <a className="navbar-brand" href="/">
                            <img 
                                src={logo.url} 
                                alt={logo.alt || 'Logo'} 
                                className="navbar-logo"
                                loading="eager"
                            />
                        </a>
                    )}

                    {/* Mobile Toggle Button */}
                    <button 
                        className="navbar-toggler" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation"
                    >
                        <span className="navbar-toggler-icon"></span>
                    </button>

                    {/* Collapsible Menu */}
                    <div className="collapse navbar-collapse" id="navbarNav">
                        {/* Menu will be rendered by PHP */}
                        <div className="navbar-menu-container" data-menu-id={menuId}>
                            {/* Placeholder - actual menu rendered server-side */}
                        </div>

                        {/* Language Switcher */}
                        {showLanguageSwitcher && (
                            <div className="navbar-nav ms-auto language-switcher">
                                <button 
                                    className="btn btn-link nav-link language-btn" 
                                    data-lang="es"
                                    aria-label="Español"
                                >
                                    ES
                                </button>
                                <span className="language-separator">|</span>
                                <button 
                                    className="btn btn-link nav-link language-btn" 
                                    data-lang="en"
                                    aria-label="English"
                                >
                                    EN
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </nav>
        </header>
    );
}
