import { useBlockProps } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const { documents, category, sortBy, displayStyle } = attributes;

	const blockProps = useBlockProps.save({
		className: 'reforestamos-documents',
	});

	const getFileIcon = (fileType) => {
		const icons = {
			pdf: '📄',
			doc: '📝',
			docx: '📝',
			xls: '📊',
			xlsx: '📊',
			ppt: '📽️',
			pptx: '📽️',
			zip: '🗜️',
			default: '📎',
		};
		return icons[fileType.toLowerCase()] || icons.default;
	};

	// Filter documents by category if specified
	let filteredDocuments = [...documents];
	if (category) {
		filteredDocuments = filteredDocuments.filter(
			(doc) =>
				doc.category &&
				doc.category.toLowerCase() === category.toLowerCase()
		);
	}

	// Sort documents
	filteredDocuments.sort((a, b) => {
		switch (sortBy) {
			case 'name':
				return (a.title || '').localeCompare(b.title || '');
			case 'type':
				return (a.fileType || '').localeCompare(b.fileType || '');
			case 'size':
				return (a.fileSize || '').localeCompare(b.fileSize || '');
			case 'date':
			default:
				return new Date(b.date || 0) - new Date(a.date || 0);
		}
	});

	if (filteredDocuments.length === 0) {
		return null;
	}

	return (
		<div {...blockProps}>
			<div className={`documents-container ${displayStyle}`}>
				<div className={`documents-${displayStyle}`}>
					{filteredDocuments.map((doc, index) => (
						<div key={index} className="document-item">
							<div className="document-icon">
								<span className="icon">
									{getFileIcon(doc.fileType)}
								</span>
								<span className="file-type-label">
									{doc.fileType.toUpperCase()}
								</span>
							</div>
							<div className="document-content">
								<h3 className="document-title">{doc.title}</h3>
								{doc.description && (
									<p className="document-description">
										{doc.description}
									</p>
								)}
								<div className="document-meta">
									{doc.fileSize && (
										<span className="meta-item file-size">
											<svg
												width="16"
												height="16"
												viewBox="0 0 16 16"
												fill="currentColor"
											>
												<path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm0 14.5a6.5 6.5 0 1 1 0-13 6.5 6.5 0 0 1 0 13z" />
												<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
											</svg>
											{doc.fileSize}
										</span>
									)}
									{doc.date && (
										<span className="meta-item file-date">
											<svg
												width="16"
												height="16"
												viewBox="0 0 16 16"
												fill="currentColor"
											>
												<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
											</svg>
											{new Date(
												doc.date
											).toLocaleDateString()}
										</span>
									)}
									{doc.category && (
										<span className="meta-item file-category">
											<svg
												width="16"
												height="16"
												viewBox="0 0 16 16"
												fill="currentColor"
											>
												<path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3.879a1.5 1.5 0 0 1 1.06.44l1.122 1.12A1.5 1.5 0 0 0 9.62 3H13.5A1.5 1.5 0 0 1 15 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 11.5v-9z" />
											</svg>
											{doc.category}
										</span>
									)}
								</div>
							</div>
							{doc.url && (
								<div className="document-action">
									<a
										href={doc.url}
										className="btn btn-primary btn-download"
										download
										target="_blank"
										rel="noopener noreferrer"
									>
										<svg
											width="16"
											height="16"
											viewBox="0 0 16 16"
											fill="currentColor"
										>
											<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
											<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
										</svg>
										Descargar
									</a>
								</div>
							)}
						</div>
					))}
				</div>
			</div>
		</div>
	);
}
