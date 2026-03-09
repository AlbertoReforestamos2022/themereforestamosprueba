import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, RichText, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, ToggleControl, IconButton } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { title, content, team, stats, showStats, showTeam } = attributes;
    
    const blockProps = useBlockProps({
        className: 'reforestamos-sobre-nosotros'
    });

    const addTeamMember = () => {
        const newTeam = [...team, {
            name: '',
            position: '',
            bio: '',
            photo: { url: '', alt: '', id: null },
            social: { facebook: '', twitter: '', linkedin: '', instagram: '' }
        }];
        setAttributes({ team: newTeam });
    };

    const updateTeamMember = (index, field, value) => {
        const newTeam = [...team];
        if (field.includes('.')) {
            const [parent, child] = field.split('.');
            newTeam[index][parent][child] = value;
        } else {
            newTeam[index][field] = value;
        }
        setAttributes({ team: newTeam });
    };

    const removeTeamMember = (index) => {
        const newTeam = team.filter((_, i) => i !== index);
        setAttributes({ team: newTeam });
    };

    const addStat = () => {
        const newStats = [...stats, {
            value: '',
            label: '',
            icon: ''
        }];
        setAttributes({ stats: newStats });
    };

    const updateStat = (index, field, value) => {
        const newStats = [...stats];
        newStats[index][field] = value;
        setAttributes({ stats: newStats });
    };

    const removeStat = (index) => {
        const newStats = stats.filter((_, i) => i !== index);
        setAttributes({ stats: newStats });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Configuración de Sección', 'reforestamos')}>
                    <ToggleControl
                        label={__('Mostrar Estadísticas', 'reforestamos')}
                        checked={showStats}
                        onChange={(value) => setAttributes({ showStats: value })}
                    />
                    <ToggleControl
                        label={__('Mostrar Equipo', 'reforestamos')}
                        checked={showTeam}
                        onChange={(value) => setAttributes({ showTeam: value })}
                    />
                </PanelBody>

                {showStats && (
                    <PanelBody title={__('Estadísticas', 'reforestamos')} initialOpen={false}>
                        {stats.map((stat, index) => (
                            <div key={index} style={{ marginBottom: '20px', padding: '10px', border: '1px solid #ddd' }}>
                                <TextControl
                                    label={__('Valor', 'reforestamos')}
                                    value={stat.value}
                                    onChange={(value) => updateStat(index, 'value', value)}
                                    placeholder="1000+"
                                />
                                <TextControl
                                    label={__('Etiqueta', 'reforestamos')}
                                    value={stat.label}
                                    onChange={(value) => updateStat(index, 'label', value)}
                                    placeholder="Árboles plantados"
                                />
                                <TextControl
                                    label={__('Icono (clase)', 'reforestamos')}
                                    value={stat.icon}
                                    onChange={(value) => updateStat(index, 'icon', value)}
                                    placeholder="dashicons-palmtree"
                                />
                                <Button
                                    isDestructive
                                    onClick={() => removeStat(index)}
                                >
                                    {__('Eliminar Estadística', 'reforestamos')}
                                </Button>
                            </div>
                        ))}
                        <Button isPrimary onClick={addStat}>
                            {__('Agregar Estadística', 'reforestamos')}
                        </Button>
                    </PanelBody>
                )}

                {showTeam && (
                    <PanelBody title={__('Miembros del Equipo', 'reforestamos')} initialOpen={false}>
                        {team.map((member, index) => (
                            <div key={index} style={{ marginBottom: '20px', padding: '10px', border: '1px solid #ddd' }}>
                                <TextControl
                                    label={__('Nombre', 'reforestamos')}
                                    value={member.name}
                                    onChange={(value) => updateTeamMember(index, 'name', value)}
                                />
                                <TextControl
                                    label={__('Cargo', 'reforestamos')}
                                    value={member.position}
                                    onChange={(value) => updateTeamMember(index, 'position', value)}
                                />
                                <TextControl
                                    label={__('Biografía', 'reforestamos')}
                                    value={member.bio}
                                    onChange={(value) => updateTeamMember(index, 'bio', value)}
                                />
                                <MediaUpload
                                    onSelect={(media) => updateTeamMember(index, 'photo', {
                                        url: media.url,
                                        alt: media.alt,
                                        id: media.id
                                    })}
                                    allowedTypes={['image']}
                                    value={member.photo.id}
                                    render={({ open }) => (
                                        <Button onClick={open} variant="secondary">
                                            {member.photo.url ? __('Cambiar Foto', 'reforestamos') : __('Seleccionar Foto', 'reforestamos')}
                                        </Button>
                                    )}
                                />
                                <hr />
                                <strong>{__('Redes Sociales', 'reforestamos')}</strong>
                                <TextControl
                                    label="Facebook"
                                    value={member.social.facebook}
                                    onChange={(value) => updateTeamMember(index, 'social.facebook', value)}
                                    placeholder="https://facebook.com/..."
                                />
                                <TextControl
                                    label="Twitter"
                                    value={member.social.twitter}
                                    onChange={(value) => updateTeamMember(index, 'social.twitter', value)}
                                    placeholder="https://twitter.com/..."
                                />
                                <TextControl
                                    label="LinkedIn"
                                    value={member.social.linkedin}
                                    onChange={(value) => updateTeamMember(index, 'social.linkedin', value)}
                                    placeholder="https://linkedin.com/in/..."
                                />
                                <TextControl
                                    label="Instagram"
                                    value={member.social.instagram}
                                    onChange={(value) => updateTeamMember(index, 'social.instagram', value)}
                                    placeholder="https://instagram.com/..."
                                />
                                <Button
                                    isDestructive
                                    onClick={() => removeTeamMember(index)}
                                >
                                    {__('Eliminar Miembro', 'reforestamos')}
                                </Button>
                            </div>
                        ))}
                        <Button isPrimary onClick={addTeamMember}>
                            {__('Agregar Miembro', 'reforestamos')}
                        </Button>
                    </PanelBody>
                )}
            </InspectorControls>

            <div {...blockProps}>
                <div className="container py-5">
                    {/* Sección de introducción */}
                    <div className="sobre-nosotros-intro text-center mb-5">
                        <RichText
                            tagName="h2"
                            value={title}
                            onChange={(value) => setAttributes({ title: value })}
                            placeholder={__('Título de la sección...', 'reforestamos')}
                            className="sobre-nosotros-title"
                        />
                        <RichText
                            tagName="p"
                            value={content}
                            onChange={(value) => setAttributes({ content: value })}
                            placeholder={__('Descripción de la organización...', 'reforestamos')}
                            className="sobre-nosotros-content lead"
                        />
                    </div>

                    {/* Estadísticas */}
                    {showStats && stats.length > 0 && (
                        <div className="sobre-nosotros-stats mb-5">
                            <div className="row g-4">
                                {stats.map((stat, index) => (
                                    <div key={index} className="col-md-6 col-lg-3">
                                        <div className="stat-card text-center p-4">
                                            {stat.icon && <span className={`stat-icon ${stat.icon}`}></span>}
                                            <div className="stat-value">{stat.value || '0'}</div>
                                            <div className="stat-label">{stat.label || __('Etiqueta', 'reforestamos')}</div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Equipo */}
                    {showTeam && team.length > 0 && (
                        <div className="sobre-nosotros-team">
                            <div className="row g-4">
                                {team.map((member, index) => (
                                    <div key={index} className="col-md-6 col-lg-4">
                                        <div className="team-card">
                                            {member.photo.url && (
                                                <div className="team-photo">
                                                    <img src={member.photo.url} alt={member.photo.alt || member.name} />
                                                </div>
                                            )}
                                            <div className="team-info p-3">
                                                <h3 className="team-name">{member.name || __('Nombre', 'reforestamos')}</h3>
                                                <p className="team-position">{member.position || __('Cargo', 'reforestamos')}</p>
                                                {member.bio && <p className="team-bio">{member.bio}</p>}
                                                <div className="team-social">
                                                    {member.social.facebook && <span>FB </span>}
                                                    {member.social.twitter && <span>TW </span>}
                                                    {member.social.linkedin && <span>LI </span>}
                                                    {member.social.instagram && <span>IG </span>}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
