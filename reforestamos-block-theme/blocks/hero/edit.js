/**
 * Hero Block — Editor Component
 *
 * Renders the editor interface for the Hero block, including
 * InspectorControls for background image, overlay opacity,
 * min height, and button URL configuration.
 *
 * @package
 * @since 1.0.0
 */

import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	RichText,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	TextControl,
	Button,
} from '@wordpress/components';

/**
 * Edit component for the Hero block.
 *
 * @param {Object}   props               - Block props from the editor.
 * @param {Object}   props.attributes    - Block attributes (title, subtitle, backgroundImage, etc.).
 * @param {Function} props.setAttributes - Callback to update block attributes.
 * @return {Element} The editor UI for the Hero block.
 */
export default function Edit({ attributes, setAttributes }) {
	const {
		title,
		subtitle,
		backgroundImage,
		buttonText,
		buttonUrl,
		overlayOpacity,
		minHeight,
	} = attributes;

	const blockProps = useBlockProps({
		style: {
			backgroundImage: backgroundImage.url
				? `url(${backgroundImage.url})`
				: 'none',
			minHeight,
		},
		className: 'reforestamos-hero',
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Hero Settings', 'reforestamos')}>
					<MediaUpload
						onSelect={(media) =>
							setAttributes({
								backgroundImage: {
									url: media.url,
									alt: media.alt,
									id: media.id,
								},
							})
						}
						allowedTypes={['image']}
						value={backgroundImage.id}
						render={({ open }) => (
							<Button onClick={open} variant="secondary">
								{backgroundImage.url
									? __('Change Image', 'reforestamos')
									: __('Select Image', 'reforestamos')}
							</Button>
						)}
					/>
					{backgroundImage.url && (
						<Button
							onClick={() =>
								setAttributes({
									backgroundImage: {
										url: '',
										alt: '',
										id: null,
									},
								})
							}
							variant="link"
							isDestructive
						>
							{__('Remove Image', 'reforestamos')}
						</Button>
					)}
					<RangeControl
						label={__('Overlay Opacity', 'reforestamos')}
						value={overlayOpacity}
						onChange={(value) =>
							setAttributes({ overlayOpacity: value })
						}
						min={0}
						max={1}
						step={0.1}
					/>
					<TextControl
						label={__('Min Height', 'reforestamos')}
						value={minHeight}
						onChange={(value) =>
							setAttributes({ minHeight: value })
						}
						help={__('e.g., 500px, 50vh, 100%', 'reforestamos')}
					/>
					<TextControl
						label={__('Button URL', 'reforestamos')}
						value={buttonUrl}
						onChange={(value) =>
							setAttributes({ buttonUrl: value })
						}
						type="url"
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div
					className="hero-overlay"
					style={{ opacity: overlayOpacity }}
				></div>
				<div className="hero-content">
					<RichText
						tagName="h1"
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('Enter hero title…', 'reforestamos')}
						className="hero-title"
					/>
					<RichText
						tagName="p"
						value={subtitle}
						onChange={(value) => setAttributes({ subtitle: value })}
						placeholder={__('Enter subtitle…', 'reforestamos')}
						className="hero-subtitle"
					/>
					<RichText
						tagName="span"
						value={buttonText}
						onChange={(value) =>
							setAttributes({ buttonText: value })
						}
						placeholder={__('Button text…', 'reforestamos')}
						className="btn btn-primary"
					/>
				</div>
			</div>
		</>
	);
}
