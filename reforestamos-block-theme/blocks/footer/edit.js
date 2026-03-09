import { __ } from '@wordpress/i18n';
import { 
    useBlockProps, 
    InspectorControls, 
    RichText 
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    RangeControl, 
    ToggleControl,
    TextControl,
    Button,
    ColorPicker
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { 
        columns, 
        social, 
        copyright, 
        backgroundColor, 
        textColor, 
        columnCount,
        showSocial 
    } = attributes;
    
    const blockProps = useBlockProps({
        style: {
            backgroundColor: backgroundColor,
            color: textColor,
        },
        className: 'reforestamos-footer'
    });

    const updateColumn = (index, field, value) => {
        const newColumns = [...columns];
        newColumns[index] = { ...newColumns[index], [field]: value };
        setAttributes({ columns: newColumns });
    };

    const addLink = (columnIndex) => {
        const newColumns = [...columns];
        if (!newColumns[columnIndex].links) {
            newColumns[columnIndex].links = [];
        }
        newColumns[columnIndex].links.push({ text: '', url: '' });
        setAttributes({ columns: newColumns });
    };

    const updateLink = (columnIndex, linkIndex, field, value) => {
        const newColumns = [...columns];
        newColumns[columnIndex].links[linkIndex][field] = value;
        setAttributes({ columns: newColumns });
    };

    const removeLink = (columnIndex, linkIndex) => {
        const newColumns = [...columns];
        newColumns[columnIndex].links.splice(linkIndex, 1);
        setAttributes({ columns: newColumns });
    };

    const updateSocial = (index, field, value) => {
        const newSocial = [...social];
        newSocial[index] = { ...newSocial[index], [field]: value };
        setAttributes({ social: newSocial });
    };

    const addSocialLink = () => {
        setAttributes({ 
            social: [...social, { name: '', url: '', icon: 'link' }] 
        });
    };

    const removeSocialLink = (index) => {
        const newSocial = [...social];
        newSocial.splice(index, 1);
        setAttributes({ social: newSocial });
    };

    // Ajustar número de columnas
    const adjustColumnCount = (newCount) => {
        const newColumns = [...columns];
        if (newCount > columns.length) {
            // Agregar columnas
            for (let i = columns.length; i < newCount; i++) {
                newColumns.push({
                    title: `Columna ${i + 1}`,
                    content: '',
                    links: []
                });
            }
        } else if (newCount < columns.length) {
            // Remover columnas
            newColumns.splice(newCount);
        }
        setAttributes({ columns: newColumns, columnCount: newCount });
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Configuración del Footer', 'reforestamos')}>
                    <RangeControl
                        label={__('Número de Columnas', 'reforestamos')}
                        value={columnCount}
                        onChange={adjustColumnCount}
                        min={2}
                        max={4}
                    />
                    <ToggleControl
                        label={__('Mostrar Redes Sociales', 'reforestamos')}
                        checked={showSocial}
                        onChange={(value) => setAttributes({ showSocial: value })}
                    />
                </PanelBody>

                <PanelBody title={__('Colores', 'reforestamos')} initialOpen={false}>
                    <p><strong>{__('Color de Fondo', 'reforestamos')}</strong></p>
                    <ColorPicker
                        color={backgroundColor}
                        onChangeComplete={(value) => setAttributes({ backgroundColor: value.hex })}
                    />
                    <p><strong>{__('Color de Texto', 'reforestamos')}</strong></p>
                    <ColorPicker
                        color={textColor}
                        onChangeComplete={(value) => setAttributes({ textColor: value.hex })}
                    />
                </PanelBody>

                <PanelBody title={__('Redes Sociales', 'reforestamos')} initialOpen={false}>
                    {social.map((item, index) => (
                        <div key={index} style={{ marginBottom: '15px', padding: '10px', border: '1px solid #ddd' }}>
                            <TextControl
                                label={__('Nombre', 'reforestamos')}
                                value={item.name}
                                onChange={(value) => updateSocial(index, 'name', value)}
                            />
                            <TextControl
                                label={__('URL', 'reforestamos')}
                                value={item.url}
                                onChange={(value) => updateSocial(index, 'url', value)}
                            />
                            <TextControl
                                label={__('Icono (Font Awesome)', 'reforestamos')}
                                value={item.icon}
                                onChange={(value) => updateSocial(index, 'icon', value)}
                                help={__('Ej: facebook, twitter, instagram, linkedin', 'reforestamos')}
                            />
                            <Button 
                                isDestructive 
                                onClick={() => removeSocialLink(index)}
                            >
                                {__('Eliminar', 'reforestamos')}
                            </Button>
                        </div>
                    ))}
                    <Button 
                        isPrimary 
                        onClick={addSocialLink}
                    >
                        {__('Agregar Red Social', 'reforestamos')}
                    </Button>
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="footer-content container">
                    <div className="row">
                        {columns.slice(0, columnCount).map((column, columnIndex) => (
                            <div key={columnIndex} className={`col-md-${12 / columnCount} footer-column`}>
                                <RichText
                                    tagName="h4"
                                    value={column.title}
                                    onChange={(value) => updateColumn(columnIndex, 'title', value)}
                                    placeholder={__('Título de columna...', 'reforestamos')}
                                    className="footer-column-title"
                                />
                                <RichText
                                    tagName="p"
                                    value={column.content}
                                    onChange={(value) => updateColumn(columnIndex, 'content', value)}
                                    placeholder={__('Contenido...', 'reforestamos')}
                                    className="footer-column-content"
                                />
                                
                                <div className="footer-links">
                                    {column.links && column.links.map((link, linkIndex) => (
                                        <div key={linkIndex} className="footer-link-item">
                                            <TextControl
                                                placeholder={__('Texto del enlace', 'reforestamos')}
                                                value={link.text}
                                                onChange={(value) => updateLink(columnIndex, linkIndex, 'text', value)}
                                            />
                                            <TextControl
                                                placeholder={__('URL', 'reforestamos')}
                                                value={link.url}
                                                onChange={(value) => updateLink(columnIndex, linkIndex, 'url', value)}
                                            />
                                            <Button 
                                                isSmall 
                                                isDestructive 
                                                onClick={() => removeLink(columnIndex, linkIndex)}
                                            >
                                                {__('Eliminar', 'reforestamos')}
                                            </Button>
                                        </div>
                                    ))}
                                    <Button 
                                        isSecondary 
                                        isSmall 
                                        onClick={() => addLink(columnIndex)}
                                    >
                                        {__('+ Agregar Enlace', 'reforestamos')}
                                    </Button>
                                </div>
                            </div>
                        ))}
                    </div>

                    {showSocial && (
                        <div className="footer-social">
                            <div className="social-links">
                                {social.map((item, index) => (
                                    item.url && (
                                        <a 
                                            key={index} 
                                            href={item.url} 
                                            className="social-link"
                                            aria-label={item.name}
                                        >
                                            <i className={`fab fa-${item.icon}`}></i>
                                        </a>
                                    )
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="footer-copyright">
                        <RichText
                            tagName="p"
                            value={copyright}
                            onChange={(value) => setAttributes({ copyright: value })}
                            placeholder={__('Texto de copyright...', 'reforestamos')}
                            className="copyright-text"
                        />
                    </div>
                </div>
            </div>
        </>
    );
}
