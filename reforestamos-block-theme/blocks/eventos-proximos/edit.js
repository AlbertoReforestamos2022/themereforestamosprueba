import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { format } from '@wordpress/date';

export default function Edit({ attributes, setAttributes }) {
    const { count, showPast, layout } = attributes;
    
    // Obtener eventos desde el store de WordPress
    const eventos = useSelect((select) => {
        const { getEntityRecords } = select('core');
        const query = {
            per_page: count,
            _embed: true,
            status: 'publish',
            orderby: 'meta_value',
            order: 'asc',
            meta_key: 'fecha_evento'
        };
        
        return getEntityRecords('postType', 'eventos', query) || [];
    }, [count]);
    
    const blockProps = useBlockProps({
        className: `eventos-proximos-block layout-${layout}`
    });
    
    const renderEventCard = (evento) => {
        const featuredImage = evento._embedded?.['wp:featuredmedia']?.[0];
        const imageUrl = featuredImage?.source_url || '';
        const fecha = evento.meta?.fecha_evento || evento.date;
        const ubicacion = evento.meta?.ubicacion || '';
        
        // Parsear fecha para mostrar día y mes
        const fechaObj = new Date(fecha);
        const dia = format('j', fechaObj);
        const mes = format('M', fechaObj);
        
        return (
            <div key={evento.id} className="evento-card">
                {imageUrl && (
                    <div className="evento-image">
                        <img src={imageUrl} alt={evento.title.rendered} loading="lazy" />
                        <div className="evento-fecha-badge">
                            <span className="dia">{dia}</span>
                            <span className="mes">{mes}</span>
                        </div>
                    </div>
                )}
                <div className="evento-content">
                    <h3 className="evento-title" dangerouslySetInnerHTML={{ __html: evento.title.rendered }} />
                    {ubicacion && (
                        <p className="evento-ubicacion">
                            <span className="dashicons dashicons-location"></span>
                            {ubicacion}
                        </p>
                    )}
                    <div className="evento-excerpt" dangerouslySetInnerHTML={{ __html: evento.excerpt.rendered }} />
                    <a href={evento.link} className="btn btn-outline-primary">
                        {__('Ver detalles', 'reforestamos')}
                    </a>
                </div>
            </div>
        );
    };
    
    const renderEventList = (evento) => {
        const fecha = evento.meta?.fecha_evento || evento.date;
        const ubicacion = evento.meta?.ubicacion || '';
        const fechaObj = new Date(fecha);
        const fechaFormateada = format('F j, Y', fechaObj);
        
        return (
            <div key={evento.id} className="evento-list-item">
                <div className="evento-fecha">
                    <span className="dashicons dashicons-calendar-alt"></span>
                    {fechaFormateada}
                </div>
                <div className="evento-info">
                    <h4 className="evento-title" dangerouslySetInnerHTML={{ __html: evento.title.rendered }} />
                    {ubicacion && (
                        <p className="evento-ubicacion">
                            <span className="dashicons dashicons-location"></span>
                            {ubicacion}
                        </p>
                    )}
                </div>
                <a href={evento.link} className="btn btn-sm btn-primary">
                    {__('Ver más', 'reforestamos')}
                </a>
            </div>
        );
    };
    
    const renderEventGrid = (evento) => {
        const featuredImage = evento._embedded?.['wp:featuredmedia']?.[0];
        const imageUrl = featuredImage?.source_url || '';
        const fecha = evento.meta?.fecha_evento || evento.date;
        const ubicacion = evento.meta?.ubicacion || '';
        const fechaObj = new Date(fecha);
        const fechaFormateada = format('F j, Y', fechaObj);
        
        return (
            <div key={evento.id} className="col-md-6 col-lg-4 mb-4">
                <div className="evento-grid-card">
                    {imageUrl && (
                        <div className="evento-image">
                            <img src={imageUrl} alt={evento.title.rendered} loading="lazy" />
                        </div>
                    )}
                    <div className="evento-body">
                        <div className="evento-meta">
                            <span className="evento-fecha">
                                <span className="dashicons dashicons-calendar-alt"></span>
                                {fechaFormateada}
                            </span>
                            {ubicacion && (
                                <span className="evento-ubicacion">
                                    <span className="dashicons dashicons-location"></span>
                                    {ubicacion}
                                </span>
                            )}
                        </div>
                        <h4 className="evento-title" dangerouslySetInnerHTML={{ __html: evento.title.rendered }} />
                        <a href={evento.link} className="btn btn-sm btn-outline-primary">
                            {__('Ver detalles', 'reforestamos')}
                        </a>
                    </div>
                </div>
            </div>
        );
    };
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Configuración de Eventos', 'reforestamos')}>
                    <RangeControl
                        label={__('Número de eventos', 'reforestamos')}
                        value={count}
                        onChange={(value) => setAttributes({ count: value })}
                        min={1}
                        max={12}
                    />
                    <ToggleControl
                        label={__('Mostrar eventos pasados', 'reforestamos')}
                        checked={showPast}
                        onChange={(value) => setAttributes({ showPast: value })}
                        help={__('Si está activado, se mostrarán también eventos que ya pasaron', 'reforestamos')}
                    />
                    <SelectControl
                        label={__('Diseño', 'reforestamos')}
                        value={layout}
                        options={[
                            { label: __('Tarjetas', 'reforestamos'), value: 'cards' },
                            { label: __('Lista', 'reforestamos'), value: 'list' },
                            { label: __('Grid', 'reforestamos'), value: 'grid' }
                        ]}
                        onChange={(value) => setAttributes({ layout: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <div className="eventos-proximos-header">
                    <h2>{__('Próximos Eventos', 'reforestamos')}</h2>
                </div>
                
                {eventos.length === 0 ? (
                    <div className="eventos-empty">
                        <p>{__('No hay eventos disponibles. Los eventos se cargarán cuando el CPT "Eventos" esté configurado.', 'reforestamos')}</p>
                        <p className="text-muted">{__('Este bloque está listo para consumir la REST API de eventos.', 'reforestamos')}</p>
                    </div>
                ) : (
                    <div className={`eventos-container layout-${layout}`}>
                        {layout === 'cards' && (
                            <div className="eventos-cards">
                                {eventos.map(renderEventCard)}
                            </div>
                        )}
                        
                        {layout === 'list' && (
                            <div className="eventos-list">
                                {eventos.map(renderEventList)}
                            </div>
                        )}
                        
                        {layout === 'grid' && (
                            <div className="row">
                                {eventos.map(renderEventGrid)}
                            </div>
                        )}
                    </div>
                )}
            </div>
        </>
    );
}
