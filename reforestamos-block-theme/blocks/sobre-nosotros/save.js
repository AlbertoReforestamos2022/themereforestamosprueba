import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { title, content, team, stats, showStats, showTeam } = attributes;
    
    const blockProps = useBlockProps.save({
        className: 'reforestamos-sobre-nosotros'
    });

    return (
        <div {...blockProps}>
            <div className="container py-5">
                {/* Sección de introducción */}
                <div className="sobre-nosotros-intro text-center mb-5">
                    {title && (
                        <RichText.Content
                            tagName="h2"
                            value={title}
                            className="sobre-nosotros-title"
                        />
                    )}
                    {content && (
                        <RichText.Content
                            tagName="p"
                            value={content}
                            className="sobre-nosotros-content lead"
                        />
                    )}
                </div>

                {/* Estadísticas */}
                {showStats && stats.length > 0 && (
                    <div className="sobre-nosotros-stats mb-5">
                        <div className="row g-4">
                            {stats.map((stat, index) => (
                                <div key={index} className="col-md-6 col-lg-3">
                                    <div className="stat-card text-center p-4">
                                        {stat.icon && (
                                            <span className={`stat-icon ${stat.icon}`}></span>
                                        )}
                                        <div className="stat-value">{stat.value}</div>
                                        <div className="stat-label">{stat.label}</div>
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
                                                <img 
                                                    src={member.photo.url} 
                                                    alt={member.photo.alt || member.name}
                                                    loading="lazy"
                                                />
                                            </div>
                                        )}
                                        <div className="team-info p-3">
                                            {member.name && (
                                                <h3 className="team-name">{member.name}</h3>
                                            )}
                                            {member.position && (
                                                <p className="team-position">{member.position}</p>
                                            )}
                                            {member.bio && (
                                                <p className="team-bio">{member.bio}</p>
                                            )}
                                            {(member.social.facebook || member.social.twitter || member.social.linkedin || member.social.instagram) && (
                                                <div className="team-social">
                                                    {member.social.facebook && (
                                                        <a href={member.social.facebook} target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                                            <i className="fab fa-facebook"></i>
                                                        </a>
                                                    )}
                                                    {member.social.twitter && (
                                                        <a href={member.social.twitter} target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                                            <i className="fab fa-twitter"></i>
                                                        </a>
                                                    )}
                                                    {member.social.linkedin && (
                                                        <a href={member.social.linkedin} target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                                            <i className="fab fa-linkedin"></i>
                                                        </a>
                                                    )}
                                                    {member.social.instagram && (
                                                        <a href={member.social.instagram} target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                                            <i className="fab fa-instagram"></i>
                                                        </a>
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
