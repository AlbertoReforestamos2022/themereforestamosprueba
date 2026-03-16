import { useBlockProps } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const { galleries, defaultTab } = attributes;

	const blockProps = useBlockProps.save({
		className: 'reforestamos-galeria-tabs',
	});

	if (!galleries || galleries.length === 0) {
		return null;
	}

	// Generate unique ID for this gallery instance
	const galleryId = `gallery-${Math.random().toString(36).substr(2, 9)}`;

	return (
		<div {...blockProps}>
			<div className="galeria-tabs-container">
				{/* Tabs Navigation */}
				<ul
					className="nav nav-tabs"
					id={`${galleryId}-tabs`}
					role="tablist"
				>
					{galleries.map((gallery, index) => (
						<li
							className="nav-item"
							role="presentation"
							key={index}
						>
							<button
								className={`nav-link ${index === defaultTab ? 'active' : ''}`}
								id={`${galleryId}-tab-${index}`}
								data-bs-toggle="tab"
								data-bs-target={`#${galleryId}-content-${index}`}
								type="button"
								role="tab"
								aria-controls={`${galleryId}-content-${index}`}
								aria-selected={index === defaultTab}
							>
								{gallery.title}
							</button>
						</li>
					))}
				</ul>

				{/* Tab Content */}
				<div className="tab-content" id={`${galleryId}-content`}>
					{galleries.map((gallery, galleryIndex) => (
						<div
							key={galleryIndex}
							className={`tab-pane fade ${galleryIndex === defaultTab ? 'show active' : ''}`}
							id={`${galleryId}-content-${galleryIndex}`}
							role="tabpanel"
							aria-labelledby={`${galleryId}-tab-${galleryIndex}`}
						>
							{gallery.images && gallery.images.length > 0 ? (
								<div className="row g-3 gallery-grid">
									{gallery.images.map((image, imageIndex) => (
										<div
											key={imageIndex}
											className="col-6 col-md-4 col-lg-3"
										>
											<a
												href={image.url}
												className="gallery-item glightbox"
												data-gallery={`gallery-${galleryIndex}`}
												data-glightbox={`title: ${image.caption || image.alt}; description: ${image.caption || ''}`}
											>
												<div className="gallery-image-wrapper">
													<img
														src={image.url}
														alt={image.alt}
														className="img-fluid"
														loading="lazy"
													/>
													<div className="gallery-overlay">
														<span className="gallery-icon">
															<svg
																xmlns="http://www.w3.org/2000/svg"
																width="24"
																height="24"
																viewBox="0 0 24 24"
																fill="none"
																stroke="currentColor"
																strokeWidth="2"
																strokeLinecap="round"
																strokeLinejoin="round"
															>
																<circle
																	cx="11"
																	cy="11"
																	r="8"
																></circle>
																<line
																	x1="21"
																	y1="21"
																	x2="16.65"
																	y2="16.65"
																></line>
																<line
																	x1="11"
																	y1="8"
																	x2="11"
																	y2="14"
																></line>
																<line
																	x1="8"
																	y1="11"
																	x2="14"
																	y2="11"
																></line>
															</svg>
														</span>
													</div>
												</div>
											</a>
										</div>
									))}
								</div>
							) : (
								<p className="no-images-message">
									No hay imágenes en esta categoría.
								</p>
							)}
						</div>
					))}
				</div>
			</div>
		</div>
	);
}
