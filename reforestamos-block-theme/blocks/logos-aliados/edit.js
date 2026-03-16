import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	Button,
	TextControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 *
 * @param root0
 * @param root0.attributes
 * @param root0.setAttributes
 */
export default function Edit({ attributes, setAttributes }) {
	const { logos, columns, linkable } = attributes;
	const [editingIndex, setEditingIndex] = useState(null);

	const blockProps = useBlockProps({
		className: 'reforestamos-logos-aliados',
	});

	const addLogo = () => {
		const newLogos = [
			...logos,
			{
				id: Date.now(),
				imageUrl: '',
				imageId: null,
				alt: '',
				name: '',
				url: '',
			},
		];
		setAttributes({ logos: newLogos });
	};

	const updateLogo = (index, field, value) => {
		const newLogos = [...logos];
		newLogos[index][field] = value;
		setAttributes({ logos: newLogos });
	};

	const removeLogo = (index) => {
		const newLogos = logos.filter((_, i) => i !== index);
		setAttributes({ logos: newLogos });
		if (editingIndex === index) {
			setEditingIndex(null);
		}
	};

	const moveLogo = (index, direction) => {
		const newLogos = [...logos];
		const newIndex = direction === 'up' ? index - 1 : index + 1;

		if (newIndex >= 0 && newIndex < logos.length) {
			[newLogos[index], newLogos[newIndex]] = [
				newLogos[newIndex],
				newLogos[index],
			];
			setAttributes({ logos: newLogos });
		}
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Configuración del Grid', 'reforestamos')}>
					<RangeControl
						label={__('Número de Columnas', 'reforestamos')}
						value={columns}
						onChange={(value) => setAttributes({ columns: value })}
						min={2}
						max={6}
						step={1}
						help={__(
							'Define cuántas columnas tendrá el grid de logos',
							'reforestamos'
						)}
					/>
					<ToggleControl
						label={__('Logos Clicables', 'reforestamos')}
						checked={linkable}
						onChange={(value) => setAttributes({ linkable: value })}
						help={
							linkable
								? __(
										'Los logos son enlaces clicables',
										'reforestamos'
									)
								: __(
										'Los logos no son clicables',
										'reforestamos'
									)
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="logos-aliados-editor">
					<div className="editor-header">
						<h3>{__('Logos de Aliados', 'reforestamos')}</h3>
						<Button variant="primary" onClick={addLogo}>
							{__('Agregar Logo', 'reforestamos')}
						</Button>
					</div>

					{logos.length === 0 && (
						<div className="empty-state">
							<p>
								{__(
									'No hay logos agregados. Haz clic en "Agregar Logo" para comenzar.',
									'reforestamos'
								)}
							</p>
						</div>
					)}

					<div className={`logos-grid columns-${columns}`}>
						{logos.map((logo, index) => (
							<div key={logo.id} className="logo-item">
								<div className="logo-preview">
									{logo.imageUrl ? (
										<img
											src={logo.imageUrl}
											alt={logo.alt || logo.name}
										/>
									) : (
										<div className="logo-placeholder">
											<span>
												{__(
													'Sin imagen',
													'reforestamos'
												)}
											</span>
										</div>
									)}
								</div>

								<div className="logo-controls">
									<MediaUploadCheck>
										<MediaUpload
											onSelect={(media) => {
												updateLogo(
													index,
													'imageUrl',
													media.url
												);
												updateLogo(
													index,
													'imageId',
													media.id
												);
												updateLogo(
													index,
													'alt',
													media.alt || ''
												);
											}}
											allowedTypes={['image']}
											value={logo.imageId}
											render={({ open }) => (
												<Button
													onClick={open}
													variant="secondary"
													isSmall
												>
													{logo.imageUrl
														? __(
																'Cambiar',
																'reforestamos'
															)
														: __(
																'Seleccionar',
																'reforestamos'
															)}
												</Button>
											)}
										/>
									</MediaUploadCheck>

									<Button
										onClick={() =>
											setEditingIndex(
												editingIndex === index
													? null
													: index
											)
										}
										variant="secondary"
										isSmall
									>
										{editingIndex === index
											? __('Cerrar', 'reforestamos')
											: __('Editar', 'reforestamos')}
									</Button>

									<Button
										onClick={() => moveLogo(index, 'up')}
										variant="secondary"
										isSmall
										disabled={index === 0}
									>
										↑
									</Button>

									<Button
										onClick={() => moveLogo(index, 'down')}
										variant="secondary"
										isSmall
										disabled={index === logos.length - 1}
									>
										↓
									</Button>

									<Button
										onClick={() => removeLogo(index)}
										variant="secondary"
										isSmall
										isDestructive
									>
										{__('Eliminar', 'reforestamos')}
									</Button>
								</div>

								{editingIndex === index && (
									<div className="logo-edit-panel">
										<TextControl
											label={__(
												'Nombre del Aliado',
												'reforestamos'
											)}
											value={logo.name}
											onChange={(value) =>
												updateLogo(index, 'name', value)
											}
											placeholder={__(
												'Ej: Empresa XYZ',
												'reforestamos'
											)}
										/>
										<TextControl
											label={__(
												'Texto Alternativo',
												'reforestamos'
											)}
											value={logo.alt}
											onChange={(value) =>
												updateLogo(index, 'alt', value)
											}
											placeholder={__(
												'Descripción de la imagen',
												'reforestamos'
											)}
											help={__(
												'Importante para accesibilidad',
												'reforestamos'
											)}
										/>
										{linkable && (
											<TextControl
												label={__(
													'URL del Sitio Web',
													'reforestamos'
												)}
												value={logo.url}
												onChange={(value) =>
													updateLogo(
														index,
														'url',
														value
													)
												}
												placeholder={__(
													'https://ejemplo.com',
													'reforestamos'
												)}
												type="url"
											/>
										)}
									</div>
								)}
							</div>
						))}
					</div>
				</div>
			</div>
		</>
	);
}
