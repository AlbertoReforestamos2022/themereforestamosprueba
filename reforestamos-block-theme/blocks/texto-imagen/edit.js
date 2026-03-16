import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	Button,
	Placeholder,
} from '@wordpress/components';

/**
 *
 * @param root0
 * @param root0.attributes
 * @param root0.setAttributes
 */
export default function Edit({ attributes, setAttributes }) {
	const { content, image, imagePosition, imageWidth } = attributes;

	const blockProps = useBlockProps({
		className: `reforestamos-texto-imagen image-${imagePosition}`,
	});

	const onSelectImage = (media) => {
		setAttributes({
			image: {
				url: media.url,
				alt: media.alt || '',
				id: media.id,
			},
		});
	};

	const removeImage = () => {
		setAttributes({
			image: {
				url: '',
				alt: '',
				id: null,
			},
		});
	};

	// Convert percentage to number for RangeControl
	const imageWidthNumber = parseInt(imageWidth) || 50;

	const onChangeImageWidth = (value) => {
		setAttributes({ imageWidth: `${value}%` });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Layout Settings', 'reforestamos')}>
					<SelectControl
						label={__('Image Position', 'reforestamos')}
						value={imagePosition}
						options={[
							{
								label: __('Left', 'reforestamos'),
								value: 'left',
							},
							{
								label: __('Right', 'reforestamos'),
								value: 'right',
							},
						]}
						onChange={(value) =>
							setAttributes({ imagePosition: value })
						}
						help={__(
							'Choose where to display the image',
							'reforestamos'
						)}
					/>
					<RangeControl
						label={__('Image Width', 'reforestamos')}
						value={imageWidthNumber}
						onChange={onChangeImageWidth}
						min={30}
						max={70}
						step={5}
						help={__(
							'Adjust the width of the image column',
							'reforestamos'
						)}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="texto-imagen-container">
					<div
						className="texto-imagen-content"
						style={{ width: `${100 - imageWidthNumber}%` }}
					>
						<RichText
							tagName="div"
							value={content}
							onChange={(value) =>
								setAttributes({ content: value })
							}
							placeholder={__(
								'Enter your text content here…',
								'reforestamos'
							)}
							className="content-editor"
						/>
					</div>

					<div
						className="texto-imagen-image"
						style={{ width: imageWidth }}
					>
						{!image.url ? (
							<MediaUploadCheck>
								<Placeholder
									icon="format-image"
									label={__('Image', 'reforestamos')}
									instructions={__(
										'Select an image to display',
										'reforestamos'
									)}
								>
									<MediaUpload
										onSelect={onSelectImage}
										allowedTypes={['image']}
										value={image.id}
										render={({ open }) => (
											<Button
												onClick={open}
												variant="primary"
											>
												{__(
													'Select Image',
													'reforestamos'
												)}
											</Button>
										)}
									/>
								</Placeholder>
							</MediaUploadCheck>
						) : (
							<div className="image-preview">
								<img src={image.url} alt={image.alt || ''} />
								<div className="image-controls">
									<MediaUploadCheck>
										<MediaUpload
											onSelect={onSelectImage}
											allowedTypes={['image']}
											value={image.id}
											render={({ open }) => (
												<Button
													onClick={open}
													variant="secondary"
													size="small"
												>
													{__(
														'Replace',
														'reforestamos'
													)}
												</Button>
											)}
										/>
									</MediaUploadCheck>
									<Button
										onClick={removeImage}
										variant="secondary"
										size="small"
										isDestructive
									>
										{__('Remove', 'reforestamos')}
									</Button>
								</div>
							</div>
						)}
					</div>
				</div>
			</div>
		</>
	);
}
