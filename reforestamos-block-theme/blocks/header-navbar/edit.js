import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { 
    PanelBody, 
    ToggleControl, 
    Button, 
    SelectControl,
    Placeholder 
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
    const { menuId, logo, sticky, backgroundColor, textColor, showLanguageSwitcher, transparentOnTop } = attributes;
    const [menuItems, setMenuItems] = useState([]);
    
    const blockProps = useBlockProps({
        className: 'reforestamos-header-navbar-editor'
    });

    // Get available menus from WordPress
    const menus = useSelect((select) => {
        const { getEntityRecords } = select('core');
        return getEntityRecords('taxonomy', 'nav_menu', { per_page: -1 }) || [];
    }, []);

    // Fetch menu items when menuId changes
    useEffect(() => {
        if (menuId) {
            // In the editor, we'll show a preview of menu structure
            // The actual menu will be rendered on the frontend via wp_nav_menu
            fetch(`/wp-json/wp/v2/menu-items?menus=${menuId}&per_page=100`)
                .then(response => response.json())
                .then(data => {
                    setMenuItems(data);
                })
                .catch(error => {
                    console.error('Error fetching menu items:', error);
                    setMenuItems([]);
                });
        }
    }, [menuId]);

    const menuOptions = [
        { label: __('Select a menu...', 'reforestamos'), value: '' },
        ...menus.map(menu => ({
            label: menu.name,
            value: menu.id.toString()
        }))
    ];

    const colorOptions = [
        { label: __('White', 'reforestamos'), value: 'white' },
        { label: __('Light', 'reforestamos'), value: 'light' },
        { label: __('Primary', 'reforestamos'), value: 'primary' },
        { label: __('Dark', 'reforestamos'), value: 'dark' },
    ];

    const textColorOptions = [
        { label: __('Dark', 'reforestamos'), value: 'dark' },
        { label: __('Light', 'reforestamos'), value: 'light' },
    ];
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Header Settings', 'reforestamos')}>
                    <SelectControl
                        label={__('Select Menu', 'reforestamos')}
                        value={menuId}
                        options={menuOptions}
                        onChange={(value) => setAttributes({ menuId: value })}
                        help={__('Choose which WordPress menu to display', 'reforestamos')}
                    />
                    
                    <MediaUploadCheck>
                        <div className="logo-upload-section">
                            <label>{__('Logo', 'reforestamos')}</label>
                            {logo.url ? (
                                <div className="logo-preview">
                                    <img src={logo.url} alt={logo.alt || ''} style={{ maxWidth: '200px', height: 'auto' }} />
                                    <div className="logo-controls">
                                        <MediaUpload
                                            onSelect={(media) => setAttributes({
                                                logo: {
                                                    url: media.url,
                                                    alt: media.alt,
                                                    id: media.id
                                                }
                                            })}
                                            allowedTypes={['image']}
                                            value={logo.id}
                                            render={({ open }) => (
                                                <Button onClick={open} variant="secondary" size="small">
                                                    {__('Change Logo', 'reforestamos')}
                                                </Button>
                                            )}
                                        />
                                        <Button 
                                            onClick={() => setAttributes({ logo: { url: '', alt: '', id: null } })}
                                            variant="tertiary"
                                            isDestructive
                                            size="small"
                                        >
                                            {__('Remove', 'reforestamos')}
                                        </Button>
                                    </div>
                                </div>
                            ) : (
                                <MediaUpload
                                    onSelect={(media) => setAttributes({
                                        logo: {
                                            url: media.url,
                                            alt: media.alt,
                                            id: media.id
                                        }
                                    })}
                                    allowedTypes={['image']}
                                    value={logo.id}
                                    render={({ open }) => (
                                        <Button onClick={open} variant="secondary">
                                            {__('Select Logo', 'reforestamos')}
                                        </Button>
                                    )}
                                />
                            )}
                        </div>
                    </MediaUploadCheck>
                </PanelBody>

                <PanelBody title={__('Appearance', 'reforestamos')} initialOpen={false}>
                    <SelectControl
                        label={__('Background Color', 'reforestamos')}
                        value={backgroundColor}
                        options={colorOptions}
                        onChange={(value) => setAttributes({ backgroundColor: value })}
                    />
                    
                    <SelectControl
                        label={__('Text Color', 'reforestamos')}
                        value={textColor}
                        options={textColorOptions}
                        onChange={(value) => setAttributes({ textColor: value })}
                    />

                    <ToggleControl
                        label={__('Sticky Header', 'reforestamos')}
                        checked={sticky}
                        onChange={(value) => setAttributes({ sticky: value })}
                        help={__('Header stays fixed at top when scrolling', 'reforestamos')}
                    />

                    <ToggleControl
                        label={__('Transparent on Top', 'reforestamos')}
                        checked={transparentOnTop}
                        onChange={(value) => setAttributes({ transparentOnTop: value })}
                        help={__('Header becomes transparent when at page top, solid when scrolling', 'reforestamos')}
                    />
                </PanelBody>

                <PanelBody title={__('Features', 'reforestamos')} initialOpen={false}>
                    <ToggleControl
                        label={__('Show Language Switcher', 'reforestamos')}
                        checked={showLanguageSwitcher}
                        onChange={(value) => setAttributes({ showLanguageSwitcher: value })}
                        help={__('Display language switcher (ES/EN)', 'reforestamos')}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className={`header-navbar-preview bg-${backgroundColor} text-${textColor}`}>
                    <div className="container-fluid">
                        <div className="navbar-preview-content">
                            {/* Logo */}
                            <div className="navbar-brand-preview">
                                {logo.url ? (
                                    <img src={logo.url} alt={logo.alt || 'Logo'} className="navbar-logo" />
                                ) : (
                                    <div className="navbar-logo-placeholder">
                                        <span>{__('Logo', 'reforestamos')}</span>
                                    </div>
                                )}
                            </div>

                            {/* Menu Preview */}
                            <div className="navbar-menu-preview">
                                {menuId ? (
                                    menuItems.length > 0 ? (
                                        <ul className="nav-preview-list">
                                            {menuItems.slice(0, 5).map((item) => (
                                                <li key={item.id}>
                                                    <span>{item.title.rendered}</span>
                                                </li>
                                            ))}
                                            {menuItems.length > 5 && (
                                                <li><span>...</span></li>
                                            )}
                                        </ul>
                                    ) : (
                                        <p className="menu-loading">{__('Loading menu...', 'reforestamos')}</p>
                                    )
                                ) : (
                                    <p className="menu-placeholder">{__('Select a menu in settings →', 'reforestamos')}</p>
                                )}
                            </div>

                            {/* Language Switcher Preview */}
                            {showLanguageSwitcher && (
                                <div className="language-switcher-preview">
                                    <button className="lang-btn active">ES</button>
                                    <button className="lang-btn">EN</button>
                                </div>
                            )}

                            {/* Mobile Toggle */}
                            <div className="navbar-toggler-preview">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>

                    {/* Preview Info */}
                    <div className="preview-info">
                        {sticky && <span className="badge">Sticky</span>}
                        {transparentOnTop && <span className="badge">Transparent on Top</span>}
                        {menuId && <span className="badge">Menu: {menus.find(m => m.id.toString() === menuId)?.name}</span>}
                    </div>
                </div>
            </div>
        </>
    );
}
